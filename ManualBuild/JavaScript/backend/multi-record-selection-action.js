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
import{MultiRecordSelectionSelectors}from"@typo3/backend/multi-record-selection";export class MultiRecordSelectionAction{static getEntityIdentifiers(e){const t=[];return e.checkboxes.forEach((o=>{const c=o.closest(MultiRecordSelectionSelectors.elementSelector);null!==c&&c.dataset[e.configuration.idField]&&t.push(c.dataset[e.configuration.idField])})),t}}