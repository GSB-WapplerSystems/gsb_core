document.querySelector('html').classList.remove('no-js')
document.querySelector('html').classList.add('js')

function wrap (el, wrapper) {
  el.parentNode.insertBefore(wrapper, el)
  wrapper.appendChild(el)
  wrapper.className = 'table-responsive'
}

if (document.querySelector('.table')) {
  const tableWrap = document.querySelectorAll('.table')
  tableWrap.forEach(function (element) {
    wrap(element, document.createElement('div'))
  })
}

const popupWindow = document.querySelectorAll('.popup-window')
Array.prototype.forEach.call(popupWindow, function (el) {
  el.onclick = function (e) {
    e.preventDefault()
    window.open(el.attributes.href.value, '', 'width=600,height=600')
  }
})

if (document.querySelector('.is-invalid')) {
  const IsInvalid = document.querySelector('.is-invalid')
  IsInvalid.focus()
}
