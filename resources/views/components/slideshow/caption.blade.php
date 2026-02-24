<div class="absolute bottom-10 flex items-end bg-white" x-bind:class="captionOpen ? 'left-10 w-[calc(100%_-_9px)]' : '-right-1'">
	<div x-show="captionOpen" class="text-xs lg:text-sm font-semibold px-5 py-5 flex-1 min-w-0">
		{{ $slot }}
	</div>
	<button @click="captionOpen = !captionOpen" class="bg-white w-30 h-30 flex items-center justify-center shrink-0 cursor-pointer">
		<svg x-bind:class="captionOpen ? 'rotate-0' : 'rotate-180'" class="w-16 h-16 transition-transform duration-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
		</svg>
	</button>
</div>
