document.addEventListener('DOMContentLoaded', function () {
  const dsgvoContainers = document.querySelectorAll('.dsgvo-external');

  dsgvoContainers.forEach(container => {
    container.addEventListener('dsgvo:consent-granted', function () {
      activateExternalContent(container);
    });
  });

  function activateExternalContent(container) {
    const iframe = container.querySelector('iframe');
    if (iframe && container.dataset.dsgvoSrc) {
      const src = container.dataset.dsgvoSrc;
      iframe.setAttribute('src', src);
      delete container.dataset.dsgvoSrc;
    }

    const webComponent = container.querySelector('.external-webcomponent');
    if (webComponent && container.dataset.dsgvoUrl) {
      const url = container.dataset.dsgvoUrl;
      let inner = webComponent.querySelector('iframe');
      if (!inner) {
        inner = document.createElement('iframe');
        webComponent.appendChild(inner);
      }
      inner.setAttribute('src', url);
      inner.setAttribute('loading', 'lazy');
      inner.setAttribute('allowfullscreen', '');

      const wAttr = webComponent.getAttribute('width') || webComponent.dataset.width;
      const hAttr = webComponent.getAttribute('height') || webComponent.dataset.height;
      if (wAttr) inner.setAttribute('width', wAttr);
      if (hAttr) inner.setAttribute('height', hAttr);

      if (webComponent.style.width && !inner.style.width) inner.style.width = webComponent.style.width;
      if (webComponent.style.height && !inner.style.height) inner.style.height = webComponent.style.height;

      inner.style.border = 'none';
      delete container.dataset.dsgvoUrl;
    }

    adjustContainerSize(container);
  }

  function adjustContainerSize(container) {
    const iframe = container.querySelector('iframe');
    const webComponent = container.querySelector('.external-webcomponent');

    const readDimension = (el, attrName) => {
      if (!el) return null;
      const attr = el.getAttribute(attrName);
      if (attr) return attr;
      const styleVal = (attrName === 'width') ? el.style.width : el.style.height;
      if (styleVal) return styleVal;
      if (attrName === 'width' && el.clientWidth) return el.clientWidth + 'px';
      if (attrName === 'height' && el.clientHeight) return el.clientHeight + 'px';
      return null;
    };

    let width = null, height = null;
    if (iframe) {
      width = readDimension(iframe, 'width');
      height = readDimension(iframe, 'height');
    } else if (webComponent) {
      width = readDimension(webComponent, 'width');
      height = readDimension(webComponent, 'height');
    }

    if (width) container.style.width = width;
    if (height) container.style.height = height;
  }
});
