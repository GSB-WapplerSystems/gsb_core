import "@typo3/backend/element/icon-element.js";
import Modal from "@typo3/backend/modal.js";
import { BaseElement } from "@typo3/backend/settings/type/base.js";
import { MessageUtility } from "@typo3/backend/utility/message-utility.js";
import { html, nothing } from "lit";
import { customElement, property } from "lit/decorators.js";

// Decorator helper function
var __decorate = function (decorators, target, key, desc) {
    var c = arguments.length,
        r = c < 3 ? target : desc === null ? desc = Object.getOwnPropertyDescriptor(target, key) : desc,
        d;
    if (typeof Reflect === "object" && typeof Reflect.decorate === "function") {
        r = Reflect.decorate(decorators, target, key, desc);
    } else {
        for (var i = decorators.length - 1; i >= 0; i--) {
            d = decorators[i];
            if (d) {
                r = (c < 3 ? d(r) : c > 3 ? d(target, key, r) : d(target, key)) || r;
            }
        }
    }
    return c > 3 && r && Object.defineProperty(target, key, r), r;
};

const componentName = "typo3-backend-settings-type-file";

let FileTypeElement = class extends BaseElement {
    constructor() {
        super(...arguments);

        this.elementBrowserListener = (event) => {
            if (!MessageUtility.verifyOrigin(event.origin)) {
                throw "Denied message sent by " + event.origin;
            }

            if (event.data.actionName === "typo3:elementBrowser:elementAdded") {
                if (typeof event.data.fieldName === "undefined") {
                    throw "fieldName not defined in message";
                }
                if (typeof event.data.value === "undefined") {
                    throw "value not defined in message";
                }

                this.value = event.data.value.split("_").pop();
            }
        };
    }

    render() {
        return html`
            <div class="input-grouped">
                <input
                    type="number"
                    id="${this.formid}"
                    class="form-control"
                    ?readonly="${this.readonly}"
                    .value="${this.value}"
                    @change="${(event) => this.value = parseInt(event.target.value, 10)}"
                >
                ${this.canUseElementBrowser() ? html`
                    <button
                        type="button"
                        class="btn btn-default"
                        @click="${() => this.openElementBrowser()}"
                    >
                        <typo3-backend-icon
                            identifier="actions-file-image"
                            size="small"
                        ></typo3-backend-icon>
                        "Select file"
                    </button>
                ` : nothing}
            </div>
        `;
    }

    canUseElementBrowser() {
        return top.TYPO3.settings?.Wizards?.elementBrowserUrl !== undefined;
    }

    openElementBrowser() {
        const mode = "file";
        const bparams = this.formid + "|||*";
        const modal = Modal.advanced({
            type: Modal.types.iframe,
            content: top.TYPO3.settings.Wizards.elementBrowserUrl + "&mode=" + mode + "&bparams=" + bparams,
            size: Modal.sizes.large
        });

        window.addEventListener("message", this.elementBrowserListener);
        modal.addEventListener("typo3-modal-hide", () => {
            window.removeEventListener("message", this.elementBrowserListener);
        });
    }
};

__decorate([
    property({ type: Number })
], FileTypeElement.prototype, "value", void 0);

FileTypeElement = __decorate([
    customElement(componentName)
], FileTypeElement);

export { componentName, FileTypeElement };
