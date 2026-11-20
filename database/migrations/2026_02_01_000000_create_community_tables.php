<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            $t->string('bio', 500)->nullable();
        });

        Schema::create('posts', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->text('body');
            $t->string('image')->nullable();
            $t->timestamps();
            $t->softDeletes();
            $t->index('created_at');
        });

        Schema::create('comments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('post_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->string('body', 1000);
            $t->timestamps();
            $t->softDeletes();
        });

        Schema::create('post_likes', function (Blueprint $t) {
            $t->id();
            $t->foreignId('post_id')->constrained()->cascadeOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->timestamps();
            $t->unique(['post_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_likes');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('posts');
        Schema::table('users', function (Blueprint $t) {
            $t->dropColumn('bio');
        });
    }
};
