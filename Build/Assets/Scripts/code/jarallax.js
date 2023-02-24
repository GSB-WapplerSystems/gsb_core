import {jarallax} from 'jarallax'

const parallax = document.querySelector('.grid-parallax')
if (parallax) {
  jarallax(document.querySelectorAll('.grid-parallax'), {
    speed: 0.5,
    imgPosition: '100%'
  })
}
