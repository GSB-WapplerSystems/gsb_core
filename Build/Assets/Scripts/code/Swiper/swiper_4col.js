import Swiper from 'swiper/bundle'

const language = document.querySelector('html').getAttribute('lang')

let prevSlideMessage
let nextSlideMessage
let firstSlideMessage
let lastSlideMessage
let paginationBulletMessage

if (language === 'de') {
  prevSlideMessage = 'Vorheriger Inhalt'
  nextSlideMessage = 'Nächster Inhalt'
  firstSlideMessage = 'Das ist der erste Inhalt'
  lastSlideMessage = 'Das ist der letzte Inhalt'
  paginationBulletMessage = 'Gehe zu Inhalt {{index}}'
} else {
  prevSlideMessage = 'Previous slide'
  nextSlideMessage = 'Next slide'
  firstSlideMessage = 'This is the first slide'
  lastSlideMessage = 'This is the last slide'
  paginationBulletMessage = 'Go to slide {{index}}'
}

if (document.querySelector('.swiper-slide-col-4')) {
  const Swiper4Col = function () {
    const customSwiper = document.querySelectorAll('.swiper-slide-col-4')
    let i
    for (i = 0; i < customSwiper.length; i++) {
      customSwiper[i].classList.add('swiper-slide-col-4-' + i)
      // eslint-disable-next-line no-new
      new Swiper('.swiper-slide-col-4-' + i, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 10,
        watchOverflow: true,
        pagination: {
          el: '.swiper-pagination',
          clickable: true,
          bulletElement: 'a',
          dynamicBullets: true
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },
        keyboard: {
          enabled: true
        },
        a11y: {
          prevSlideMessage,
          nextSlideMessage,
          firstSlideMessage,
          lastSlideMessage,
          paginationBulletMessage,
          slideLabelMessage: '',
          slideRole: 'listitem'
        },
        breakpoints: {
          576: {
            slidesPerView: 2,
            slidesPerGroup: 2,
            spaceBetween: 20
          },
          992: {
            slidesPerView: 3,
            slidesPerGroup: 4,
            spaceBetween: 30
          },
          1220: {
            slidesPerView: 4,
            slidesPerGroup: 4,
            spaceBetween: 40
          }
        },
        on: {
          afterInit: function () {
            const swiperRole = document.querySelectorAll('.swiper-slide')
            swiperRole.forEach(function (element) {
              element.removeAttribute('role')
            })
          }
        }
      })
    }
  }

  Swiper4Col()
}
