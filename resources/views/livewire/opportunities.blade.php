<div>
    <div class="container mx-auto p-4">
        <!-- Search and Filters Section -->
        <div class="mb-4 flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
            <!-- Search Input -->
            <div class="flex-1">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search items..."
                    class="w-full p-2 border rounded shadow-sm"
                >
            </div>

            <!-- Items per page selection -->
            <div class="flex items-center space-x-2">
                <label for="perPage">Items per page:</label>
                <select
                    wire:model.live="perPage"
                    id="perPage"
                    class="p-2 border rounded shadow-sm"
                >
                    @foreach($options as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-6 py-3 border-b">
                        <button
                            wire:click="sortBy('name')"
                            class="flex items-center space-x-1 font-medium"
                        >
                            <span>Name</span>
                            @if($sortField === 'name')
                            <span>
                                        @if($sortDirection === 'asc')
                                            &uarr;
                                        @else
                                            &darr;
                                        @endif
                                    </span>
                            @endif
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 border-b">{{ $item->name }}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $items->links() }}
        </div>
    </div>

    <!-- Loading State -->
    <div wire:loading class="fixed top-0 right-0 m-4">
        <div class="bg-blue-500 text-white px-4 py-2 rounded shadow">
            Loading...
        </div>
    </div>
</div>
