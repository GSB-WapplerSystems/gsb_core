const body = document.querySelector('body')
addEventListener('scroll', (event) => {
  const scrollSticky = window.scrollY

  if (scrollSticky > 70) {
    body.classList.add('sticky')
  } else {
    body.classList.remove('sticky')
  }
})
