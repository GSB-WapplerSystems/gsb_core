function MainNavDesktop () {
  const MainNavDesktopItem = document.querySelectorAll('.mainnav-desktop-item')
  const body = document.querySelector('body')
  const headerWrapper = document.querySelector('.header-wrapper')

  MainNavDesktopItem.forEach(function (element) {
    element.addEventListener('show.bs.dropdown', function () {
      body.classList.add('active-nav-body')
      headerWrapper.classList.add('active-nav')
    })

    element.addEventListener('hide.bs.dropdown', function () {
      body.classList.remove('active-nav-body')
      headerWrapper.classList.remove('active-nav')
    })
  })
}

if (document.querySelector('.mainnav-desktop-item')) {
  MainNavDesktop()
}
