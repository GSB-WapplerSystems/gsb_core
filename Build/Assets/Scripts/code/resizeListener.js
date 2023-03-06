// eslint-disable-next-line no-unused-vars
function detachMetaMobile () {
  document.querySelector('.meta-mobile').appendChild(document.querySelector('.meta-wrapper'))
}

// eslint-disable-next-line no-unused-vars
function detachMetaDesktop () {
  document.querySelector('.meta-desktop').appendChild(document.querySelector('.meta-wrapper'))
}

function resize () {
  // Initialize the media query
  const mediaQuery = window.matchMedia('(min-width: 62rem)')

  // Function to do something with the media query
  function mediaQueryListener (mediaQuery) {
    if (mediaQuery.matches) {
      // do something
      // detachMetaDesktop()
    } else {
      // do something
      // detachMetaMobile()
    }
  }

  // On change
  mediaQuery.addEventListener('change', mediaQueryListener)

  // On load
  mediaQueryListener(mediaQuery)
}

resize()
