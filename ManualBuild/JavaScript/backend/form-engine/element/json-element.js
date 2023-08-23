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
import{Resizable}from"./modifier/resizable";import{Tabbable}from"./modifier/tabbable";class JsonElement extends HTMLElement{constructor(){super(...arguments),this.element=null}connectedCallback(){this.element=document.getElementById(this.getAttribute("recordFieldId")||""),this.element&&(Resizable.enable(this.element),Tabbable.enable(this.element))}}window.customElements.define("typo3-formengine-element-json",JsonElement);