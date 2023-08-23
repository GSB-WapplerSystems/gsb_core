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
import Client from"@typo3/backend/storage/client";import"@typo3/backend/input/clearable";import DocumentService from"@typo3/core/document-service";import DebounceEvent from"@typo3/core/event/debounce-event";import RegularEvent from"@typo3/core/event/regular-event";import Mark from"@typo3/backend/contrib/mark";class CollapseStateSearch{constructor(){this.searchValueSelector=".t3js-collapse-search-term",this.searchValue="",this.markInstances=[],DocumentService.ready().then((()=>{if(this.treeContainers=document.querySelectorAll(".t3js-collapse-states-search-tree"),0!==this.treeContainers.length){this.numberOfSearchMatchesContainer=document.querySelectorAll(".t3js-collapse-states-search-numberOfSearchMatches"),this.searchField=document.querySelector(this.searchValueSelector),this.searchForm=this.searchField.closest("form"),this.searchSessionKey=this.searchField.dataset.persistCollapseSearchKey,this.searchValue=Client.get(this.searchSessionKey)??"",this.registerEvents();for(let e=0;e<this.treeContainers.length;e++)this.markInstances[e]=new Mark(this.treeContainers[e]);""!==this.searchValue&&(this.searchField.value=this.searchValue,this.searchField.dispatchEvent(new Event("keyup")),this.searchForm.requestSubmit())}}))}registerEvents(){this.searchField.clearable({onClear:e=>{e.closest("form").requestSubmit()}}),new DebounceEvent("input",(()=>{this.searchForm.requestSubmit()})).bindTo(this.searchField),new RegularEvent("submit",(e=>{e.preventDefault();for(let e=0;e<this.treeContainers.length;e++)this.filterTree(this.searchField.value,this.treeContainers[e],this.numberOfSearchMatchesContainer[e],this.markInstances[e])})).bindTo(this.searchForm)}filterTree(e,t,s,r){if(e=e.toLowerCase(),r.unmark(),Client.set(this.searchSessionKey,e),e.length<3)return void s.classList.add("hidden");const a=new Set,n=[...this.findNodesByIdentifier(e,t),...this.findNodesByValue(e,t),...this.findNodesByComment(e,t),...this.findNodesByConstantSubstitution(e,t)];s.innerText=String(TYPO3.lang["collapse-state-search.numberOfSearchMatches"]).replace("%s",String(n.length)),s.classList.remove("hidden"),n.forEach((e=>{if(null===e)return;const t=e.parentElement.querySelector('[data-bs-toggle="collapse"]')?.dataset.bsTarget;void 0!==t&&a.add(t.substring(1));const s=this.parents(e,".collapse");for(const e of s)a.add(e.id)}));const o=Array.from(t.querySelectorAll(".collapse"));for(const e of o){const t=e.classList.contains("show"),s=e.id;if(a.has(s)){if(!t){const t=document.querySelector('[data-bs-target="#'+s+'"]');t.classList.remove("collapsed"),t.setAttribute("aria-expanded","true"),e.classList.add("show")}}else if(t){const t=document.querySelector('[data-bs-target="#'+s+'"]');t.classList.add("collapsed"),t.setAttribute("aria-expanded","false"),e.classList.remove("show")}}r.mark(e,{element:"strong",className:"text-danger"})}findNodesByIdentifier(e,t){return Array.from(t.querySelectorAll(".treelist-label")).filter((t=>t.textContent.toLowerCase().includes(e)))}findNodesByValue(e,t){return Array.from(t.querySelectorAll(".treelist-value")).filter((t=>t.textContent.toLowerCase().includes(e))).map((e=>e.previousElementSibling))}findNodesByComment(e,t){return Array.from(t.querySelectorAll(".treelist-comment")).filter((t=>t.textContent.toLowerCase().includes(e)))}findNodesByConstantSubstitution(e,t){return Array.from(t.querySelectorAll(".treelist-constant-substitution")).filter((t=>t.textContent.toLowerCase().includes(e)))}parents(e,t){const s=[];let r;for(;null!==(r=e.parentElement.closest(t));)e=r,s.push(r);return s}}export default new CollapseStateSearch;