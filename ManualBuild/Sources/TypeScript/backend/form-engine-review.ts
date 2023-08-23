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

import 'bootstrap';
import DocumentService from '@typo3/core/document-service';
import $ from 'jquery';
import FormEngine from '@typo3/backend/form-engine';
import '@typo3/backend/element/icon-element';
import Popover from './popover';
import { Popover as BootstrapPopover } from 'bootstrap';

/**
 * Module: @typo3/backend/form-engine-review
 * Enables interaction with record fields that need review
 * @exports @typo3/backend/form-engine-review
 */
class FormEngineReview {

  /**
   * Class for the toggle button
   */
  private readonly toggleButtonClass: string = 't3js-toggle-review-panel';

  /**
   * Class of FormEngine labels
   */
  private readonly labelSelector: string = '.t3js-formengine-label';

  /**
   * Class of FormEngine legends
   */
  private readonly legendSelector: string = '.t3js-formengine-legend';

  /**
   * The constructor, set the class properties default values
   */
  constructor() {
    this.initialize();
  }

  /**
   * Fetches all fields that have a failed validation
   *
   * @return {$}
   */
  public static findInvalidField(): any {
    return $(document).find('.tab-content .' + FormEngine.Validation.errorClass);
  }

  /**
   * Renders an invisible button to toggle the review panel into the least possible toolbar
   *
   * @param {Object} context
   */
  public static attachButtonToModuleHeader(context: any): void {
    const leastButtonBar: HTMLElement = document.querySelector('.t3js-module-docheader-bar-buttons').lastElementChild.querySelector('[role="toolbar"]');

    const icon = document.createElement('typo3-backend-icon');
    icon.setAttribute('identifier', 'actions-info');
    icon.setAttribute('size', 'small');

    const button = document.createElement('button');
    button.type = 'button';
    button.classList.add('btn', 'btn-danger', 'btn-sm', 'hidden', context.toggleButtonClass);
    button.title = TYPO3.lang['buttons.reviewFailedValidationFields'];
    button.appendChild(icon);

    Popover.popover(button);
    leastButtonBar.prepend(button);
  }

  /**
   * Initialize the events
   */
  public initialize(): void {
    const $document: any = $(document);

    DocumentService.ready().then((): void => {
      FormEngineReview.attachButtonToModuleHeader(this);
    });
    $document.on('t3-formengine-postfieldvalidation', this.checkForReviewableField);
  }

  /**
   * Checks if fields have failed validation. In such case, the markup is rendered and the toggle button is unlocked.
   */
  public checkForReviewableField = (): void => {
    const $invalidFields: any = FormEngineReview.findInvalidField();
    const toggleButton: HTMLElement = document.querySelector('.' + this.toggleButtonClass);
    if (toggleButton === null) {
      return;
    }

    if ($invalidFields.length > 0) {
      const $list: any = $('<div />', { class: 'list-group' });

      $invalidFields.each((index: number, element: Element): void => {
        const $field: any = $(element);
        const $input: JQuery = $field.find('[data-formengine-validation-rules]');

        const link = document.createElement('a');
        link.classList.add('list-group-item');
        link.href = '#';
        link.textContent = $field.find(this.labelSelector).text() || $field.find(this.legendSelector).text();
        link.addEventListener('click', (e: Event) => this.switchToField(e, $input));

        $list.append(link);
      });

      toggleButton.classList.remove('hidden');
      Popover.setOptions(toggleButton, <BootstrapPopover.Options>{
        html: true,
        content: $list[0]
      });
    } else {
      toggleButton.classList.add('hidden');
      Popover.hide(toggleButton);
    }
  };

  /**
   * Finds the field in the form and focuses it
   *
   * @param {Event} e
   */
  public switchToField = (e: Event, $referenceField: JQuery): void => {
    e.preventDefault();

    // iterate possibly nested tab panels
    $referenceField.parents('[id][role="tabpanel"]').each(function(this: Element): void {
      $('[aria-controls="' + $(this).attr('id') + '"]').tab('show');
    });

    $referenceField.focus();
  };
}

// create an instance and return it
export default new FormEngineReview();
