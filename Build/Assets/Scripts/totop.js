// only activate javascript on breakpoint lg or higher
// uncomment this code, if we want this feature, current styleguide from bundesregierung.de doesn't have this
// if (window.matchMedia('(min-width: 1024px)').matches) {
//   const totop = document.querySelector('.totop')
//   const footerEl = document.querySelector('footer')
//
//   window.onscroll = () => {
//     const scrollTop = window.scrollY
//     const docHeight = document.body.offsetHeight
//     const winHeight = window.innerHeight
//     const scrollPercent = scrollTop / (docHeight - winHeight)
//     const degrees = scrollPercent * 360
//     document.querySelector('.bg').style.background = `#fff conic-gradient(#63686a ${degrees}deg, #fff ${degrees}deg) center center / 60px`
//     if (scrollTop > 185) {
//       totop.classList.add('on')
//     } else {
//       totop.classList.remove('on')
//     }
//     if (scrollTop > 64) {
//       document.body.classList.add('sticky')
//     } else {
//       document.body.classList.remove('sticky')
//     }
//   }
//
//   totop.addEventListener('click', (e) => {
//     window.scroll({
//       top: 0,
//       left: 0,
//       behavior: 'smooth'
//     })
//     e.preventDefault()
//   })
//
//   const handler = (entries) => {
//     if (!entries[0].isIntersecting) {
//       totop.classList.remove('enabled')
//     } else {
//       totop.classList.add('enabled')
//     }
//   }
//
//   const observer = new window.IntersectionObserver(handler)
//   observer.observe(footerEl)
// }
