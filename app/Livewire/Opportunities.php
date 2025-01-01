<?php

namespace App\Livewire;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
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
        // Don't cache search results
        if ($this->search) {
            $items = $this->getItems();
            return view('livewire.opportunities', ['items' => $items]);
        }

        // Only cache default views (first few pages with default sorting)
        if ($this->sortField === 'name' &&
            $this->sortDirection === 'asc' &&
            in_array($this->perPage, [20, 50]) &&
            $this->getPage() <= 5) {

            $cacheKey = "items_page_{$this->getPage()}_per_page_{$this->perPage}";

            $items = Cache::remember($cacheKey, now()->addMinutes(5), function () {
                return $this->getItems();
            });
        } else {
            $items = $this->getItems();
        }

        return view('livewire.opportunities', ['items' => $items]);
    }

    private function getItems(): LengthAwarePaginator
    {
        return Item::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'LIKE', '%' . $this->search . '%');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
    }
}
