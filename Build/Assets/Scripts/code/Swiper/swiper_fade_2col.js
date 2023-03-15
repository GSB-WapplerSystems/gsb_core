import Swiper from 'swiper/bundle'

if (document.querySelector('.swiper-slide-col-2')) {
  const SwiperFade2Col = function () {
    const customSwiper = document.querySelectorAll('.swiper-fade-col-2')
    let i
    for (i = 0; i < customSwiper.length; i++) {
      customSwiper[i].classList.add('swiper-fade-col-2-' + i)
      /* eslint-disable no-unused-vars */
      const swiperFadeCol2 = new Swiper('.swiper-fade-col-2-' + i, {
        slidesPerView: 1,
        slidesPerGroup: 1,
        spaceBetween: 10,
        watchOverflow: true,
        keyboard: {
          enabled: true
        },
        breakpoints: {
          576: {
            slidesPerView: 2,
            spaceBetween: 20
          }
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
        swiperFadeCol2.autoplay.start()
      })

      swiperStop.addEventListener('click', () => {
        swiperStop.classList.remove('active')
        swiperStart.classList.add('active')
        swiperFadeCol2.autoplay.stop()
      })
    }
  }

  SwiperFade2Col()
}
