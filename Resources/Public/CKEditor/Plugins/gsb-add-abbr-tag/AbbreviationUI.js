import {Core, UI} from '@typo3/ckeditor5-bundle.js'
import FormView from './FormView.js'
import {getRangeText} from './Utils.js'

export default class AbbreviationUI extends Core.Plugin {
  static get requires () {
    return [UI.ContextualBalloon]
  }

  init () {
    const editor = this.editor
    this._balloon = this.editor.plugins.get(UI.ContextualBalloon)
    this.formView = this._createFormView()

    editor.ui.componentFactory.add('abbreviation', () => {
      const button = new UI.ButtonView()
      button.label = 'Abkürzung/Lehnwort'
      button.tooltip = true
      button.withText = true

    	this.listenTo(button, 'execute', () => {
        this._showUI()
      })

      return button
    })
  }

  _createFormView () {
    const editor = this.editor
    const formView = new FormView(editor.locale)
    this.listenTo(formView, 'submit', () => {
      const value = {
        abbr: formView.abbrInputView.fieldView.element.value,
        title: formView.titleInputView.fieldView.element.value
      }
      editor.execute('addAbbreviation', value)

      this._hideUI()
    })

    this.listenTo(formView, 'cancel', () => {
      this._hideUI()
    })

    UI.clickOutsideHandler({
      emitter: formView,
      activator: () => this._balloon.visibleView === formView,
      contextElements: [this._balloon.view.element],
      callback: () => this._hideUI()
    })

    formView.keystrokes.set('Esc', (data, cancel) => {
      this._hideUI()
      cancel()
    })

    return formView
  }

  _hideUI () {
    this.formView.abbrInputView.fieldView.value = ''
    this.formView.titleInputView.fieldView.value = ''
    this.formView.element.reset()

    this._balloon.remove(this.formView)

    this.editor.editing.view.focus()
  }

  _getBalloonPositionData () {
    const view = this.editor.editing.view
    const viewDocument = view.document
    let target = null

    target = () => view.domConverter.viewRangeToDom(viewDocument.selection.getFirstRange())

    return {
      target
    }
  }

  _showUI () {
    const selection = this.editor.model.document.selection

    const commandValue = this.editor.commands.get('addAbbreviation').value

    this._balloon.add({
      view: this.formView,
      position: this._getBalloonPositionData()
    })

    this.formView.abbrInputView.isEnabled = selection.getFirstRange().isCollapsed

    if (commandValue) {
      this.formView.abbrInputView.fieldView.value = commandValue.abbr
      this.formView.titleInputView.fieldView.value = commandValue.title
    } else {
      const selectedText = getRangeText(selection.getFirstRange())

      this.formView.abbrInputView.fieldView.value = selectedText
      this.formView.titleInputView.fieldView.value = ''
    }

    this.formView.focus()
  }
}
