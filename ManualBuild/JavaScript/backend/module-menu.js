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
import{ScaffoldIdentifierEnum}from"./enum/viewport/scaffold-identifier";import{flushModuleCache,ModuleSelector,ModuleUtility}from"@typo3/backend/module";import $ from"jquery";import PersistentStorage from"./storage/persistent";import Viewport from"./viewport";import ClientRequest from"./event/client-request";import TriggerRequest from"./event/trigger-request";import AjaxRequest from"@typo3/core/ajax/ajax-request";import RegularEvent from"@typo3/core/event/regular-event";import{ModuleStateStorage}from"./storage/module-state-storage";var ModuleMenuSelector;!function(e){e.menu="[data-modulemenu]",e.item="[data-modulemenu-identifier]",e.collapsible='[data-modulemenu-collapsible="true"]'}(ModuleMenuSelector||(ModuleMenuSelector={}));class ModuleMenu{constructor(){this.loadedModule=null,$((()=>this.initialize()))}static getModuleMenuItemFromElement(e){return{identifier:e.dataset.modulemenuIdentifier,level:e.parentElement.dataset.modulemenuLevel?parseInt(e.parentElement.dataset.modulemenuLevel,10):null,collapsible:"true"===e.dataset.modulemenuCollapsible,expanded:"true"===e.attributes.getNamedItem("aria-expanded")?.value,element:e}}static getCollapsedMainMenuItems(){return PersistentStorage.isset("modulemenu")?JSON.parse(PersistentStorage.get("modulemenu")):{}}static addCollapsedMainMenuItem(e){const t=ModuleMenu.getCollapsedMainMenuItems();t[e]=!0,PersistentStorage.set("modulemenu",JSON.stringify(t))}static removeCollapseMainMenuItem(e){const t=this.getCollapsedMainMenuItems();delete t[e],PersistentStorage.set("modulemenu",JSON.stringify(t))}static includeId(e,t){if(!e.navigationComponentId)return t;let o="";o="@typo3/backend/page-tree/page-tree-element"===e.navigationComponentId?"web":e.name.split("_")[0];const n=ModuleStateStorage.current(o);return n.selection&&(t="id="+n.selection+"&"+t),t}static toggleMenu(e){const t=document.querySelector(ScaffoldIdentifierEnum.scaffold),o="scaffold-modulemenu-expanded";void 0===e&&(e=t.classList.contains(o)),t.classList.toggle(o,!e),e||t.classList.remove("scaffold-toolbar-expanded"),PersistentStorage.set("BackendComponents.States.typo3-module-menu",{collapsed:e})}static toggleModuleGroup(e){const t=ModuleMenu.getModuleMenuItemFromElement(e),o=t.element.closest(".modulemenu-group"),n=o.querySelector(".modulemenu-group-container");t.expanded?ModuleMenu.addCollapsedMainMenuItem(t.identifier):ModuleMenu.removeCollapseMainMenuItem(t.identifier),o.classList.toggle("modulemenu-group-collapsed",t.expanded),o.classList.toggle("modulemenu-group-expanded",!t.expanded),e.setAttribute("aria-expanded",(!t.expanded).toString()),$(n).stop().slideToggle()}static highlightModule(e){document.querySelector(ModuleMenuSelector.menu).querySelectorAll(ModuleMenuSelector.item).forEach((e=>{e.classList.remove("modulemenu-action-active"),e.removeAttribute("aria-current")}));document.querySelector(".t3js-scaffold-toolbar").querySelectorAll(ModuleSelector.link+".dropdown-item").forEach((e=>{e.classList.remove("active"),e.removeAttribute("aria-current")}));const t=ModuleUtility.getFromName(e);this.highlightModuleMenuItem(t,!0)}static highlightModuleMenuItem(e,t=!0){const o=document.querySelector(ModuleMenuSelector.menu).querySelectorAll(ModuleMenuSelector.item+'[data-modulemenu-identifier="'+e.name+'"]');o.forEach((e=>{e.classList.add("modulemenu-action-active"),t&&e.setAttribute("aria-current","location")}));const n=document.querySelector(".t3js-scaffold-toolbar").querySelectorAll(ModuleSelector.link+'[data-moduleroute-identifier="'+e.name+'"].dropdown-item');n.forEach((e=>{e.classList.add("active"),t&&e.setAttribute("aria-current","location")})),(o.length>0||n.length>0)&&(t=!1),""!==e.parent&&this.highlightModuleMenuItem(ModuleUtility.getFromName(e.parent),t)}static getPreviousItem(e){const t=e.parentElement.previousElementSibling;return null===t?ModuleMenu.getLastItem(e):t.firstElementChild}static getNextItem(e){const t=e.parentElement.nextElementSibling;return null===t?ModuleMenu.getFirstItem(e):t.firstElementChild}static getFirstItem(e){return e.parentElement.parentElement.firstElementChild.firstElementChild}static getLastItem(e){return e.parentElement.parentElement.lastElementChild.firstElementChild}static getParentItem(e){return e.parentElement.parentElement.parentElement.firstElementChild}static getFirstChildItem(e){return e.nextElementSibling.firstElementChild.firstElementChild}refreshMenu(){return new AjaxRequest(TYPO3.settings.ajaxUrls.modulemenu).get().then((async e=>{const t=await e.resolve();document.getElementById("modulemenu").outerHTML=t.menu,flushModuleCache(),this.initializeModuleMenuEvents(),this.loadedModule&&ModuleMenu.highlightModule(this.loadedModule)}))}getCurrentModule(){return this.loadedModule}reloadFrames(){Viewport.ContentContainer.refresh()}showModule(e,t,o=null){t=t||"";const n=ModuleUtility.getFromName(e);return this.loadModuleComponents(n,t,new ClientRequest("typo3.showModule",o))}initialize(){if(null===document.querySelector(ModuleMenuSelector.menu))return;const e=$.Deferred();e.resolve(),e.then((()=>{this.initializeModuleMenuEvents(),Viewport.Topbar.Toolbar.registerEvent((()=>{document.querySelector(".t3js-scaffold-toolbar")&&this.initializeTopBarEvents()}))}))}keyboardNavigation(e,t){const o=ModuleMenu.getModuleMenuItemFromElement(t);let n=null;switch(e.code){case"ArrowUp":n=ModuleMenu.getPreviousItem(o.element);break;case"ArrowDown":n=ModuleMenu.getNextItem(o.element);break;case"ArrowLeft":o.level>1&&(n=ModuleMenu.getParentItem(o.element));break;case"ArrowRight":o.collapsible&&(o.expanded||ModuleMenu.toggleModuleGroup(o.element),n=ModuleMenu.getFirstChildItem(o.element));break;case"Home":if(e.ctrlKey&&o.level>1){n=document.querySelector(ModuleMenuSelector.menu+" "+ModuleMenuSelector.item);break}n=ModuleMenu.getFirstItem(o.element);break;case"End":n=e.ctrlKey&&o.level>1?ModuleMenu.getLastItem(document.querySelector(ModuleMenuSelector.menu+" "+ModuleMenuSelector.item)):ModuleMenu.getLastItem(o.element);break;case"Space":case"Enter":("Space"===e.code||o.collapsible)&&e.preventDefault(),o.collapsible&&(ModuleMenu.toggleModuleGroup(o.element),"true"===o.element.attributes.getNamedItem("aria-expanded").value&&(n=ModuleMenu.getFirstChildItem(o.element)));break;case"Esc":case"Escape":o.level>1&&(n=ModuleMenu.getParentItem(o.element),ModuleMenu.toggleModuleGroup(n));break;default:n=null}null!==n&&n.focus()}initializeModuleMenuEvents(){const e=document.querySelector(ModuleMenuSelector.menu);new RegularEvent("keydown",this.keyboardNavigation).delegateTo(e,ModuleMenuSelector.item),new RegularEvent("click",((e,t)=>{e.preventDefault();const o=ModuleUtility.getRouteFromElement(t);this.showModule(o.identifier,o.params,e)})).delegateTo(e,ModuleSelector.link),new RegularEvent("click",((e,t)=>{e.preventDefault(),ModuleMenu.toggleModuleGroup(t)})).delegateTo(e,ModuleMenuSelector.collapsible)}initializeTopBarEvents(){const e=document.querySelector(".t3js-scaffold-toolbar");new RegularEvent("click",((e,t)=>{e.preventDefault();const o=ModuleUtility.getRouteFromElement(t);this.showModule(o.identifier,o.params,e)})).delegateTo(e,ModuleSelector.link),new RegularEvent("click",(e=>{e.preventDefault(),ModuleMenu.toggleMenu()})).bindTo(document.querySelector(".t3js-topbar-button-modulemenu")),new RegularEvent("click",(e=>{e.preventDefault(),ModuleMenu.toggleMenu(!0)})).bindTo(document.querySelector(".t3js-scaffold-content-overlay"));const t=e=>{const t=e.detail.module;if(!t||this.loadedModule===t)return;const o=ModuleUtility.getFromName(t);o.link&&(ModuleMenu.highlightModule(t),this.loadedModule=t,o.navigationComponentId?Viewport.NavigationContainer.showComponent(o.navigationComponentId):Viewport.NavigationContainer.hide())};document.addEventListener("typo3-module-load",t),document.addEventListener("typo3-module-loaded",t)}loadModuleComponents(e,t,o){const n=e.name,l=Viewport.ContentContainer.beforeSetUrl(o);return l.then((()=>{e.navigationComponentId?Viewport.NavigationContainer.showComponent(e.navigationComponentId):Viewport.NavigationContainer.hide(),ModuleMenu.highlightModule(n),this.loadedModule=n,t=ModuleMenu.includeId(e,t),this.openInContentContainer(n,e.link,t,new TriggerRequest("typo3.loadModuleComponents",o))})),l}openInContentContainer(e,t,o,n){const l=t+(o?(t.includes("?")?"&":"?")+o:"");return Viewport.ContentContainer.setUrl(l,new TriggerRequest("typo3.openInContentFrame",n),e)}}top.TYPO3.ModuleMenu||(top.TYPO3.ModuleMenu={App:new ModuleMenu});const moduleMenuApp=top.TYPO3.ModuleMenu;export default moduleMenuApp;