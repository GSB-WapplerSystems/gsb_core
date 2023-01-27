function mainnav () {
  const language = document.querySelector('html').getAttribute('lang')

  let closeTitle
  let openTitle
  let closeNav
  let openNav

  if (language === 'de') {
    openNav = 'öffnen'
    closeNav = 'schließen'
    openTitle = 'Menü öffnen'
    closeTitle = 'Menü schließen'
  } else {
    openNav = 'Open'
    closeNav = 'Close'
    openTitle = 'Open menu'
    closeTitle = 'Close menu'
  }

  const Dropdown = document.getElementById('main-menu')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')
  const navbarToggler = document.querySelector('.navbar-toggler')
  const navbarTogglerText = document.querySelector('.navbar-toggler span.txt > .visually-hidden')

  navbarToggler.setAttribute('title', openNav)
  navbarTogglerText.textContent = openNav

  Dropdown.addEventListener('show.bs.collapse', function () {
    body.classList.add('active-nav-body')
    headerWrapper.classList.add('active-nav')
    navbarToggler.setAttribute('title', closeTitle)
    navbarTogglerText.textContent = closeNav
  })

  Dropdown.addEventListener('hide.bs.collapse', function () {
    body.classList.remove('active-nav-body')
    headerWrapper.classList.remove('active-nav')
    navbarToggler.setAttribute('title', openTitle)
    navbarTogglerText.textContent = openNav
  })

  // functions for showing and hiding html elements

  // Create a media condition that targets viewports at least 991px wide
  const mediaQuery = window.matchMedia('(max-width: 991px)')
  // Check if the media query is true
  if (mediaQuery.matches) {
    const getSiblings = function (e) {
      const siblings = []
      if (!e.parentNode) {
        return siblings
      }
      let sibling = e.parentNode.firstChild
      while (sibling) {
        if (sibling.nodeType === 1 && sibling !== e) {
          siblings.push(sibling)
        }
        sibling = sibling.nextSibling
      }
      return siblings
    }

    const more = document.querySelectorAll('.readmore')

    more.forEach(e => {
      e.addEventListener('click', function () {
        showElements(e)
        this.classList.toggle('open')
      })
    })

    function showElements (target) {
      const sib = getSiblings(target)
      let less = true

      sib.forEach(s => {
        if (s.classList.contains('d-none')) {
          less = false
        }
      })

      if (!less) {
        sib.forEach(s => {
          if (s.classList.contains('d-none')) {
            s.classList.remove('d-none')
            s.classList.add('will-hidden')
            target.innerHTML = '<span>weniger anzeigen</span>'
          }
        })
      } else {
        sib.forEach(s => {
          if (s.classList.contains('will-hidden')) {
            s.classList.remove('will-hidden')
            s.classList.add('d-none')
            target.innerHTML = '<span>mehr anzeigen</span>'
          }
        })
      }
    }
  }
}

if (document.querySelector('#main-menu')) {
  mainnav()
}
