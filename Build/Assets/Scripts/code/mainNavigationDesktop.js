function MainNavDesktop () {
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

function resize () {
  // Initialize the media query
  const mediaQuery = window.matchMedia('(min-width: 62rem)')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')

  // Function to do something with the media query
  function mediaQueryListener (mediaQuery) {
    const NavbarToggler = document.querySelectorAll('.navbar-toggler.show')
    const DropdownMenu = document.querySelectorAll('.first-child.show')

    if (mediaQuery.matches) {
      body.classList.remove('active-nav-body')
      headerWrapper.classList.remove('active-nav')
      if (document.querySelector('.mainnav-desktop-item')) {
        DropdownMenu.forEach(function (element) {
          // eslint-disable-next-line no-undef
          bootstrap.Dropdown.getInstance(element).hide()
        })
        MainNavDesktop()
      }
    } else {
      body.classList.remove('active-nav-body')
      headerWrapper.classList.remove('active-nav')
      NavbarToggler.forEach(function (element) {
        // eslint-disable-next-line no-undef
        bootstrap.Dropdown.getInstance(element).hide()
      })
    }
  }

  // On change
  mediaQuery.addEventListener('change', mediaQueryListener)

  // On load
  mediaQueryListener(mediaQuery)
}

resize()
