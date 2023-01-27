function personWidth () {
  const personWidth = document.querySelector('.person-wrapper').offsetWidth
  const personItem = document.querySelectorAll('.person-item > .full-collapse')
  personItem.forEach(function (element) {
    element.style.width = personWidth + 'px'
  })
}

function personRect () {
  const personRect = document.querySelector('.person-wrapper').getBoundingClientRect()
  const personRectLeft = personRect.left
  const personItemBtn = document.querySelectorAll('.person-item > .btn')
  personItemBtn.forEach(function (element) {
    const personItemBtnRect = element.getBoundingClientRect()
    const personItemBtnRectLeft = personItemBtnRect.left - personRectLeft
    const personItemMargin = element.nextElementSibling
    personItemMargin.style.marginLeft = '-' + personItemBtnRectLeft + 'px'
  })
}

if (document.querySelector('.person-wrapper')) {
  personWidth()
  personRect()

  window.addEventListener('resize', function () {
    personWidth()
    personRect()
  })
}
