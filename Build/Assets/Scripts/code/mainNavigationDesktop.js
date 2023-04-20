function mainnavDesktop () {
  const language = document.querySelector('html').getAttribute('lang')

  let closeNav
  let openNav

  if (language === 'de') {
    openNav = 'öffnen'
    closeNav = 'schließen'
  } else {
    openNav = 'Open'
    closeNav = 'Close'
  }

  const Dropdown = document.getElementById('main-menu-desktop')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')
  const navbarToggler = document.querySelector('.first-child')
  const navbarTogglerText = document.querySelector('.first-child span.txt > .visually-hidden')

  Dropdown.addEventListener('show.bs.dropdown', function () {
    body.classList.add('active-nav-body')
    headerWrapper.classList.add('active-nav')
    navbarToggler.setAttribute('title', closeNav)
    navbarTogglerText.textContent = closeNav
  })

  Dropdown.addEventListener('hide.bs.dropdown', function () {
    body.classList.remove('active-nav-body')
    headerWrapper.classList.remove('active-nav')
    navbarToggler.setAttribute('title', openNav)
    navbarTogglerText.textContent = openNav
  })

  const FirstChild = document.querySelectorAll('.first-child')
  FirstChild.forEach(function (element) {
    element.addEventListener('click', function () {
      body.classList.add('active-nav-body')
      headerWrapper.classList.add('active-nav')
      navbarToggler.setAttribute('title', closeNav)
      navbarTogglerText.textContent = closeNav
    })
  })
}

if (document.querySelector('#main-menu')) {
  mainnavDesktop()
}
