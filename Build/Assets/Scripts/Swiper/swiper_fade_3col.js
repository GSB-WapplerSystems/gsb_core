import Swiper from 'swiper/bundle'

if (document.querySelector('.swiper-fade-col-4')) {
    const SwiperFade3Col = function () {
        const customSwiper = document.querySelectorAll('.swiper-fade-col-4')
        let i
        for (i = 0; i < customSwiper.length; i++) {
            customSwiper[i].classList.add('swiper-fade-col-4-' + i)
            const swiperFadeCol4 = new Swiper('.swiper-fade-col-4-' + i, {
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
                        slidesPerGroup: 2,
                        spaceBetween: 20
                    },
                    992: {
                        slidesPerView: 3,
                        slidesPerGroup: 3,
                        spaceBetween: 30
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
                  swiperFadeCol4.autoplay.start()
            })

            swiperStop.addEventListener('click', () => {
                  swiperStop.classList.remove('active')
                  swiperStart.classList.add('active')
                  swiperFadeCol4.autoplay.stop()
            })
        }
    }

    SwiperFade3Col()
}
