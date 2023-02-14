function modalGallery () {
  const carouselItem = document.querySelectorAll('.carousel-item img')
  const windowHeight = window.innerHeight * 0.75
  carouselItem.forEach(function (element) {
    element.style.maxHeight = windowHeight + 'px'
    element.style.width = 'auto'
  })

  window.addEventListener('resize', function () {
    const carouselItem = document.querySelectorAll('.carousel-item img')
    const windowHeight = window.innerHeight * 0.75
    carouselItem.forEach(function (element) {
      element.style.maxHeight = windowHeight + 'px'
      element.style.width = 'auto'
    })
  })
}

if (document.querySelector('.modal-content')) {
  modalGallery()
}

if (document.querySelector('.image-wrapper')) {
  const imageWrap = document.querySelectorAll('.image-wrapper')
  imageWrap.forEach(function (element) {
    const el = element.querySelector('img')
    if (el) {
      el.setAttribute('data-bs-slide-to', element.dataset.count)
    }
  })
}
