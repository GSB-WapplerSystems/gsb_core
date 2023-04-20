function mainnavDesktop () {
  // const body = document.querySelector('body')
  // const headerWrapper = document.querySelector('.header-wrapper')

  const FirstChild = document.querySelectorAll('.first-child')
  FirstChild.forEach(function (element) {
    element.addEventListener('click', function () {
      // test
    })
  })
}

if (document.querySelector('#main-menu')) {
  mainnavDesktop()
}
