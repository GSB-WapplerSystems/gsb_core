// SPDX-FileCopyrightText: 2024 Bundesrepublik Deutschland, vertreten durch das BMI/ITZBund
//
// SPDX-License-Identifier: GPL-3.0-or-later

(function () {
  // Check if the user has already consented to cookies
  const cookiesAccepted = getCookie('cookies_accepted');

  // Get the cookie banner container element by its ID
  const cookieBannerContainer = document.getElementById('cookie-banner');

  // Extract the URL from the dataset or default to an empty string if the container doesn't exist
  const url = cookieBannerContainer ? cookieBannerContainer.dataset.src : '';

  // If the user has already accepted cookies, remove the cookie banner
  if (cookiesAccepted) {
    removeCookieBannerContainer();
  }

  // Add the cookie banner as the first element in the body
  document.body.insertBefore(cookieBannerContainer, document.body.firstChild);

  // If cookies are not accepted and a URL for the banner is provided, fetch and display the banner
  if (!cookiesAccepted && url !== '') {
    const xhr = new XMLHttpRequest();
    const method = 'GET';

    xhr.open(method, url, true);
    xhr.onreadystatechange = () => {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        const status = xhr.status;
        if (status === 0 || (status >= 200 && status < 400)) {
          // Update the cookie banner container with the fetched HTML content
          cookieBannerContainer.innerHTML = xhr.responseText;

          const consentHeader = document.querySelector('.consent-header');
          if (consentHeader) {
            consentHeader.focus();
          }

          // Check if HTML content was loaded successfully
          if (xhr.responseText.trim() === '') {
            // If HTML is empty, remove the cookie banner
            removeCookieBannerContainer();
            return;
          }

          // Find the button element within the cookie banner
          const button = cookieBannerContainer.querySelector('button');

          // If button is found, add an event listener for accepting cookies
          if (button) {
            button.addEventListener('click', acceptCookies);
          } else {
            // eslint-disable-next-line no-console
            console.error('Button not found in the cookie banner');
          }
        } else {
          // eslint-disable-next-line no-console
          console.error('Oh no! There has been an error with the request!');
        }
      }
    };
    xhr.send();
  }

  // Function to remove the cookie banner container from the DOM
  function removeCookieBannerContainer() {
    const existingBanner = document.getElementById('cookie-banner');

    if (existingBanner) {
      existingBanner.parentNode.removeChild(existingBanner);
    }
  }

  // Function to handle the acceptance of cookies
  function acceptCookies() {
    // Set the cookie to mark acceptance
    const expirationDate = Date.now() + 30 * 86400 * 1000; // 30 days in milliseconds
    setCookie('cookies_accepted', 'true', expirationDate);

    // Remove the cookie banner
    removeCookieBannerContainer();
  }

  // Helper function to set a cookie
  function setCookie(name, value, expirationDate) {
    document.cookie = `${name}=${value}; expires=${new Date(expirationDate).toUTCString()}; path=/`;
  }

  // Helper function to read a cookie
  function getCookie(name) {
    const cookieName = `${name}=`;
    const cookies = document.cookie.split(';');

    for (let i = 0; i < cookies.length; i++) {
      const cookie = cookies[i].trim();
      if (cookie.startsWith(cookieName)) {
        return cookie.substring(cookieName.length, cookie.length);
      }
    }

    return null;
  }
})();
