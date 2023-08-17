import {Core, Utils} from '@typo3/ckeditor5-bundle.js'
import {findAttributeRange, getRangeText} from './Utils.js'

export default class AbbreviationCommand extends Core.Command {
  refresh () {
    const model = this.editor.model
    const selection = model.document.selection
    const firstRange = selection.getFirstRange()

    if (firstRange.isCollapsed) {
      if (selection.hasAttribute('abbreviation')) {
        const attributeValue = selection.getAttribute('abbreviation')

        const abbreviationRange = findAttributeRange(selection.getFirstPosition(), 'abbreviation', attributeValue, model)

        this.value = {
          abbr: getRangeText(abbreviationRange),
          title: attributeValue,
          range: abbreviationRange
        }
      } else {
        this.value = null
      }
    } else {
      if (selection.hasAttribute('abbreviation')) {
        const attributeValue = selection.getAttribute('abbreviation')

        // Find the entire range containing the abbreviation under the caret position.
        const abbreviationRange = findAttributeRange(selection.getFirstPosition(), 'abbreviation', attributeValue, model)

        if (abbreviationRange.containsRange(firstRange, true)) {
          this.value = {
            abbr: getRangeText(firstRange),
            title: attributeValue,
            range: firstRange
          }
        } else {
          this.value = null
        }
      } else {
        this.value = null
      }
    }

    this.isEnabled = model.schema.checkAttributeInSelection(selection, 'abbreviation')
  }

  execute ({abbr, title}) {
    const model = this.editor.model
    const selection = model.document.selection

    model.change(writer => {
      if (selection.isCollapsed) {
        if (this.value) {
          const {end: positionAfter} = model.insertContent(
            writer.createText(abbr, {abbreviation: title}),
            this.value.range
          )
          writer.setSelection(positionAfter)
        } else if (abbr !== '') {
          const firstPosition = selection.getFirstPosition()

          const attributes = Utils.toMap(selection.getAttributes())

          attributes.set('abbreviation', title)

          const {end: positionAfter} = model.insertContent(writer.createText(abbr, attributes), firstPosition)

          writer.setSelection(positionAfter)
        }

        writer.removeSelectionAttribute('abbreviation')
      } else {
        const ranges = model.schema.getValidRanges(selection.getRanges(), 'abbreviation')

        for (const range of ranges) {
          writer.setAttribute('abbreviation', title, range)
        }
      }
    })
  }
}
