<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 lg:p-8">
                @if (session()->has('message'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('message') }}
                    </div>
                @endif

                <!-- Back button -->
                <a href="{{ route('my-listings') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-6">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to My Listings
                </a>

                @if(!$isEditing)
                    <!-- View Mode -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div>
                            @if($item->image_path)
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full rounded-lg shadow-lg">
                            @else
                                <div class="w-full h-96 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <h1 class="text-3xl font-bold text-gray-900">{{ $item->name }}</h1>
                                <span class="px-3 py-1 text-sm font-semibold rounded-full 
                                    {{ $item->status === 'available' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </div>
                            
                            <p class="text-gray-600 mb-6">{{ $item->description }}</p>
                            
                            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                                <div class="text-3xl font-bold text-blue-600 mb-2">${{ number_format($item->price, 2) }}</div>
                                <div class="text-gray-600">per day</div>
                            </div>
                            
                            <div class="border-t pt-6">
                                <h3 class="text-lg font-semibold mb-3">Owner Information</h3>
                                <div class="flex items-center">
                                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-medium">{{ $item->user->name }}</div>
                                        <div class="text-sm text-gray-500">Member since {{ $item->user->created_at->format('M Y') }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($isOwner)
                                <div class="mt-6 flex space-x-3">
                                    <button wire:click="toggleEdit" class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded">
                                        Edit Item
                                    </button>
                                </div>
                            @else
                                <div class="mt-6">
                                    @if($item->status === 'available')
                                        <button class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded">
                                            Request to Rent
                                        </button>
                                    @else
                                        <button disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 px-4 rounded cursor-not-allowed">
                                            Currently Unavailable
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Edit Mode -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6">Edit Item</h2>
                        
                        <form wire:submit.prevent="updateItem">
                            <div class="grid grid-cols-1 gap-6">
                                <!-- Item Name -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Item Name</label>
                                    <input type="text" wire:model="name" class="w-full border-gray-300 rounded-md shadow-sm">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Description -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                    <textarea wire:model="description" rows="4" class="w-full border-gray-300 rounded-md shadow-sm"></textarea>
                                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Price -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Price per Day ($)</label>
                                    <input type="number" step="0.01" wire:model="price" class="w-full border-gray-300 rounded-md shadow-sm">
                                    @error('price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Status -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                                    <select wire:model="status" class="w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="available">Available</option>
                                        <option value="rented">Rented</option>
                                        <option value="maintenance">Maintenance</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                
                                <!-- Image Upload -->
                                <div>
                                    <label class="block text-gray-700 text-sm font-bold mb-2">Update Image (Optional)</label>
                                    <input type="file" wire:model="image" class="w-full">
                                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    @if($image)
                                        <div class="mt-2">
                                            <img src="{{ $image->temporaryUrl() }}" class="h-32 object-cover rounded">
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="flex space-x-3">
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                                        Save Changes
                                    </button>
                                    <button type="button" wire:click="toggleEdit" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
