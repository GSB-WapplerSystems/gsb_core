// scrollIntoView and open for tab elements chosen by url hash
function scrollIntoViewTab () {
  if (window.location.hash && document.querySelector('.nav-tabs')) {
    const idArr = window.location.hash
    const anchor = document.querySelector(idArr)
    const idArrSplit = window.location.hash.split('-')
    const anchorSplitResult = idArrSplit[0]
    if (anchorSplitResult === '#tab') {
      anchor.scrollIntoView({
        behavior: 'smooth'
      })
      const someTabTriggerEl = document.querySelector(idArr)
      // eslint-disable-next-line no-undef
      const tab = new bootstrap.Tab(someTabTriggerEl)
      tab.show()
    }
  }
}

// scrollIntoView and open for accordion elements chosen by url hash
function scrollIntoViewAccordion () {
  if (window.location.hash && document.querySelector('.accordion')) {
    const idArr = window.location.hash.split('#')
    const anchor = document.getElementById(idArr[1])
    const idArrSplit = window.location.hash.split('-')
    const accordionSplitResult = idArrSplit[0]
    const anchorParent = anchor.parentElement
    anchorParent.setAttribute('tabindex', '-1')
    anchorParent.focus()
    if (accordionSplitResult === '#content') {
      anchorParent.scrollIntoView({
        behavior: 'smooth'
      })
      anchorParent.removeAttribute('tabindex')
      const myCollapse = document.getElementById(idArr[1])
      // eslint-disable-next-line no-undef
      const bsCollapse = new bootstrap.Collapse(myCollapse, {
        toggle: true
      })
      bsCollapse.show()
    }
  }
}

window.addEventListener('load', (event) => {
  scrollIntoViewTab()
  scrollIntoViewAccordion()
})
