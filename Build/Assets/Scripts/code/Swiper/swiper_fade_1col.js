import Swiper from 'swiper/bundle'

if (document.querySelector('.swiper-fade-col-1')) {
  const SwiperFade1Col = function () {
    const customSwiper = document.querySelectorAll('.swiper-fade-col-1')
    let i
    for (i = 0; i < customSwiper.length; i++) {
      customSwiper[i].classList.add('swiper-fade-col-1-' + i)
      /* eslint-disable no-unused-vars */
      const swiperFadeCol12 = new Swiper('.swiper-fade-col-1-' + i, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 0,
        watchOverflow: true,
        effect: 'fade',
        speed: 2000,
        autoplay: {
          delay: 5000,
          disableOnInteraction: true
        },
        keyboard: {
          enabled: true
        },
        a11y: {
          slideLabelMessage: '',
          slideRole: 'listitem'
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

      const swiperStart = document.querySelector('.swiper-start')
      const swiperStop = document.querySelector('.swiper-stop')

      swiperStart.addEventListener('click', () => {
        swiperStart.classList.remove('active')
        swiperStop.classList.add('active')
        swiperFadeCol12.autoplay.start()
      })

      swiperStop.addEventListener('click', () => {
        swiperStop.classList.remove('active')
        swiperStart.classList.add('active')
        swiperFadeCol12.autoplay.stop()
      })
    }
  }

  SwiperFade1Col()
}
