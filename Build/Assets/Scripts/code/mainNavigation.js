function mainnav () {
  const language = document.querySelector('html').getAttribute('lang')

  let closeTitle
  let openTitle
  let closeNav
  let openNav
  let openButton
  let closeButton

  if (language === 'de') {
    openNav = 'öffnen'
    closeNav = 'schließen'
    openTitle = 'Menü öffnen'
    closeTitle = 'Menü schließen'
    openButton = 'Untermenü öffnen'
    closeButton = 'Untermenü schließen'
  } else {
    openNav = 'Open'
    closeNav = 'Close'
    openTitle = 'Open menu'
    closeTitle = 'Close menu'
    openButton = 'Open Submenu'
    closeButton = 'Close Submenu'
  }

  const Dropdown = document.getElementById('main-menu')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')
  const navbarToggler = document.querySelector('.navbar-toggler')
  const navbarTogglerText = document.querySelector('.navbar-toggler span.txt > .visually-hidden')

  Dropdown.addEventListener('show.bs.dropdown', function () {
    body.classList.add('active-nav-body')
    headerWrapper.classList.add('active-nav')
    navbarToggler.setAttribute('title', closeTitle)
    navbarTogglerText.textContent = closeNav
  })

  Dropdown.addEventListener('hide.bs.dropdown', function () {
    body.classList.remove('active-nav-body')
    headerWrapper.classList.remove('active-nav')
    navbarToggler.setAttribute('title', openTitle)
    navbarTogglerText.textContent = openNav
  })

  const ButtonOpen = document.querySelectorAll('.btn-open')
  ButtonOpen.forEach(function (element) {
    element.addEventListener('click', function () {
      if (element.firstElementChild.textContent === closeButton) {
        element.firstElementChild.textContent = openButton
      } else {
        element.firstElementChild.textContent = closeButton
      }
    })
  })

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
