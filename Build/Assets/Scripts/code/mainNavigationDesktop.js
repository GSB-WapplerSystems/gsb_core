function mainNavigationDesktop () {
  const MainNavDesktopItem = document.querySelectorAll('.mainnav-desktop-item')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')

  MainNavDesktopItem.forEach(function (element) {
    element.addEventListener('hide.bs.dropdown', function () {
      body.classList.remove('active-nav-body')
      headerWrapper.classList.remove('active-nav')
    })

    element.addEventListener('show.bs.dropdown', function () {
      setTimeout(() => {
        body.classList.add('active-nav-body')
        headerWrapper.classList.add('active-nav')
      }, 0)
    })
  })
}

if (document.querySelector('.mainnav-desktop')) {
  mainNavigationDesktop()
}

function resize () {
  // Initialize the media query
  const mediaQuery = window.matchMedia('(min-width: 62rem)')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')

  // Function to do something with the media query
  function mediaQueryListener (mediaQuery) {
    const NavbarToggler = document.querySelector('.navbar-toggler.show')
    const FirstNav = document.querySelector('.first-nav.show')

    if (mediaQuery.matches) {
      if (NavbarToggler) {
        NavbarToggler.click()
      }
      body.classList.remove('active-nav-body')
      headerWrapper.classList.remove('active-nav')
      if (FirstNav) {
        FirstNav.click()
      }
    } else {
      if (FirstNav) {
        FirstNav.click()
      }
      body.classList.remove('active-nav-body')
      headerWrapper.classList.remove('active-nav')
      if (NavbarToggler) {
        NavbarToggler.click()
      }
    }
  }

  // On change
  mediaQuery.addEventListener('change', mediaQueryListener)

  // On load
  mediaQueryListener(mediaQuery)
}

resize()
