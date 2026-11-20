<?php

namespace App\Livewire;

use App\Models\LessonProgress;
use Livewire\Component;

class MyCourses extends Component
{
    public function render()
    {
        $user = auth()->user();
        $courses = $user->enrolledCourses()->with('category')->withCount('lessons')->get();
        $done = LessonProgress::query()
            ->join('lessons', 'lessons.id', '=', 'lesson_progress.lesson_id')
            ->where('lesson_progress.user_id', $user->id)
            ->groupBy('lessons.course_id')
            ->selectRaw('lessons.course_id as cid, count(*) as c')
            ->pluck('c', 'cid');

        return view('livewire.my-courses', ['courses' => $courses, 'done' => $done]);
    }
}
