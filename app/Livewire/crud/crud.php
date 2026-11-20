<?php

namespace App\Livewire\crud;

use Livewire\Attributes\On;
use Livewire\WithPagination;

/**
 * Shared behaviour of every admin index: search, trashed filter, page size,
 * bootstrap pagination, soft delete, restore.
 */
trait crud
{
    use WithPagination;

    public string $search = '';
    public string $trashed = '';
    public int $perPage = 10;
    public bool $showForm = false;
    public ?int $editId = null;

    /** Eloquent model class handled by the component. */
    abstract protected function modelclass(): string;

    /** Permission prefix, e.g. "courses" => courses.update */
    abstract protected function perm(): string;

    public function paginationView()
    {
        return 'livewire::bootstrap';
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingTrashed(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    protected function toast(string $message, string $icon = 'success'): void
    {
        $this->dispatch('admin-toast', message: $message, icon: $icon);
    }

    public function cancel(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    #[On('deleteConfirmed')]
    public function deleteConfirmed($id): void
    {
        $this->need($this->perm().'.delete');
        $this->modelclass()::findOrFail($id)->delete();   // soft delete
    }

    public function restore(int $id): void
    {
        $this->need($this->perm().'.update');
        $this->modelclass()::withTrashed()->findOrFail($id)->restore();
        $this->toast(__('admin.restored_ok'));
    }
}
