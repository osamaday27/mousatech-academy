<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use DomainException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * All money maths happens here, on the server, from database prices only.
 * Amounts are integer piasters.
 */
class OrderService
{
    /** @return array{0: ?Coupon, 1: ?string} [coupon, error] */
    public function coupon(?string $code, int $payable, ?User $user, bool $lock = false): array
    {
        $code = strtoupper(trim((string) $code));
        if ($code === '') {
            return [null, null];
        }
        $c = Coupon::where('code', $code)->when($lock, fn ($q) => $q->lockForUpdate())->first();
        $err = match (true) {
            ! $c || ! $c->is_active => 'Invalid coupon code',
            $c->starts_at && $c->starts_at->isFuture() => 'This coupon is not active yet',
            $c->expires_at && $c->expires_at->isPast() => 'This coupon has expired',
            $c->max_uses !== null && $c->used_count >= $c->max_uses => 'This coupon has reached its usage limit',
            $payable < $c->min_total => 'Your order is below the minimum for this coupon',
            $user && CouponUsage::where('coupon_id', $c->id)->where('user_id', $user->id)->count() >= $c->per_user_limit => 'You have already used this coupon',
            default => null,
        };

        return $err ? [null, $err] : [$c, null];
    }

    public function quote(Collection $courses, ?string $code, ?User $user, bool $lock = false): array
    {
        $original = (int) $courses->sum('price');
        $subtotal = (int) $courses->sum(fn ($c) => $c->currentPrice());
        [$coupon, $error] = $this->coupon($code, $subtotal, $user, $lock);
        $discount = $coupon ? $coupon->discountFor($subtotal) : 0;

        return [
            'original' => $original,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => max(0, $subtotal - $discount),
            'coupon' => $coupon,
            'error' => $error,
        ];
    }

    public function create(User $user, Collection $courses, ?string $code, string $method, ?string $proofPath): Order
    {
        return DB::transaction(function () use ($user, $courses, $code, $method, $proofPath) {
            // Re-read prices from the database; never trust what the browser showed.
            $courses = Course::live()->whereIn('id', $courses->pluck('id'))->get()
                ->reject(fn ($c) => $user->owns($c))->values();
            if ($courses->isEmpty()) {
                throw new DomainException(__('Your cart has nothing to buy'));
            }

            $q = $this->quote($courses, $code, $user, true);
            if (trim((string) $code) !== '' && $q['error']) {
                throw new DomainException(__($q['error']));
            }
            $free = $q['total'] === 0;
            if (! $free && $method === 'vodafone' && ! $proofPath) {
                throw new DomainException(__('Upload your transfer receipt'));
            }

            $order = Order::create([
                'number' => 'MT-'.now()->format('ymd').'-'.strtoupper(Str::random(5)),
                'user_id' => $user->id,
                'subtotal' => $q['subtotal'],
                'discount' => $q['discount'],
                'total' => $q['total'],
                'coupon_id' => $q['coupon']?->id,
                'coupon_code' => $q['coupon']?->code,
                'status' => ! $free && $method === 'vodafone' ? 'awaiting_verification' : 'pending',
                'payment_method' => $free ? 'free' : $method,
            ]);
            foreach ($courses as $c) {
                $order->items()->create([
                    'course_id' => $c->id,
                    'title' => $c->getTranslation('title', 'en', false) ?: $c->getTranslation('title', 'ar', false),
                    'price' => $c->currentPrice(),
                ]);
            }
            Payment::create([
                'order_id' => $order->id,
                'method' => $order->payment_method,
                'amount' => $order->total,
                'status' => 'pending',
                'proof_path' => $proofPath,
            ]);
            if ($q['coupon']) {
                $q['coupon']->increment('used_count');
                CouponUsage::create(['coupon_id' => $q['coupon']->id, 'user_id' => $user->id, 'order_id' => $order->id]);
            }
            if ($free) {
                $this->markPaid($order);
            }

            return $order->fresh();
        });
    }

    /** Idempotent: calling it twice (e.g. a duplicated webhook) never double-enrolls. */
    public function markPaid(Order $order, ?int $actor = null): Order
    {
        return DB::transaction(function () use ($order, $actor) {
            $o = Order::with('items')->lockForUpdate()->findOrFail($order->id);
            if ($o->status === 'paid') {
                return $o;
            }
            if (in_array($o->status, ['cancelled', 'refunded'], true)) {
                throw new DomainException('Order is '.$o->status);
            }
            $o->update(['status' => 'paid', 'paid_at' => now()]);
            $o->payment?->update(['status' => 'paid']);
            foreach ($o->items as $i) {
                Enrollment::firstOrCreate(
                    ['user_id' => $o->user_id, 'course_id' => $i->course_id],
                    ['order_id' => $o->id]
                );
            }
            ActivityLog::record('order.paid', $o, ['actor' => $actor]);

            return $o;
        });
    }

    public function reject(Order $order, ?int $actor = null): void
    {
        DB::transaction(function () use ($order, $actor) {
            $o = Order::lockForUpdate()->findOrFail($order->id);
            if (! in_array($o->status, ['pending', 'awaiting_verification'], true)) {
                return;
            }
            $o->update(['status' => 'failed']);
            $o->payment?->update(['status' => 'failed']);
            if ($o->coupon_id) {
                Coupon::whereKey($o->coupon_id)->where('used_count', '>', 0)->decrement('used_count');
                CouponUsage::where('order_id', $o->id)->delete();
            }
            ActivityLog::record('order.rejected', $o, ['actor' => $actor]);
        });
    }
}
