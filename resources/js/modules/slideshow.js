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
      spaceBetween: 10,
      navigation: {
        nextEl: '.swiper-btn-next',
        prevEl: '.swiper-btn-prev',
      },
      on: {
        slideChange: function() {

        }
      }
    });
  });
}
