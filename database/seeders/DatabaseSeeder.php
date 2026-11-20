<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $perms = collect(['dashboard.view', 'courses.create', 'courses.update', 'courses.delete', 'orders.view', 'orders.update', 'payments.verify', 'users.manage', 'posts.moderate', 'reports.view', 'settings.update'])
            ->map(fn ($s) => Permission::firstOrCreate(['slug' => $s]));
        $super = Role::firstOrCreate(['slug' => 'super-admin'], ['name' => 'Super Admin']);
        $mod = Role::firstOrCreate(['slug' => 'moderator'], ['name' => 'Moderator']);
        $super->permissions()->syncWithoutDetaching($perms->pluck('id'));
        $mod->permissions()->syncWithoutDetaching($perms->whereIn('slug', ['dashboard.view', 'posts.moderate', 'reports.view'])->pluck('id'));

        User::firstOrCreate(['email' => 'admin@mousatech.test'], ['name' => 'Super Admin', 'phone' => '01000000001', 'password' => 'ChangeMe#2026'])
            ->roles()->syncWithoutDetaching([$super->id]);
        User::firstOrCreate(['email' => 'mod@mousatech.test'], ['name' => 'Moderator', 'phone' => '01000000002', 'password' => 'ChangeMe#2026'])
            ->roles()->syncWithoutDetaching([$mod->id]);

        $cats = [
            ['web-development', 'تطوير الويب', 'Web Development'],
            ['backend-development', 'تطوير الخلفية', 'Backend Development'],
            ['cyber-security', 'الأمن السيبراني', 'Cyber Security'],
            ['networking-it', 'الشبكات والدعم الفني', 'Networking & IT'],
            ['cloud-computing', 'الحوسبة السحابية', 'Cloud Computing'],
            ['ai-automation', 'الذكاء الاصطناعي والأتمتة', 'AI & Automation'],
        ];
        foreach ($cats as $i => [$slug, $ar, $en]) {
            Category::firstOrCreate(['slug' => $slug], ['name_ar' => $ar, 'name_en' => $en, 'sort_order' => $i, 'is_active' => true]);
        }

        // slug, category, title_ar, title_en, instructor, price EGP, sale EGP|null, level, minutes, featured
        $courses = [
            ['laravel-backend', 'backend-development', 'تطوير الخلفية بـ Laravel', 'Laravel Backend Development', 'Mousa', 1000, 600, 'intermediate', 1260, true],
            ['html-css-js', 'web-development', 'أساسيات الويب: HTML وCSS وJavaScript', 'Web Fundamentals: HTML, CSS & JavaScript', 'Mousa', 500, null, 'beginner', 840, true],
            ['react-next', 'web-development', 'React وNext.js من الصفر', 'React & Next.js from Scratch', 'Mousa', 900, 700, 'intermediate', 1100, false],
            ['security-basics', 'cyber-security', 'أساسيات الأمن السيبراني', 'Security Fundamentals', 'Mousa', 800, null, 'beginner', 700, true],
            ['aws-cloud', 'cloud-computing', 'مدخل إلى AWS', 'Introduction to AWS', 'Mousa', 750, 500, 'beginner', 600, false],
            ['python-automation', 'ai-automation', 'أتمتة المهام بلغة Python', 'Python Automation', 'Mousa', 650, null, 'beginner', 540, false],
        ];
        foreach ($courses as $n => [$slug, $cat, $ar, $en, $ins, $price, $sale, $lvl, $min, $feat]) {
            $c = Course::firstOrCreate(['slug' => $slug], [
                'category_id' => Category::where('slug', $cat)->value('id'),
                'title_ar' => $ar, 'title_en' => $en,
                'description_ar' => 'دورة عملية تعتمد على بناء مشاريع حقيقية خطوة بخطوة، مع ملفات وأكواد جاهزة للتطبيق.',
                'description_en' => 'A hands-on course built around real projects, step by step, with source files you can reuse.',
                'instructor_name' => $ins, 'price' => $price * 100, 'sale_price' => $sale ? $sale * 100 : null,
                'level' => $lvl, 'duration_minutes' => $min, 'is_featured' => $feat,
                'status' => 'published', 'published_at' => now()->subDays(30 - $n * 6),
            ]);
            if ($c->sections()->doesntExist()) {
                $s1 = $c->sections()->create(['title_ar' => 'البداية', 'title_en' => 'Getting started', 'sort_order' => 1]);
                $s2 = $c->sections()->create(['title_ar' => 'التطبيق العملي', 'title_en' => 'Hands-on practice', 'sort_order' => 2]);
                $lessons = [[$s1, 'مقدمة الكورس', 'Course introduction', true], [$s1, 'تجهيز بيئة العمل', 'Setting up your environment', false], [$s2, 'المشروع الأول', 'Your first project', false], [$s2, 'المراجعة والخطوات التالية', 'Review and next steps', false]];
                foreach ($lessons as $k => [$s, $lar, $len, $prev]) {
                    $c->lessons()->create(['course_section_id' => $s->id, 'title_ar' => $lar, 'title_en' => $len, 'is_preview' => $prev, 'duration_minutes' => 15, 'sort_order' => $k + 1]);
                }
            }
        }

        Coupon::firstOrCreate(['code' => 'MOUSA40'], ['type' => 'percent', 'value' => 40, 'max_uses' => 100, 'per_user_limit' => 1, 'is_active' => true]);
    }
}
