import LiveSearchConfigurator from '@typo3/backend/live-search/live-search-configurator';
import { html, TemplateResult } from 'lit';
import { ResultItemActionInterface, ResultItemInterface } from '@typo3/backend/live-search/element/result/item/item';
import windowManager from '@typo3/backend/window-manager';

export function registerRenderer(type: string) {
  LiveSearchConfigurator.addRenderer(
    type,
    '@typo3/backend/live-search/element/provider/page-provider-result-item.js',
    (attributes: ResultItemInterface): TemplateResult => {
      return html`<typo3-backend-live-search-result-item-page-provider
        .icon="${attributes.icon}"
        .itemTitle="${attributes.itemTitle}"
        .typeLabel="${attributes.typeLabel}"
        .extraData="${attributes.extraData}">
      </typo3-backend-live-search-result-item-page-provider>`;
    }
  );

  LiveSearchConfigurator.addInvokeHandler(type, 'preview_page', (resultItem: ResultItemInterface, action: ResultItemActionInterface): void => {
    windowManager.localOpen(action.url, true);
  });
}
