<?php

namespace App\Livewire\curriculum;

use App\Livewire\Concerns\NeedsPermission;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class curriculum extends Component
{
    use NeedsPermission;

    public Course $course;
    public array $section = ['ar' => '', 'en' => ''];
    public $lesson_section = '';
    public array $lesson = ['ar' => '', 'en' => ''];
    public string $video_url = '';
    public $lesson_minutes = 0;
    public bool $is_preview = false;

    public function mount(Course $course): void
    {
        $this->need('courses.update');
        $this->course = $course;
    }

    protected function validationAttributes(): array
    {
        return [
            'section.ar' => __('admin.fields.section_ar'),
            'section.en' => __('admin.fields.section_en'),
            'lesson_section' => __('admin.fields.section'),
            'lesson.ar' => __('admin.fields.lesson_ar'),
            'lesson.en' => __('admin.fields.lesson_en'),
            'video_url' => __('admin.fields.video_url'),
            'lesson_minutes' => __('admin.fields.duration'),
        ];
    }

    public function addSection(): void
    {
        $this->need('courses.update');
        $this->validate(['section.ar' => 'required|string|max:190', 'section.en' => 'required|string|max:190']);
        $this->course->sections()->create([
            'title' => $this->section,
            'sort_order' => (int) $this->course->sections()->max('sort_order') + 1,
        ]);
        $this->section = ['ar' => '', 'en' => ''];
        $this->dispatch('admin-toast', message: __('admin.saved_ok'), icon: 'success');
    }

    public function addLesson(): void
    {
        $this->need('courses.update');
        $this->validate([
            'lesson_section' => ['required', Rule::exists('course_sections', 'id')->where('course_id', $this->course->id)->whereNull('deleted_at')],
            'lesson.ar' => 'required|string|max:190',
            'lesson.en' => 'required|string|max:190',
            'video_url' => 'nullable|url|starts_with:https://|max:500',
            'lesson_minutes' => 'required|integer|min:0|max:10000',
        ]);
        Lesson::create([
            'course_id' => $this->course->id,
            'course_section_id' => $this->lesson_section,
            'title' => $this->lesson,
            'video_url' => $this->video_url ?: null,
            'duration_minutes' => (int) $this->lesson_minutes,
            'is_preview' => $this->is_preview,
            'sort_order' => (int) Lesson::where('course_section_id', $this->lesson_section)->max('sort_order') + 1,
        ]);
        $this->lesson = ['ar' => '', 'en' => ''];
        $this->video_url = '';
        $this->lesson_minutes = 0;
        $this->is_preview = false;
        $this->dispatch('admin-toast', message: __('admin.saved_ok'), icon: 'success');
    }

    #[On('deleteSectionConfirmed')]
    public function deleteSection($id): void
    {
        $this->need('courses.update');
        $s = $this->course->sections()->findOrFail($id);
        Lesson::where('course_section_id', $s->id)->delete();
        $s->delete();
    }

    #[On('deleteLessonConfirmed')]
    public function deleteLesson($id): void
    {
        $this->need('courses.update');
        Lesson::where('course_id', $this->course->id)->findOrFail($id)->delete();
    }

    public function render()
    {
        $this->need('courses.update');

        return view('livewire.curriculum.curriculum', ['sections' => $this->course->sections()->with('lessons')->get()]);
    }
}
