<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('phone', 20)->nullable()->unique();
            $t->string('avatar')->nullable();
        });

        Schema::create('roles', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
            $t->string('name');
        });
        Schema::create('permissions', function (Blueprint $t) {
            $t->id();
            $t->string('slug')->unique();
        });
        Schema::create('permission_role', function (Blueprint $t) {
            $t->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->primary(['permission_id', 'role_id']);
        });
        Schema::create('role_user', function (Blueprint $t) {
            $t->foreignId('role_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->primary(['role_id', 'user_id']);
        });

        Schema::create('categories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $t->string('slug')->unique();
            $t->json('name');                       // translatable {ar,en}
            $t->string('icon')->nullable();         // Material Design Icon class
            $t->json('description')->nullable();    // translatable {ar,en}
            $t->unsignedInteger('sort_order')->default(0);
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('courses', function (Blueprint $t) {
            $t->id();
            $t->foreignId('category_id')->constrained()->restrictOnDelete();
            $t->string('slug')->unique();
            $t->json('title');                      // translatable {ar,en}
            $t->json('description');                // translatable {ar,en}
            $t->string('instructor_name');
            $t->string('image')->nullable();
            $t->string('hover_image')->nullable();
            $t->unsignedInteger('price');            // piasters
            $t->unsignedInteger('sale_price')->nullable();
            $t->string('level')->default('beginner');
            $t->unsignedInteger('duration_minutes')->default(0);
            $t->boolean('is_featured')->default(false);
            $t->string('status')->default('draft')->index();
            $t->timestamp('published_at')->nullable();
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('course_sections', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->json('title');
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('lessons', function (Blueprint $t) {
            $t->id();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_section_id')->constrained('course_sections')->cascadeOnDelete();
            $t->json('title');
            $t->string('video_url')->nullable();
            $t->unsignedInteger('duration_minutes')->default(0);
            $t->boolean('is_preview')->default(false);
            $t->unsignedInteger('sort_order')->default(0);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('coupons', function (Blueprint $t) {
            $t->id();
            $t->string('code')->unique();
            $t->string('type')->default('percent');   // percent | fixed (piasters)
            $t->unsignedInteger('value');
            $t->unsignedInteger('min_total')->default(0);
            $t->unsignedInteger('max_uses')->nullable();
            $t->unsignedInteger('used_count')->default(0);
            $t->unsignedInteger('per_user_limit')->default(1);
            $t->timestamp('starts_at')->nullable();
            $t->timestamp('expires_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->string('number')->unique();
            $t->foreignId('user_id')->constrained()->restrictOnDelete();
            $t->unsignedInteger('subtotal');
            $t->unsignedInteger('discount')->default(0);
            $t->unsignedInteger('total');
            $t->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $t->string('coupon_code')->nullable();
            $t->string('status')->default('pending')->index();
            $t->string('payment_method');
            $t->timestamp('paid_at')->nullable();
            $t->timestamps();
        });

        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_id')->constrained()->restrictOnDelete();
            $t->string('title');
            $t->unsignedInteger('price');
            $t->timestamps();
        });

        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $t->string('method');
            $t->unsignedInteger('amount');
            $t->string('status')->default('pending');
            $t->string('proof_path')->nullable();
            $t->string('gateway_ref')->nullable();
            $t->timestamps();
        });

        Schema::create('coupon_usages', function (Blueprint $t) {
            $t->id();
            $t->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
        });

        Schema::create('course_enrollments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('course_id')->constrained()->cascadeOnDelete();
            $t->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $t->timestamps();
            $t->unique(['user_id', 'course_id']);
        });

        Schema::create('lesson_progress', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('lesson_id')->constrained('lessons')->cascadeOnDelete();
            $t->timestamp('completed_at')->nullable();
            $t->timestamps();
            $t->unique(['user_id', 'lesson_id']);
        });

        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action');
            $t->string('subject_type')->nullable();
            $t->unsignedBigInteger('subject_id')->nullable();
            $t->json('meta')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['activity_logs', 'lesson_progress', 'course_enrollments', 'coupon_usages', 'payments', 'order_items', 'orders', 'coupons', 'lessons', 'course_sections', 'courses', 'categories', 'role_user', 'permission_role', 'permissions', 'roles'] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::table('users', function (Blueprint $t) {
            $t->dropUnique(['phone']);
            $t->dropColumn(['phone', 'avatar']);
        });
    }
};
