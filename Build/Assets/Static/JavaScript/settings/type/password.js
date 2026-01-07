import { BaseElement } from "@typo3/backend/settings/type/base.js";
import { html } from "lit";
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

const componentName = "typo3-backend-settings-type-password";
const PASSWORD_PREFIX = "###ENCRYPTED###";
const MASKED_PASSWORD = "********";

let PasswordTypeElement = class extends BaseElement {
    constructor() {
        super(...arguments);
        this.originalEncryptedValue = null;
        this.hasUserInput = false;
        this.displayValue = '';
    }

    connectedCallback() {
        super.connectedCallback();
        // Store the original encrypted value and keep it in this.value
        if (this.value && typeof this.value === 'string' && this.value.startsWith(PASSWORD_PREFIX)) {
            this.originalEncryptedValue = this.value;
            // Keep the encrypted value in this.value, only change display
            this.displayValue = MASKED_PASSWORD;
            // Don't clear this.value - keep the encrypted value!
        } else if (this.value && this.value !== '') {
            // If there's a non-encrypted value, show masked password
            this.displayValue = MASKED_PASSWORD;
            this.originalEncryptedValue = this.value;
        }
    }

    render() {
        return html`
            <div class="input-grouped">
                <input
                    type="password"
                    id="${this.formid}"
                    class="form-control"
                    ?readonly="${this.readonly}"
                    .value="${this.displayValue}"
                    @input="${(event) => this.handleInput(event)}"
                    @change="${(event) => this.handleChange(event)}"
                    @focus="${() => this.handleFocus()}"
                    @blur="${() => this.handleBlur()}"
                >
            </div>
        `;
    }

    handleFocus() {
        // Clear the display value when user focuses on the field to allow normal input
        // But keep this.value as encrypted value until user actually types something
        if (this.displayValue === MASKED_PASSWORD) {
            this.displayValue = '';
            this.requestUpdate();
        }
    }

    handleBlur() {
        // When user leaves the field, show 8 stars if there's a value
        const input = this.shadowRoot?.querySelector('input');
        if (input && input.value !== '' && this.hasUserInput) {
            // User entered something - show masked password
            this.displayValue = MASKED_PASSWORD;
            this.requestUpdate();
        } else if (input && input.value === '' && !this.hasUserInput && this.originalEncryptedValue) {
            // Field is empty and user didn't input anything - restore encrypted value
            this.value = this.originalEncryptedValue;
            this.displayValue = MASKED_PASSWORD;
            this.requestUpdate();
        } else if (input && input.value === '' && this.hasUserInput && this.originalEncryptedValue) {
            // User cleared the field after entering something - keep encrypted value
            this.value = this.originalEncryptedValue;
            this.displayValue = MASKED_PASSWORD;
            this.hasUserInput = false; // Reset flag since user cleared it
            this.requestUpdate();
        }
    }

    handleInput(event) {
        // Mark that the user has entered something
        this.hasUserInput = true;
        const newValue = event.target.value;
        // During input, use the actual value so user can see normal password masking
        this.displayValue = newValue;
        if (newValue !== '') {
            // User is typing a new password - store it
            this.value = newValue;
        } else {
            // If the field was cleared by user, check if we should restore encrypted value
            if (this.originalEncryptedValue) {
                // Restore encrypted value if user clears the field
                this.value = this.originalEncryptedValue;
            } else {
                this.value = '';
            }
        }
        this.requestUpdate();
    }

    handleChange(event) {
        const newValue = event.target.value;
        if (newValue === '' && !this.hasUserInput && this.originalEncryptedValue) {
            // If the field is empty and the user has not entered anything, keep the encrypted value
            this.value = this.originalEncryptedValue;
            this.displayValue = MASKED_PASSWORD;
        } else if (newValue === '' && this.hasUserInput && this.originalEncryptedValue) {
            // User cleared the field after entering something - restore encrypted value
            this.value = this.originalEncryptedValue;
            this.displayValue = MASKED_PASSWORD;
            this.hasUserInput = false; // Reset flag
        } else if (newValue === '') {
            // If the field is empty and no encrypted value exists, set to empty
            this.value = '';
            this.displayValue = '';
        } else {
            // New password was entered, show 8 stars after input is complete
            this.value = newValue;
            this.displayValue = MASKED_PASSWORD;
        }
        this.requestUpdate();
    }

};

__decorate([
    property({ type: String })
], PasswordTypeElement.prototype, "value", void 0);

PasswordTypeElement = __decorate([
    customElement(componentName)
], PasswordTypeElement);

export { componentName, PasswordTypeElement };

