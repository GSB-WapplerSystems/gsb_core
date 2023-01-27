const language = document.querySelector('html').getAttribute('lang')
let totopText
if (language === 'de') {
  totopText = 'Nach oben'
} else {
  totopText = 'To top'
}

const backToTopButton = ['<div class="totop-wrapper"><div class="container"><a href="#top" class="totop" aria-label="' + totopText + '" title="' + totopText + '">' +
'<div class="ol"></div>' +
'<div class="bg"></div>' +
'</a></div></div>'].join('')

const body = document.querySelector('body')
body.insertAdjacentHTML('beforeend', backToTopButton)
const totop = document.querySelector('.totop')
const footerEl = document.querySelector('footer')

window.onscroll = () => {
  const scrollTop = window.scrollY
  const docHeight = document.body.offsetHeight
  const winHeight = window.innerHeight
  const scrollPercent = scrollTop / (docHeight - winHeight)
  const degrees = scrollPercent * 360
  document.querySelector('.bg').style.background = `#fff conic - gradient(#55438d ${degrees}deg, #fff ${degrees}deg) center center / 60px`
  if (scrollTop > 185) {
    totop.classList.add('on')
  } else {
    totop.classList.remove('on')
  }
  if (scrollTop > 64) {
    body.classList.add('sticky')
  } else {
    body.classList.remove('sticky')
  }
}

totop.addEventListener('click', (e) => {
  window.scroll({
    top: 0,
    left: 0,
    behavior: 'smooth'
  })
  e.preventDefault()
})

const handler = (entries) => {
  if (!entries[0].isIntersecting) {
    totop.classList.remove('enabled')
  } else {
    totop.classList.add('enabled')
  }
}

const observer = new window.IntersectionObserver(handler)
observer.observe(footerEl)
