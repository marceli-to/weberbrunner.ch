import Swiper from 'swiper';
import 'swiper/css';

const MIN_STEP_RATIO = 0.1;

function updatePrevButton(swiper, prevBtn) {
	if (!prevBtn) return;
	prevBtn.style.opacity = swiper.isBeginning ? '0' : '1';
	prevBtn.style.pointerEvents = swiper.isBeginning ? 'none' : 'auto';
}

// With centered slides clamped to the edges, neighbouring snaps can be only a few px apart
// (e.g. the info column followed by the first image), so skip steps that barely move.
function step(swiper, direction) {
	const current = -swiper.translate;
	const minStep = swiper.width * MIN_STEP_RATIO;

	const stops = [];
	swiper.snapGrid.forEach((snap, index) => {
		if (!stops.length || snap - stops[stops.length - 1].snap > minStep) {
			stops.push({ snap, index });
		}
	});

	const target = direction > 0
		? stops.find((stop) => stop.snap > current + minStep) ?? stops[stops.length - 1]
		: stops.findLast((stop) => stop.snap < current - minStep) ?? stops[0];

	swiper.slideTo(target.index);
}

export default function initSlideshows() {
	const slideshowElements = document.querySelectorAll('[data-slideshow]');

	slideshowElements.forEach((element) => {
		const prevBtn = element.querySelector('.swiper-btn-prev');
		const nextBtn = element.querySelector('.swiper-btn-next');

		const swiper = new Swiper(element, {
			slidesPerView: 'auto',
			centeredSlides: true,
			centeredSlidesBounds: true,
			direction: 'horizontal',
			speed: 800,
			spaceBetween: 5,
			on: {
				init: function() {
					updatePrevButton(this, prevBtn);
				},
				slideChange: function() {
					updatePrevButton(this, prevBtn);
				},
				// Swiper re-snaps to activeIndex after measuring, but at translate 0 it reports the
				// centered slide (the first image) as active, which would shift off the start on load.
				slidesUpdated: function() {
					if (this.translate === 0 && this.activeIndex !== 0) {
						this.updateActiveIndex(0);
					}
				},
			},
			breakpoints: {
				768: {
					spaceBetween: 10,
				},
			},
		});

		prevBtn?.addEventListener('click', () => step(swiper, -1));
		nextBtn?.addEventListener('click', () => step(swiper, 1));
	});
}
