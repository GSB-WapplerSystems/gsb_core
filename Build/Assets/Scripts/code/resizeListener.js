// eslint-disable-next-line no-unused-vars
function detachDesktop () {
  document.querySelector('.meta-desktop').appendChild(document.querySelector('.meta-item'))
}

// eslint-disable-next-line no-unused-vars
function detachMobile () {
  document.querySelector('.meta-mobile').appendChild(document.querySelector('.meta-item'))
}

function resize () {
  // Initialize the media query
  const mediaQuery = window.matchMedia('(min-width: 62rem)')

  // Function to do something with the media query
  function mediaQueryListener (mediaQuery) {
    if (mediaQuery.matches) {
      detachDesktop()
    } else {
      detachMobile()
    }
  }

  // On change
  mediaQuery.addEventListener('change', mediaQueryListener)

  // On load
  mediaQueryListener(mediaQuery)
}

resize()
