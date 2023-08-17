import {Core} from '@typo3/ckeditor5-bundle.js'
import AbbreviationCommand from './AbbreviationCommand.js'

export default class AbbreviationEditing extends Core.Plugin {
  init () {
    this._defineSchema()
    this._defineConverters()
    this.editor.commands.add(
      'addAbbreviation', new AbbreviationCommand(this.editor)
    )
  }

  _defineSchema () {
    const schema = this.editor.model.schema
    schema.extend('$text', {
      allowAttributes: ['abbreviation']
    })
    this.editor.model.schema.setAttributeProperties('abbreviation', {
      isFormatting: true
    })
  }

  _defineConverters () {
    const conversion = this.editor.conversion

    conversion.for('downcast').attributeToElement({
      model: 'abbreviation',

      view: (modelAttributeValue, conversionApi) => {
        const {writer} = conversionApi

        return writer.createAttributeElement('abbr', {
          title: modelAttributeValue
        })
      }
    })
    conversion.for('upcast').elementToAttribute({
      view: {
        name: 'abbr',
        attributes: ['title']
      },
      model: {
        key: 'abbreviation',
        value: viewElement => {
          const title = viewElement.getAttribute('title')

          return title
        }
      }
    })
  }
}
