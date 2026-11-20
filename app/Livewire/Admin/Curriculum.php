<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Curriculum extends Component
{
    use NeedsPermission;

    public Course $course;
    public string $s_ar = '';
    public string $s_en = '';
    public $l_section = '';
    public string $l_ar = '';
    public string $l_en = '';
    public string $l_video = '';
    public $l_min = 0;
    public bool $l_preview = false;

    public function mount(Course $course): void
    {
        $this->need('courses.update');
        $this->course = $course;
    }

    public function addSection(): void
    {
        $this->need('courses.update');
        $this->validate(['s_ar' => 'required|max:190', 's_en' => 'required|max:190']);
        $this->course->sections()->create([
            'title_ar' => $this->s_ar,
            'title_en' => $this->s_en,
            'sort_order' => (int) $this->course->sections()->max('sort_order') + 1,
        ]);
        $this->reset('s_ar', 's_en');
    }

    public function addLesson(): void
    {
        $this->need('courses.update');
        $this->validate([
            'l_section' => ['required', Rule::exists('course_sections', 'id')->where('course_id', $this->course->id)],
            'l_ar' => 'required|max:190',
            'l_en' => 'required|max:190',
            'l_video' => 'nullable|url|starts_with:https://|max:500',
            'l_min' => 'required|integer|min:0',
        ]);
        Lesson::create([
            'course_id' => $this->course->id,
            'course_section_id' => $this->l_section,
            'title_ar' => $this->l_ar,
            'title_en' => $this->l_en,
            'video_url' => $this->l_video ?: null,
            'duration_minutes' => $this->l_min,
            'is_preview' => $this->l_preview,
            'sort_order' => (int) Lesson::where('course_section_id', $this->l_section)->max('sort_order') + 1,
        ]);
        $this->reset('l_ar', 'l_en', 'l_video', 'l_min', 'l_preview');
    }

    public function delSection(int $id): void
    {
        $this->need('courses.update');
        $this->course->sections()->whereKey($id)->delete();
    }

    public function delLesson(int $id): void
    {
        $this->need('courses.update');
        Lesson::where('course_id', $this->course->id)->whereKey($id)->delete();
    }

    public function render()
    {
        $this->need('courses.update');

        return view('livewire.admin.curriculum', ['sections' => $this->course->sections()->with('lessons')->get()]);
    }
}
