// scrollIntoView and open for tab elements chosen by url hash
function scrollIntoViewTab () {
  if (window.location.hash && document.querySelector('.nav-tabs')) {
    const idArr = window.location.hash
    const anchor = document.querySelector(idArr)
    const idArrSplit = window.location.hash.split('-')
    const anchorSplitResult = idArrSplit[0]
    if (anchorSplitResult === '#tab') {
      anchor.classList.add('spacer')
      anchor.scrollIntoView({
        behavior: 'smooth'
      })
      anchor.classList.remove('spacer')
      const someTabTriggerEl = document.querySelector(idArr)
      // eslint-disable-next-line no-undef
      const tab = new bootstrap.Tab(someTabTriggerEl)
      tab.show()
    }
    history.pushState(null, null, ' ')
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
    if (accordionSplitResult === '#accordion') {
      anchorParent.classList.add('spacer')
      anchorParent.scrollIntoView({
        behavior: 'smooth'
      })
      anchorParent.classList.remove('spacer')
      anchorParent.removeAttribute('tabindex')
      const myCollapse = document.getElementById(idArr[1])
      // eslint-disable-next-line no-undef,no-new
      new bootstrap.Collapse(myCollapse, {
        toggle: true
      })
    }
  }
}

window.onload = function () {
  scrollIntoViewTab()
  scrollIntoViewAccordion()
}

// scrollIntoView for tabs by hashchange from mainnav links
document.addEventListener('click', function (event) {
  if (document.querySelector('.hash-nav')) {
    window.addEventListener('hashchange', function (e) {
      scrollIntoViewTab()
      window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
      })
    })
  }
  history.pushState(null, null, ' ')
}, false)

// scrollIntoView for all anchor links by hash and click
function scrollIntoViewAnchors () {
  const links = document.querySelectorAll('.ce-bodytext [href^="#"], .video-body-wrapper [href^="#"], .focusteaser-item [href^="#"], .footnote-link[href^="#"], .footnote-item [href^="#"]')
  for (const link of links) {
    link.addEventListener('click', clickHandler)
  }

  function clickHandler (e) {
    e.preventDefault()
    const href = this.getAttribute('href').split('#')
    const hrefId = document.getElementById(href[1])
    hrefId.setAttribute('tabindex', '-1')
    hrefId.focus()
    hrefId.classList.add('spacer')
    hrefId.scrollIntoView({
      behavior: 'smooth'
    })
    hrefId.classList.remove('spacer')
    hrefId.removeAttribute('tabindex')
  }
}

scrollIntoViewAnchors()
