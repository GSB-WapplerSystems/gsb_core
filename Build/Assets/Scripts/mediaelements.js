function mediaelements () {
  const language = document.querySelector('html').getAttribute('lang')
  if (language === 'de') {
    // eslint-disable-next-line no-undef
    mejs.i18n.language('de')
  } else {
    // eslint-disable-next-line no-undef
    mejs.i18n.language('en')
  }

  if (document.querySelector('.video-local video')) {
    const videoPlayer = document.querySelectorAll('.video-local video')
    videoPlayer.forEach(function (videoPlayer) {
      // eslint-disable-next-line no-undef,no-new
      new MediaElementPlayer(videoPlayer, {
        toggleCaptionsButtonWhenOnlyOne: true,
        iPadUseNativeControls: true,
        iPhoneUseNativeControls: true,
        AndroidUseNativeControls: true,
        videoVolume: 'horizontal',
        nocookie: true,
        iconSprite: '/typo3conf/ext/gsb_template/Resources/Public/Images/mejs-controls.svg',

        success: function (videoPlayer) {
          const jumpLink = document.querySelectorAll('.jump-link')
          jumpLink.forEach(function (element) {
            element.addEventListener('click', (e) => {
              e.preventDefault()
              const jumpLinkData = e.target.dataset.seek
              const date = new Date('1970-01-01T' + jumpLinkData + '.000Z')
              const seconds = Math.floor(date.getTime() / 1000)
              videoPlayer.setCurrentTime(seconds)
              videoPlayer.play()
            })
          })
        }
      })
    })
  }

  if (document.querySelector('.popup-player')) {
    const popupPlayer = document.querySelectorAll('.popup-player')
    popupPlayer.forEach(function (popupPlayer) {
      // eslint-disable-next-line no-undef,no-new
      new MediaElementPlayer(popupPlayer, {
        toggleCaptionsButtonWhenOnlyOne: true,
        iPadUseNativeControls: true,
        iPhoneUseNativeControls: true,
        AndroidUseNativeControls: true,
        videoVolume: 'horizontal',
        nocookie: true,
        iconSprite: '/typo3conf/ext/gsb_template/Resources/Public/Images/mejs-controls.svg'
      })

      const popupVideo = document.querySelector('.js-popup-player')
      popupVideo.addEventListener('shown.bs.modal', function () {
        popupPlayer.play()
      })
      popupVideo.addEventListener('hide.bs.modal', function () {
        popupPlayer.pause()
      })
    })
  }

  if (document.querySelector('.audio-local audio')) {
    const audioPlayer = document.querySelectorAll('.audio-local audio')
    audioPlayer.forEach(function (audioPlayer) {
      // eslint-disable-next-line no-undef,no-new
      new MediaElementPlayer(audioPlayer, {
        toggleCaptionsButtonWhenOnlyOne: true,
        iPadUseNativeControls: true,
        iPhoneUseNativeControls: true,
        AndroidUseNativeControls: true,
        audioVolume: 'horizontal',
        nocookie: true,
        iconSprite: '/typo3conf/ext/gsb_template/Resources/Public/Images/mejs-controls.svg'
      })
    })
  }
}

if (document.querySelector('.jump-link')) {
  const jumpLink = document.querySelectorAll('.jump-link')
  jumpLink.forEach(function (element) {
    element.addEventListener('click', (e) => {
      e.preventDefault()
    })
  })
}

function youtube () {
  const language = document.querySelector('html').getAttribute('lang')
  let langShort
  let langLong

  if (language === 'de') {
    langShort = 'de'
    langLong = 'Deutsch'
  } else {
    langShort = 'en'
    langLong = 'English'
  }

  const videoDsgvo = document.querySelectorAll('.video-start')
  videoDsgvo.forEach(function (element) {
    element.addEventListener('click', () => {
      const dsgvo = element.closest('.dsgvo')
      const playerWrapper = element.closest('.player-wrapper')
      const src = dsgvo.getAttribute('data-src')
      const track = dsgvo.getAttribute('data-track')

      if (track) {
        dsgvo.innerHTML += '<video class="youtube" style="width:100%;height:100%;" controls autoplay preload="none" src="' + src + '"><track src="' + track + '" srclang="' + langShort + '" label="' + langLong + '" kind="subtitles" type="text/vtt"></video>'
      } else {
        dsgvo.innerHTML += '<video class="youtube" style="width:100%;height:100%;" controls autoplay preload="none" src="' + src + '"></video>'
      }

      dsgvo.removeAttribute('style')

      const player = dsgvo.querySelector('.youtube')

      player.style.display = 'block'

      playerWrapper.remove()

      const language = document.querySelector('html').getAttribute('lang')
      if (language === 'de') {
        // eslint-disable-next-line no-undef
        mejs.i18n.language('de')
      } else {
        // eslint-disable-next-line no-undef
        mejs.i18n.language('en')
      }

      // eslint-disable-next-line no-undef,no-new
      new MediaElementPlayer(player, {
        toggleCaptionsButtonWhenOnlyOne: true,
        iPadUseNativeControls: true,
        iPhoneUseNativeControls: true,
        AndroidUseNativeControls: true,
        videoVolume: 'horizontal',
        nocookie: true,
        autoplay: 'true',
        iconSprite: '/typo3conf/ext/gsb_template/Resources/Public/Images/mejs-controls.svg',

        success: function (player) {
          const jumpLink = document.querySelectorAll('.jump-link')
          jumpLink.forEach(function (element) {
            element.addEventListener('click', (e) => {
              e.preventDefault()
              const jumpLinkData = e.target.dataset.seek
              const date = new Date('1970-01-01T' + jumpLinkData + '.000Z')
              const seconds = Math.floor(date.getTime() / 1000)
              player.setCurrentTime(seconds)
              player.play()
            })
          })
        }
      })
    })
  })
}

function soundcloud () {
  const videoDsgvo = document.querySelectorAll('.audio-start')
  videoDsgvo.forEach(function (element) {
    element.addEventListener('click', () => {
      const dsgvo = element.closest('.dsgvo')
      const playerWrapper = element.closest('.player-wrapper')
      const src = element.getAttribute('data-mediaurl')
      playerWrapper.remove()
      dsgvo.removeAttribute('style')
      dsgvo.innerHTML += '<iframe title="SoudCloud" width="100%" height="487" scrolling="no" frameborder="no" allow="autoplay" src="https://w.soundcloud.com/player/?url=' + src + '&color=%23ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false&show_teaser=true&visual=true"></iframe>'
    })
  })
}

if (document.querySelector('audio, video')) {
  mediaelements()
}

if (document.querySelector('.video-youtube')) {
  youtube()
}

if (document.querySelector('.audio-soundcloud')) {
  soundcloud()
}

if (document.querySelector('.mejs__poster-img')) {
  document.querySelector('.mejs__poster-img').setAttribute('alt', '')
}

if (document.querySelector('.mejs__horizontal-volume-slider')) {
  document.querySelector('.mejs__horizontal-volume-slider').removeAttribute('href')
}
