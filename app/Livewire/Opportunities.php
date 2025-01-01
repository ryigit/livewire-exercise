<?php

namespace App\Livewire;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Item;

class Opportunities extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 20;
    public array $options = [20, 50, 100, 250];
    public string $search = '';
    public string $sortField = 'name';
    public string $sortDirection = 'asc';

    protected array $queryString = [
        'perPage' => ['except' => 20],
        'search' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc']
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function render(): View|Factory|Application
    {
        $items = Item::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.opportunities', [
            'items' => $items
        ]);
    }
}
