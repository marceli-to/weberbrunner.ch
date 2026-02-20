<div>
    <nav class="flex justify-center md:justify-start mb-10 md:mb-15 font-semibold">
        <ul class="flex items-center">
            <li class="flex items-center after:content-['/'] after:ml-3 after:mr-3">
                <button
                    wire:click="setFilter('{{ $location === 'zuerich' ? 'all' : 'zuerich' }}')"
                    class="cursor-pointer decoration-1 underline-offset-4 hover:underline {{ $location === 'zuerich' ? 'underline' : '' }}">
                    Zürich
                </button>
            </li>
            <li>
                <button
                    wire:click="setFilter('{{ $location === 'berlin' ? 'all' : 'berlin' }}')"
                    class="cursor-pointer decoration-1 underline-offset-4 hover:underline {{ $location === 'berlin' ? 'underline' : '' }}">
                    Berlin
                </button>
            </li>
        </ul>
    </nav>

    <div class="md:min-h-(--content-height-md) lg:min-h-(--content-height-lg) border-t md:border-l border-black">
        <div class="md:grid md:grid-cols-2 lg:grid-cols-3">
            @foreach($members as $member)
                @php
                    $total = count($members);
                    $index = $loop->index;
                    $isLast = $loop->last;
                    $lastRowStart2 = $total - ($total % 2 === 0 ? 2 : $total % 2);
                    $lastRowStart3 = $total - ($total % 3 === 0 ? 3 : $total % 3);
                    $isInLastRow2 = $index >= $lastRowStart2;
                    $isInLastRow3 = $index >= $lastRowStart3;
                @endphp
                <div wire:key="member-{{ $member->slug }}"
                    id="{{ $member->slug }}"
                    class="bg-white border-black
                        border-b 
                        md:border-r
                        md:nth-[2n]:border-r-0 
                        lg:nth-[2n]:border-r
                        lg:nth-[3n]:border-r-0
                        {{ $isLast ? 'max-md:border-b-0' : '' }}
                        {{ $isInLastRow2 ? 'md:max-lg:border-b-0' : '' }}
                        {{ $isInLastRow3 ? 'lg:border-b-0' : '' }}">

                    <x-cards.team
                        :image="$member->image?->file"
                        :width="$member->image?->width"
                        :height="$member->image?->height"
                        :firstname="$member->firstname"
                        :name="$member->name"
                        :title="$member->title"
                        :since="$member->since"
                        :email="$member->email"
                        :slug="$member->slug"
                    />
                </div>
            @endforeach
        </div>
    </div>
</div>
