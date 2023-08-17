import {Core, Utils} from '@typo3/ckeditor5-bundle.js'
import {findAttributeRange, getRangeText} from './Utils.js'

export default class AbbreviationCommand extends Core.Command {
  refresh () {
    const model = this.editor.model
    const selection = model.document.selection
    const firstRange = selection.getFirstRange()

    if (firstRange.isCollapsed) {
      if (selection.hasAttribute('lang')) {
        const attributeValue = selection.getAttribute('lang')

        const langRange = findAttributeRange(selection.getFirstPosition(), 'lang', attributeValue, model)

        this.value = {
          span: getRangeText(langRange),
          lang: attributeValue,
          range: langRange
        }
      } else {
        this.value = null
      }
    } else {
      if (selection.hasAttribute('lang')) {
        const attributeValue = selection.getAttribute('lang')

        // Find the entire range containing the lang under the caret position.
        const langRange = findAttributeRange(selection.getFirstPosition(), 'lang', attributeValue, model)

        if (langRange.containsRange(firstRange, true)) {
          this.value = {
            span: getRangeText(firstRange),
            lang: attributeValue,
            range: firstRange
          }
        } else {
          this.value = null
        }
      } else {
        this.value = null
      }
    }

    this.isEnabled = model.schema.checkAttributeInSelection(selection, 'lang')
  }

  execute ({span, lang}) {
    const model = this.editor.model
    const selection = model.document.selection

    model.change(writer => {
      if (selection.isCollapsed) {
        if (this.value) {
          const {end: positionAfter} = model.insertContent(
            writer.createText(span, {lang}),
            this.value.range
          )
          writer.setSelection(positionAfter)
        } else if (span !== '') {
          const firstPosition = selection.getFirstPosition()

          const attributes = Utils.toMap(selection.getAttributes())

          attributes.set('lang', lang)

          const {end: positionAfter} = model.insertContent(writer.createText(span, attributes), firstPosition)

          writer.setSelection(positionAfter)
        }

        writer.removeSelectionAttribute('lang')
      } else {
        const ranges = model.schema.getValidRanges(selection.getRanges(), 'lang')

        for (const range of ranges) {
          writer.setAttribute('lang', lang, range)
        }
      }
    })
  }
}
