import{Command as e,Plugin as t}from"@ckeditor/ckeditor5-core";import{getLanguageDirection as a,Collection as n}from"@ckeditor/ckeditor5-utils";import{ViewModel as i,createDropdown as o,addListToDropdown as r}from"@ckeditor/ckeditor5-ui";
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */function g(e,t){return`${e}:${t=t||a(e)}`}
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
class l extends e{refresh(){const e=this.editor.model,t=e.document;this.value=this._getValueFromFirstAllowedNode(),this.isEnabled=e.schema.checkAttributeInSelection(t.selection,"language")}execute({languageCode:e,textDirection:t}={}){const a=this.editor.model,n=a.document.selection,i=!!e&&g(e,t);a.change((e=>{if(n.isCollapsed)i?e.setSelectionAttribute("language",i):e.removeSelectionAttribute("language");else{const t=a.schema.getValidRanges(n.getRanges(),"language");for(const a of t)i?e.setAttribute("language",i,a):e.removeAttribute("language",a)}}))}_getValueFromFirstAllowedNode(){const e=this.editor.model,t=e.schema,a=e.document.selection;if(a.isCollapsed)return a.getAttribute("language")||!1;for(const e of a.getRanges())for(const a of e.getItems())if(t.checkAttribute(a,"language"))return a.getAttribute("language")||!1;return!1}}
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */class s extends t{static get pluginName(){return"TextPartLanguageEditing"}constructor(e){super(e),e.config.define("language",{textPartLanguage:[{title:"Arabic",languageCode:"ar"},{title:"French",languageCode:"fr"},{title:"Spanish",languageCode:"es"}]})}init(){const e=this.editor;e.model.schema.extend("$text",{allowAttributes:"language"}),e.model.schema.setAttributeProperties("language",{copyOnEnter:!0}),this._defineConverters(),e.commands.add("textPartLanguage",new l(e))}_defineConverters(){const e=this.editor.conversion;e.for("upcast").elementToAttribute({model:{key:"language",value:e=>g(e.getAttribute("lang"),e.getAttribute("dir"))},view:{name:"span",attributes:{lang:/[\s\S]+/}}}),e.for("downcast").attributeToElement({model:"language",view:(e,{writer:t},a)=>{if(!e)return;if(!a.item.is("$textProxy")&&!a.item.is("documentSelection"))return;const{languageCode:n,textDirection:i}=function(e){const[t,a]=e.split(":");return{languageCode:t,textDirection:a}}(e);return t.createAttributeElement("span",{lang:n,dir:i})}})}}
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */class u extends t{static get pluginName(){return"TextPartLanguageUI"}init(){const e=this.editor,t=e.t,a=e.config.get("language.textPartLanguage"),l=t("Choose language"),s=t("Remove language"),u=t("Language");e.ui.componentFactory.add("textPartLanguage",(t=>{const c=new n,d={},m=e.commands.get("textPartLanguage");c.add({type:"button",model:new i({label:s,languageCode:!1,withText:!0})}),c.add({type:"separator"});for(const e of a){const t={type:"button",model:new i({label:e.title,languageCode:e.languageCode,role:"menuitemradio",textDirection:e.textDirection,withText:!0})},a=g(e.languageCode,e.textDirection);t.model.bind("isOn").to(m,"value",(e=>e===a)),c.add(t),d[a]=e.title}const b=o(t);return r(b,c,{ariaLabel:u,role:"menu"}),b.buttonView.set({ariaLabel:u,ariaLabelledBy:void 0,isOn:!1,withText:!0,tooltip:u}),b.extendTemplate({attributes:{class:["ck-text-fragment-language-dropdown"]}}),b.bind("isEnabled").to(m,"isEnabled"),b.buttonView.bind("label").to(m,"value",(e=>e&&d[e]||l)),this.listenTo(b,"execute",(t=>{m.execute({languageCode:t.source.languageCode,textDirection:t.source.textDirection}),e.editing.view.focus()})),b}))}}
/**
 * @license Copyright (c) 2003-2024, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */class c extends t{static get requires(){return[s,u]}static get pluginName(){return"TextPartLanguage"}}export{c as TextPartLanguage,s as TextPartLanguageEditing,u as TextPartLanguageUI};
