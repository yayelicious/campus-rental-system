<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">My Listings</h2>
                    <a href="{{ route('add-item') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        + Add New Item
                    </a>
                </div>

                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif

                @if($items->isEmpty())
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No items listed yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Start sharing resources by adding your first item.</p>
                        <div class="mt-6">
                            <a href="{{ route('add-item') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                Add New Item
                            </a>
                        </div>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($items as $item)
                            <div class="border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <svg class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h3>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                            {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $item->description }}</p>
                                    
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-2xl font-bold text-blue-600">${{ number_format($item->price, 2) }}</span>
                                        <span class="text-sm text-gray-500">per day</span>
                                    </div>
                                    
                                    <div class="text-sm text-gray-500 mb-3">
                                        <span>{{ $item->rentals_count }} rentals</span>
                                    </div>
                                    
                                    <div class="flex space-x-2">
                                        <a href="{{ route('item.view', $item->id) }}" class="flex-1 text-center bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded text-sm">
                                            View Details
                                        </a>
                                        <button wire:click="deleteItem({{ $item->id }})"
                                                wire:confirm="Are you sure you want to delete '{{ $item->name }}'? This action cannot be undone."
                                                class="bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded text-sm">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        {{ $items->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
