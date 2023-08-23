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
import DocumentService from"@typo3/core/document-service";import $ from"jquery";import{MessageUtility}from"@typo3/backend/utility/message-utility";import{KeyTypesEnum}from"./enum/key-types";import NProgress from"nprogress";import AjaxRequest from"@typo3/core/ajax/ajax-request";import SecurityUtility from"@typo3/core/security-utility";import Modal from"./modal";import Notification from"./notification";import Severity from"./severity";class OnlineMedia{constructor(){this.securityUtility=new SecurityUtility,DocumentService.ready().then((()=>{this.registerEvents()}))}registerEvents(){$(document).on("click",".t3js-online-media-add-btn",(e=>{this.triggerModal($(e.currentTarget))}))}addOnlineMedia(e,t,i){const r=e.data("target-folder"),o=e.data("online-media-allowed"),a=e.data("file-irre-object");NProgress.start(),new AjaxRequest(TYPO3.settings.ajaxUrls.online_media_create).post({url:i,targetFolder:r,allowed:o}).then((async e=>{const i=await e.resolve();if(i.file){const e={actionName:"typo3:foreignRelation:insert",objectGroup:a,table:"sys_file",uid:i.file};MessageUtility.send(e),t.hideModal()}else Notification.error(top.TYPO3.lang["online_media.error.new_media.failed"],i.error);NProgress.done()}))}triggerModal(e){const t=e.data("btn-submit")||"Add",i=e.data("placeholder")||"Paste media url here...",r=$.map(e.data("online-media-allowed").split(","),(e=>'<span class="badge badge-success">'+this.securityUtility.encodeHtml(e.toUpperCase(),!1)+"</span>")),o=e.data("online-media-allowed-help-text")||"Allow to embed from sources:",a=$("<div>").attr("class","form-control-wrap").append([$("<input>").attr("type","text").attr("class","form-control online-media-url").attr("placeholder",i),$("<div>").attr("class","form-text").html(this.securityUtility.encodeHtml(o,!1)+"<br>"+r.join(" "))]),n=Modal.show(e.attr("title"),a,Severity.notice,[{text:t,btnClass:"btn btn-primary",name:"ok",trigger:()=>{const t=$(n).find("input.online-media-url").val();t&&this.addOnlineMedia(e,n,t)}}]);n.addEventListener("typo3-modal-shown",(e=>{$(e.currentTarget).find("input.online-media-url").first().focus().on("keydown",(e=>{e.keyCode===KeyTypesEnum.ENTER&&$(n).find('button[name="ok"]').trigger("click")}))}))}}export default new OnlineMedia;