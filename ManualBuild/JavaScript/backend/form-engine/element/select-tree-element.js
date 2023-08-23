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
import"./select-tree";import"./select-tree-toolbar";import"@typo3/backend/element/icon-element";import FormEngine from"@typo3/backend/form-engine";export class SelectTreeElement{constructor(e,t,r,i){if(this.recordField=null,this.tree=null,this.selectNode=e=>{const t=e.detail.node;this.updateAncestorsIndeterminateState(t),this.calculateIndeterminate(this.tree.nodes),this.saveCheckboxes(),this.tree.setup.input.dispatchEvent(new Event("change",{bubbles:!0,cancelable:!0}))},this.loadDataAfter=e=>{this.tree.nodes=e.detail.nodes.map((e=>(e.indeterminate=!1,e))),this.calculateIndeterminate(this.tree.nodes)},this.saveCheckboxes=()=>{void 0!==this.recordField&&(this.recordField.value=this.tree.getSelectedNodes().map((e=>e.identifier)).join(","))},r instanceof Function)throw new Error("Function `callback` is not supported anymore since TYPO3 v12.0");this.recordField=document.getElementById(t);const a=document.getElementById(e);this.tree=document.createElement("typo3-backend-form-selecttree"),this.tree.classList.add("svg-tree-wrapper"),this.tree.addEventListener("typo3:svg-tree:nodes-prepared",this.loadDataAfter),this.tree.addEventListener("typo3:svg-tree:node-selected",this.selectNode),i instanceof Array&&this.tree.addEventListener("typo3:svg-tree:node-selected",(()=>{FormEngine.processOnFieldChange(i)}));const s={id:e,dataUrl:this.generateRequestUrl(),readOnlyMode:1===parseInt(this.recordField.dataset.readOnly,10),input:this.recordField,exclusiveNodesIdentifiers:this.recordField.dataset.treeExclusiveKeys,validation:JSON.parse(this.recordField.dataset.formengineValidationRules)[0],expandUpToLevel:this.recordField.dataset.treeExpandUpToLevel,unselectableElements:[]};this.tree.addEventListener("svg-tree:initialized",(()=>{if(this.recordField.dataset.treeShowToolbar){const e=document.createElement("typo3-backend-form-selecttree-toolbar");e.tree=this.tree,this.tree.prepend(e)}})),this.tree.setup=s,a.append(this.tree),this.listenForVisibleTree()}listenForVisibleTree(){const e=new IntersectionObserver((t=>{t.forEach((t=>{t.isIntersecting&&(this.tree.dispatchEvent(new Event("svg-tree:visible")),e.unobserve(t.target))}))}));e.observe(this.tree)}generateRequestUrl(){const e={tableName:this.recordField.dataset.tablename,fieldName:this.recordField.dataset.fieldname,uid:this.recordField.dataset.uid,defaultValues:this.recordField.dataset.defaultvalues,overrideValues:this.recordField.dataset.overridevalues,recordTypeValue:this.recordField.dataset.recordtypevalue,dataStructureIdentifier:this.recordField.dataset.datastructureidentifier,flexFormSheetName:this.recordField.dataset.flexformsheetname,flexFormFieldName:this.recordField.dataset.flexformfieldname,flexFormContainerName:this.recordField.dataset.flexformcontainername,flexFormContainerIdentifier:this.recordField.dataset.flexformcontaineridentifier,flexFormContainerFieldName:this.recordField.dataset.flexformcontainerfieldname,flexFormSectionContainerIsNew:this.recordField.dataset.flexformsectioncontainerisnew,command:this.recordField.dataset.command};return TYPO3.settings.ajaxUrls.record_tree_data+"&"+new URLSearchParams(e)}updateAncestorsIndeterminateState(e){let t=!1;e.parents.forEach((r=>{const i=this.tree.nodes[r];i.indeterminate=e.checked||e.indeterminate||t,t=i.checked||i.indeterminate||e.checked||e.indeterminate}))}calculateIndeterminate(e){e.forEach((t=>{(t.checked||t.indeterminate)&&t.parents&&t.parents.length>0&&t.parents.forEach((t=>{e[t].indeterminate=!0}))}))}}