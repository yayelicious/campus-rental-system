<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-8 md:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Message -->
        @if (session()->has('message'))
            <div class="mb-6 p-4 md:p-5 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-xl shadow-sm">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('message') }}
                </div>
            </div>
        @endif

        <!-- Back Button -->
        <a href="{{ route('my-listings') }}" class="inline-flex items-center mb-6 text-blue-600 hover:text-blue-700 font-medium transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to My Listings
        </a>

        @if(!$isEditing)
            <!-- View Mode -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 p-6 md:p-8 lg:p-10">
                    <!-- Image Section -->
                    <div class="flex flex-col">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-full h-auto object-cover rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
                        @else
                            <div class="w-full h-72 md:h-96 bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
                                <svg class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Details Section -->
                    <div class="flex flex-col justify-between">
                        <!-- Header -->
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-6">
                                <div>
                                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">{{ $item->name }}</h1>
                                    <p class="text-gray-600 text-sm">Listed by {{ $item->user->name }}</p>
                                </div>
                                <span class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-full 
                                    @if($item->status === 'available')
                                        bg-green-100 text-green-800
                                    @elseif($item->status === 'rented')
                                        bg-orange-100 text-orange-800
                                    @else
                                        bg-gray-100 text-gray-800
                                    @endif
                                    w-fit">
                                    <span class="flex items-center">
                                        <span class="w-2 h-2 rounded-full mr-2 @if($item->status === 'available') bg-green-500 @elseif($item->status === 'rented') bg-orange-500 @else bg-gray-500 @endif"></span>
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </span>
                            </div>

                            <!-- Description -->
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">About</h3>
                                <p class="text-gray-700 text-base leading-relaxed">{{ $item->description }}</p>
                            </div>

                            <!-- Price Card -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 mb-6">
                                <span class="text-gray-600 text-sm font-medium">Rental Price</span>
                                <div class="flex items-baseline gap-2 mt-2">
                                    <span class="text-4xl font-bold text-blue-600">₱{{ number_format($item->price, 2) }}</span>
                                    <span class="text-gray-600 font-medium">per day</span>
                                </div>
                            </div>

                            <!-- Owner Info -->
                            <div class="border-t border-gray-200 pt-6">
                                <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-4">Owner</h3>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 12a3 3 0 100-6 3 3 0 000 6z"></path>
                                            <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm0 2a8 8 0 100 16 8 8 0 000-16z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->user->name }}</p>
                                        <p class="text-sm text-gray-500">Member since {{ $item->user->created_at->format('M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 space-y-3">
                            @if($isOwner)
                                <button wire:click="toggleEdit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Item
                                </button>
                            @else
                                @if($item->status === 'available')
                                    <button class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Request to Rent
                                    </button>
                                @else
                                    <button disabled class="w-full bg-gray-300 text-gray-500 font-semibold py-3 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Currently Unavailable
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Edit Mode -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden p-6 md:p-8 lg:p-10">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">Edit Item</h2>

                <form wire:submit.prevent="updateItem" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Item Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Item Name <span class="text-red-500">*</span></label>
                            <input 
                                type="text" 
                                wire:model="name" 
                                placeholder="Enter item name"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                            >
                            @error('name') 
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586 5.414 11.01a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l8.08-8.08z" clip-rule="evenodd"/></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Description <span class="text-red-500">*</span></label>
                            <textarea 
                                wire:model="description" 
                                rows="4" 
                                placeholder="Describe your item in detail..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                            ></textarea>
                            @error('description') 
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586 5.414 11.01a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l8.08-8.08z" clip-rule="evenodd"/></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Price per Day <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-semibold">₱</span>
                                <input 
                                    type="number" 
                                    step="0.01"
                                    min="0"
                                    wire:model="price" 
                                    placeholder="0.00"
                                    class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                >
                            </div>
                            @error('price') 
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586 5.414 11.01a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l8.08-8.08z" clip-rule="evenodd"/></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Status <span class="text-red-500">*</span></label>
                            <select 
                                wire:model="status"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all bg-white"
                            >
                                <option value="available">Available</option>
                                <option value="rented">Rented</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                            @error('status') 
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586 5.414 11.01a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l8.08-8.08z" clip-rule="evenodd"/></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">Update Image (Optional)</label>
                            <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 hover:border-blue-500 transition-colors">
                                <input 
                                    type="file" 
                                    wire:model="image"
                                    accept="image/*"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                >
                                <div class="text-center">
                                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <p class="text-gray-600 font-medium">Click or drag image here</p>
                                    <p class="text-gray-500 text-sm">PNG, JPG, GIF up to 1MB</p>
                                </div>
                            </div>
                            @error('image') 
                                <p class="text-red-500 text-sm mt-2 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18.101 12.93a1 1 0 00-1.414-1.414L10 15.586 5.414 11.01a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0l8.08-8.08z" clip-rule="evenodd"/></path></svg>
                                    {{ $message }}
                                </p>
                            @enderror

                            <!-- Image Preview -->
                            @if($image)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-3">Preview</p>
                                    <img src="{{ $image->temporaryUrl() }}" class="h-48 w-full object-cover rounded-lg shadow-md">
                                </div>
                            @elseif($item->image_path)
                                <div class="mt-4">
                                    <p class="text-sm font-medium text-gray-700 mb-3">Current Image</p>
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="h-48 w-full object-cover rounded-lg shadow-md">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <button 
                            type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Changes
                        </button>
                        <button 
                            type="button" 
                            wire:click="toggleEdit"
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>
