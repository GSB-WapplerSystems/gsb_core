function mainNavigation () {
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
}

if (document.querySelector('#main-menu')) {
  mainNavigation()
}
