<div>
    @if (session()->has('message'))
        <div>{{ session('message') }}</div>
    @endif

    <form wire:submit.prevent="update">

        <input type="text" wire:model="name">

        <textarea wire:model="description"></textarea>

        <input type="number" wire:model="price">

        <select wire:model="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select wire:model="status">
            <option value="available">Available</option>
            <option value="rented">Rented</option>
        </select>

        <input type="file" wire:model="image">

        <button type="submit">Update</button>
    </form>
</div>
