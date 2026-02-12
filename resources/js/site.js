import collapse from '@alpinejs/collapse'
import initSlideshows from './modules/slideshow.js'

// Register Alpine plugins before Livewire starts Alpine
document.addEventListener('alpine:init', () => {
  Alpine.plugin(collapse)
})

initSlideshows()