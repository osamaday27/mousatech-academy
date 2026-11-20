<?php

namespace App\Livewire;

use App\Livewire\Concerns\AddsToCart;
use App\Models\Course;
use Livewire\Component;

class CourseShow extends Component
{
    use AddsToCart;

    public Course $course;

    public function mount(Course $course): void
    {
        $visible = Course::live()->whereKey($course->id)->exists();
        abort_unless($visible || (auth()->check() && auth()->user()->can('courses.update')), 404);
        $this->course = $course;
    }

    public function buyNow()
    {
        $this->add($this->course->id);

        return $this->redirect(route('checkout'));
    }

    public function render()
    {
        $c = $this->course;

        return view('livewire.course-show', [
            'sections' => $c->sections()->with('lessons')->get(),
            'students' => $c->enrollments()->count(),
            'owned' => auth()->check() && auth()->user()->owns($c),
            'related' => Course::live()->with('category')->where('category_id', $c->category_id)
                ->whereKeyNot($c->id)->take(4)->get(),
        ]);
    }
}
