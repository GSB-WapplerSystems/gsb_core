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
import Notification from"@typo3/backend/notification";import AjaxRequest from"@typo3/core/ajax/ajax-request";import Router from"../../router";import{AbstractInteractableModule}from"../abstract-interactable-module";class PhpInfo extends AbstractInteractableModule{initialize(e){this.currentModal=e,this.getData()}getData(){const e=this.getModalBody();new AjaxRequest(Router.getUrl("phpInfoGetData")).get({cache:"no-cache"}).then((async t=>{const o=await t.resolve();!0===o.success?e.empty().append(o.html):Notification.error("Something went wrong","The request was not processed successfully. Please check the browser's console and TYPO3's log.")}),(t=>{Router.handleAjaxError(t,e)}))}}export default new PhpInfo;