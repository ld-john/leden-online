<div>
    {{-- Because she competes with no one, no one can compete with her. --}}

        <form wire:submit.prevent="save">
                @foreach ($posts as $index => $post)
                        <div wire:key="post-field-{{ $post->id }}">
                                <input type="text" wire:model="posts.{{ $index }}.name">
                        </div>
                @endforeach
                <button type="submit">Save</button>
        </form>


</div>
