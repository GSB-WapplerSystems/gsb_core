import {Core} from '@typo3/ckeditor5-bundle.js'
import LangCommand from './LangCommand.js'

export default class LangEditing extends Core.Plugin {
  init () {
    this._defineSchema()
    this._defineConverters()
    this.editor.commands.add('addLang', new LangCommand(this.editor))
  }

  _defineSchema () {
    const schema = this.editor.model.schema
    schema.extend('$text', {
      allowAttributes: ['lang']
    })
    schema.extend('$inlineObject', {
      allowAttributes: ['lang']
    })

    schema.extend('$blockObject', {
      allowAttributes: ['lang']
    })

    schema.extend('$block', {
      allowAttributes: ['lang']
    })
  }

  _defineConverters () {
    const conversion = this.editor.conversion

    conversion.for('downcast').attributeToElement({
      model: {
        key: 'lang',
        attributes: ['lang']
      },

      view: (modelAttributeValue, conversionApi) => {
        const {writer} = conversionApi

        return writer.createAttributeElement('span', {
          lang: modelAttributeValue
        })
      }
    })
    conversion.for('upcast').elementToAttribute({
      view: {
        name: 'span',
        attributes: ['lang']
      },
      model: {
        key: 'lang',
        value: viewElement => {
          const lang = viewElement.getAttribute('lang')

          return lang
        }
      }
    })
  }
}
