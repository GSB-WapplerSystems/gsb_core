import"@typo3/backend/element/icon-element.js";import Modal from"@typo3/backend/modal.js";import{BaseElement}from"@typo3/backend/settings/type/base.js";import{MessageUtility}from"@typo3/backend/utility/message-utility.js";import{html,nothing}from"lit";import{customElement,property}from"lit/decorators.js";var __decorate=function(e,t,n,o){var i,r=arguments.length,s=r<3?t:null===o?o=Object.getOwnPropertyDescriptor(t,n):o;if("object"==typeof Reflect&&"function"==typeof Reflect.decorate)s=Reflect.decorate(e,t,n,o);else for(var a=e.length-1;a>=0;a--)(i=e[a])&&(s=(r<3?i(s):r>3?i(t,n,s):i(t,n))||s);return r>3&&s&&Object.defineProperty(t,n,s),s};const componentName="typo3-backend-settings-type-file";let FileTypeElement=class extends BaseElement{constructor(){super(...arguments),this.elementBrowserListener=e=>{if(!MessageUtility.verifyOrigin(e.origin))throw"Denied message sent by "+e.origin;if("typo3:elementBrowser:elementAdded"===e.data.actionName){if(void 0===e.data.fieldName)throw"fieldName not defined in message";if(void 0===e.data.value)throw"value not defined in message";this.value=e.data.value.split("_").pop()}}}render(){return html`
            <div class="input-grouped">
                <input
                    type="number"
                    id="${this.formid}"
                    class="form-control"
                    ?readonly="${this.readonly}"
                    .value="${this.value}"
                    @change="${e=>this.value=parseInt(e.target.value,10)}"
                >
                ${this.canUseElementBrowser()?html`
                    <button
                        type="button"
                        class="btn btn-default"
                        @click="${()=>this.openElementBrowser()}"
                    >
                        <typo3-backend-icon
                            identifier="actions-file-image"
                            size="small"
                        ></typo3-backend-icon>
                        "Select file"
                    </button>
                `:nothing}
            </div>
        `}canUseElementBrowser(){return void 0!==top.TYPO3.settings?.Wizards?.elementBrowserUrl}openElementBrowser(){const e=this.formid+"|||*",t=Modal.advanced({type:Modal.types.iframe,content:top.TYPO3.settings.Wizards.elementBrowserUrl+"&mode=file&bparams="+e,size:Modal.sizes.large});window.addEventListener("message",this.elementBrowserListener),t.addEventListener("typo3-modal-hide",()=>{window.removeEventListener("message",this.elementBrowserListener)})}};__decorate([property({type:Number})],FileTypeElement.prototype,"value",void 0),FileTypeElement=__decorate([customElement(componentName)],FileTypeElement);export{componentName,FileTypeElement};