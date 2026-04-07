import Swiper from 'swiper';
import { Navigation } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';

export default function initSlideshows() {
  const slideshowElements = document.querySelectorAll('[data-slideshow]');

  slideshowElements.forEach((element) => {

    const swiper = new Swiper(element, {
      modules: [Navigation],
      slidesPerView: 'auto',
      centeredSlides: false,
      direction: 'horizontal',
      speed: 800,
      spaceBetween: 5,
      navigation: {
        nextEl: element.querySelector('.swiper-btn-next'),
        prevEl: element.querySelector('.swiper-btn-prev'),
      },
      on: {
        init: function() {
          const prevBtn = element.querySelector('.swiper-btn-prev');
          if (prevBtn) {
            prevBtn.style.opacity = this.isBeginning ? '0' : '1';
            prevBtn.style.pointerEvents = this.isBeginning ? 'none' : 'auto';
          }
        },
        slideChange: function() {
          const prevBtn = element.querySelector('.swiper-btn-prev');
          if (prevBtn) {
            prevBtn.style.opacity = this.isBeginning ? '0' : '1';
            prevBtn.style.pointerEvents = this.isBeginning ? 'none' : 'auto';
          }
        },
      },
      breakpoints: {
        768: {
          spaceBetween: 10,
        },
      },
    });
  });
}
