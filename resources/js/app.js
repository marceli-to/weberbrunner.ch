import collapse from '@alpinejs/collapse'
import initSlideshows from './modules/slideshow.js'
import masonry from './modules/masonry.js'

// Register Alpine plugins before Livewire starts Alpine
document.addEventListener('alpine:init', () => {
  Alpine.plugin(collapse)
  Alpine.data('masonry', masonry)
})

initSlideshows()