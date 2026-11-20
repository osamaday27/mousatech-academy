<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Livewire\Component;

class Player extends Component
{
    public Course $course;
    public ?int $lessonId = null;

    public function mount(Course $course, $lesson = null): void
    {
        $this->course = $course;
        $all = $this->allLessons();
        if (! $this->owned()) {
            $target = $all->firstWhere('id', (int) $lesson);
            abort_unless($target && $target->is_preview, 403);
        }
        $done = LessonProgress::where('user_id', auth()->id())->pluck('lesson_id')->all();
        $this->lessonId = $lesson
            ? (int) $lesson
            : ($all->first(fn ($l) => ! in_array($l->id, $done, true))?->id ?? $all->first()?->id);
    }

    protected function owned(): bool
    {
        $u = auth()->user();

        return $u->owns($this->course) || $u->can('courses.update');
    }

    protected function allLessons()
    {
        return $this->course->sections()->with('lessons')->get()->flatMap(fn ($s) => $s->lessons)->values();
    }

    public function select(int $id): void
    {
        $l = Lesson::where('course_id', $this->course->id)->findOrFail($id);
        abort_unless($this->owned() || $l->is_preview, 403);
        $this->lessonId = $l->id;
    }

    public function toggle(int $id): void
    {
        abort_unless($this->owned(), 403);
        $l = Lesson::where('course_id', $this->course->id)->findOrFail($id);
        $p = LessonProgress::firstOrNew(['user_id' => auth()->id(), 'lesson_id' => $l->id]);
        if ($p->exists) {
            $p->delete();
        } else {
            $p->completed_at = now();
            $p->save();
        }
    }

    public function render()
    {
        $sections = $this->course->sections()->with('lessons')->get();
        $all = $sections->flatMap(fn ($s) => $s->lessons)->values();
        $owned = $this->owned();
        $cur = $all->firstWhere('id', $this->lessonId);
        abort_if($cur && ! $owned && ! $cur->is_preview, 403);
        $done = LessonProgress::where('user_id', auth()->id())->whereIn('lesson_id', $all->pluck('id'))->pluck('lesson_id')->all();

        return view('livewire.player', [
            'sections' => $sections,
            'cur' => $cur,
            'owned' => $owned,
            'done' => $done,
            'percent' => $all->count() ? (int) round(count($done) / $all->count() * 100) : 0,
        ]);
    }
}
