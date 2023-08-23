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
import"@typo3/backend/input/clearable";import DateTimePicker from"@typo3/backend/date-time-picker";import RegularEvent from"@typo3/core/event/regular-event";class QueryGenerator{constructor(){this.form=null,this.limitField=null,this.form=document.querySelector('form[name="queryform"]'),this.limitField=document.querySelector("input#queryLimit"),new RegularEvent("click",(e=>{e.preventDefault(),this.doSubmit()})).delegateTo(this.form,".t3js-submit-click"),new RegularEvent("change",(e=>{e.preventDefault(),this.doSubmit()})).delegateTo(this.form,".t3js-submit-change"),new RegularEvent("click",((e,t)=>{e.preventDefault(),this.setLimit(t.value),this.doSubmit()})).delegateTo(this.form,'.t3js-limit-submit input[type="button"]'),new RegularEvent("click",((e,t)=>{e.preventDefault(),this.addValueToField(t.dataset.field,t.value)})).delegateTo(this.form,".t3js-addfield"),new RegularEvent("change",((e,t)=>{const i=this.form.querySelector('input[name="storeControl[title]"]');"0"!==t.value?i.value=t.querySelector("option:selected").textContent:i.value=""})).delegateTo(this.form,"select.t3js-addfield"),document.querySelectorAll('form[name="queryform"] .t3js-clearable').forEach((e=>e.clearable({onClear:()=>{this.doSubmit()}}))),document.querySelectorAll('form[name="queryform"] .t3js-datetimepicker').forEach((e=>DateTimePicker.initialize(e)))}doSubmit(){this.form.submit()}setLimit(e){this.limitField.value=e}addValueToField(e,t){const i=this.form.querySelector('[name="'+e+'"]');t=i.value+","+t,i.value=t.split(",").map((e=>e.trim())).filter(((e,t,i)=>i.indexOf(e)===t)).join(",")}}export default new QueryGenerator;