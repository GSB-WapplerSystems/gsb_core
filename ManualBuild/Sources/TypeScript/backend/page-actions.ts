/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

import DocumentService from '@typo3/core/document-service';
import RegularEvent from '@typo3/core/event/regular-event';
import PersistentStorage from './storage/persistent';

enum IdentifierEnum {
  hiddenElements = '.t3js-hidden-record',
}

/**
 * Module: @typo3/backend/page-actions
 * JavaScript implementations for page actions
 */
class PageActions {
  constructor() {
    DocumentService.ready().then((): void => {
      const showHiddenElementsCheckbox = document.getElementById('checkShowHidden') as HTMLInputElement;
      if (showHiddenElementsCheckbox !== null) {
        new RegularEvent('change', this.toggleContentElementVisibility).bindTo(showHiddenElementsCheckbox);
      }
    });
  }

  /**
   * Toggles the "Show hidden content elements" checkbox
   */
  private toggleContentElementVisibility(e: Event): void {
    const me = e.target as HTMLInputElement;
    const hiddenElements = document.querySelectorAll(IdentifierEnum.hiddenElements) as NodeListOf<HTMLElement>;
    me.disabled = true;

    for (const hiddenElement of hiddenElements) {
      hiddenElement.style.display = 'block';
      const scrollHeight = hiddenElement.scrollHeight;
      hiddenElement.style.display = '';

      if (!me.checked) {
        // Spacing between content elements is kept uniform by collapsed margins,
        // hidden elements have a height of 0 and the margins of the surrounding elements
        // cannot collapse, causing a visual gap. We therefore remove the element
        // from the flow to prevent this.
        hiddenElement.addEventListener('transitionend', (): void => {
          hiddenElement.style.position = 'absolute';
        }, { once: true });

        // We use requestAnimationFrame() as we have to set the container's height at first before resizing to 0px
        // results in a smooth animation.
        requestAnimationFrame(function() {
          hiddenElement.style.height = scrollHeight + 'px';
          requestAnimationFrame(function() {
            hiddenElement.style.height = 0 + 'px';
          });
        });
      } else {
        hiddenElement.style.position = '';
        hiddenElement.style.height = scrollHeight + 'px';
      }
    }

    PersistentStorage.set('moduleData.web_layout.showHidden', me.checked ? '1' : '0').then((): void => {
      me.disabled = false;
    });
  }
}

export default new PageActions();
