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
import"bootstrap";import $ from"jquery";import{AbstractInteractableModule}from"../abstract-interactable-module";import Modal from"@typo3/backend/modal";import Notification from"@typo3/backend/notification";import AjaxRequest from"@typo3/core/ajax/ajax-request";import InfoBox from"../../renderable/info-box";import ProgressBar from"../../renderable/progress-bar";import Severity from"../../renderable/severity";import Router from"../../router";class EnvironmentCheck extends AbstractInteractableModule{constructor(){super(...arguments),this.selectorGridderBadge=".t3js-environmentCheck-badge",this.selectorExecuteTrigger=".t3js-environmentCheck-execute",this.selectorOutputContainer=".t3js-environmentCheck-output"}initialize(e){this.currentModal=e,this.runTests(),e.on("click",this.selectorExecuteTrigger,(e=>{e.preventDefault(),this.runTests()}))}runTests(){this.setModalButtonsState(!1);const e=this.getModalBody(),t=$(this.selectorGridderBadge);t.text("").hide();const r=ProgressBar.render(Severity.loading,"Loading...","");e.find(this.selectorOutputContainer).empty().append(r),new AjaxRequest(Router.getUrl("environmentCheckGetStatus")).get({cache:"no-cache"}).then((async r=>{const o=await r.resolve();e.empty().append(o.html),Modal.setButtons(o.buttons);let s=0,n=0;if(!0===o.success&&"object"==typeof o.status){for(const t of Object.values(o.status))for(const r of t){1===r.severity&&s++,2===r.severity&&n++;const t=InfoBox.render(r.severity,r.title,r.message);e.find(this.selectorOutputContainer).append(t)}n>0?t.removeClass("badge-warning").addClass("badge-danger").text(n).show():s>0&&t.removeClass("badge-error").addClass("badge-warning").text(s).show()}else Notification.error("Something went wrong","The request was not processed successfully. Please check the browser's console and TYPO3's log.")}),(t=>{Router.handleAjaxError(t,e)}))}}export default new EnvironmentCheck;