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

import { AbstractSortableSelectItems } from '@typo3/backend/form-engine/element/abstract-sortable-select-items';

class FolderSortableSelectItems extends AbstractSortableSelectItems {
  public registerEventHandler(element: HTMLSelectElement): void {
    this.registerSortableEventHandler(element);
  }
}

/**
 * Module: @typo3/backend/form-engine/element/folder-element
 *
 * Functionality for the folder element
 *
 * @example
 * <typo3-formengine-element-folder recordFieldId="some-id">
 *   ...
 * </typo3-formengine-element-folder>
 *
 * This is based on W3C custom elements ("web components") specification, see
 * https://developer.mozilla.org/en-US/docs/Web/Web_Components/Using_custom_elements
 */
class FolderElement extends HTMLElement {
  private recordField: HTMLSelectElement = null;

  public connectedCallback(): void {
    this.recordField = <HTMLSelectElement>this.querySelector('#' + (this.getAttribute('recordFieldId') || '' as string));

    if (!this.recordField) {
      return;
    }

    this.registerEventHandler();
  }

  private registerEventHandler(): void {
    const folderSortableSelectItems = new FolderSortableSelectItems();
    folderSortableSelectItems.registerEventHandler(this.recordField);
  }
}

window.customElements.define('typo3-formengine-element-folder', FolderElement);
