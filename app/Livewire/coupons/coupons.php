<?php

namespace App\Livewire\coupons;

use App\Livewire\Concerns\NeedsPermission;
use App\Livewire\crud\crud;
use App\Models\Coupon;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class coupons extends Component
{
    use crud, NeedsPermission;

    public string $status = '';
    public string $code = '';
    public string $type = 'percent';
    public $value = 10;
    public $min_total = 0;
    public $max_uses = null;
    public $per_user_limit = 1;
    public ?string $starts_at = null;
    public ?string $expires_at = null;
    public bool $is_active = true;

    protected function modelclass(): string
    {
        return Coupon::class;
    }

    protected function perm(): string
    {
        return 'coupons';
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:40', 'regex:/^[A-Za-z0-9_-]+$/', Rule::unique('coupons', 'code')->ignore($this->editId)],
            'type' => 'required|in:percent,fixed',
            'value' => $this->type === 'percent' ? 'required|integer|min:1|max:100' : 'required|numeric|min:1|max:1000000',
            'min_total' => 'required|numeric|min:0|max:1000000',
            'max_uses' => 'nullable|integer|min:1|max:1000000',
            'per_user_limit' => 'required|integer|min:1|max:1000',
            'starts_at' => 'nullable|date',
            'expires_at' => array_filter(['nullable', 'date', $this->starts_at ? 'after:starts_at' : null]),
            'is_active' => 'boolean',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'code' => __('admin.fields.code'),
            'type' => __('admin.fields.type'),
            'value' => __('admin.fields.value'),
            'min_total' => __('admin.fields.min_total'),
            'max_uses' => __('admin.fields.max_uses'),
            'per_user_limit' => __('admin.fields.per_user'),
            'starts_at' => __('admin.fields.starts_at'),
            'expires_at' => __('admin.fields.expires_at'),
            'is_active' => __('admin.fields.status'),
        ];
    }

    protected function resetForm(): void
    {
        $this->editId = null;
        $this->code = '';
        $this->type = 'percent';
        $this->value = 10;
        $this->min_total = 0;
        $this->max_uses = null;
        $this->per_user_limit = 1;
        $this->starts_at = null;
        $this->expires_at = null;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function create(): void
    {
        $this->need('coupons.create');
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->need('coupons.update');
        $c = Coupon::findOrFail($id);
        $this->resetForm();
        $this->editId = $c->id;
        $this->code = $c->code;
        $this->type = $c->type;
        $this->value = $c->type === 'fixed' ? $c->value / 100 : $c->value;
        $this->min_total = $c->min_total / 100;
        $this->max_uses = $c->max_uses;
        $this->per_user_limit = $c->per_user_limit;
        $this->starts_at = $c->starts_at?->format('Y-m-d\TH:i');
        $this->expires_at = $c->expires_at?->format('Y-m-d\TH:i');
        $this->is_active = $c->is_active;
        $this->showForm = true;
    }

    public function save(): void
    {
        $this->need($this->editId ? 'coupons.update' : 'coupons.create');
        $d = $this->validate();

        $c = $this->editId ? Coupon::findOrFail($this->editId) : new Coupon();
        $c->fill([
            'code' => strtoupper($d['code']),
            'type' => $d['type'],
            'value' => $d['type'] === 'fixed' ? (int) round($d['value'] * 100) : (int) $d['value'],
            'min_total' => (int) round($d['min_total'] * 100),
            'max_uses' => ($d['max_uses'] ?? '') === '' ? null : (int) $d['max_uses'],
            'per_user_limit' => (int) $d['per_user_limit'],
            'starts_at' => $d['starts_at'] ?: null,
            'expires_at' => $d['expires_at'] ?: null,
            'is_active' => (bool) $d['is_active'],
        ])->save();

        $this->showForm = false;
        $this->resetForm();
        $this->toast(__('admin.saved_ok'));
    }

    public function toggleStatus(int $id): void
    {
        $this->need('coupons.update');
        $c = Coupon::findOrFail($id);
        $c->update(['is_active' => ! $c->is_active]);
        $this->toast(__('admin.status_changed'));
    }

    public function render()
    {
        $s = $this->search;
        $rows = Coupon::query()
            ->when($this->trashed === 'only', fn ($q) => $q->onlyTrashed())
            ->when($s !== '', fn ($q) => $q->where('code', 'like', "%{$s}%"))
            ->when($this->status !== '', fn ($q) => $q->where('is_active', $this->status === 'active'))
            ->latest('id')->paginate($this->perPage);

        return view('livewire.coupons.coupons', ['rows' => $rows]);
    }
}
