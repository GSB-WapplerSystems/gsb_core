import {UI, Utils} from '@typo3/ckeditor5-bundle.js'

export default class FormView extends UI.View {
  constructor (locale) {
    super(locale)
    const iconCancel = '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="m11.591 10.177 4.243 4.242a1 1 0 0 1-1.415 1.415l-4.242-4.243-4.243 4.243a1 1 0 0 1-1.414-1.415l4.243-4.242L4.52 5.934A1 1 0 0 1 5.934 4.52l4.243 4.243 4.242-4.243a1 1 0 1 1 1.415 1.414l-4.243 4.243z"/></svg>'
    const iconCheck = '<svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M6.972 16.615a.997.997 0 0 1-.744-.292l-4.596-4.596a1 1 0 1 1 1.414-1.414l3.926 3.926 9.937-9.937a1 1 0 0 1 1.414 1.415L7.717 16.323a.997.997 0 0 1-.745.292z"/></svg>'

    this.focusTracker = new Utils.FocusTracker()
    this.keystrokes = new Utils.KeystrokeHandler()

    this.spanInputView = this._createInput('Fremdsprache hinzufügen')
    this.langInputView = this._createInput('Sprachauszeichnung hinzufügen')

    this.saveButtonView = this._createButton('Speichern', iconCheck, 'ck-button-save')

    this.saveButtonView.type = 'submit'

    this.cancelButtonView = this._createButton('Abbrechen', iconCancel, 'ck-button-cancel')

    this.saveButtonView = this._createButton('Speichern', iconCheck, 'ck-button-save')
    this.saveButtonView.type = 'submit'
    this.cancelButtonView = this._createButton('Abbrechen', iconCancel, 'ck-button-cancel')
    this.cancelButtonView.delegate('execute').to(this, 'cancel')

    this.childViews = this.createCollection([
      this.spanInputView,
      this.langInputView,
      this.saveButtonView,
      this.cancelButtonView
    ])

    this._focusCycler = new UI.FocusCycler({
      focusables: this.childViews,
      focusTracker: this.focusTracker,
      keystrokeHandler: this.keystrokes,
      actions: {
        focusPrevious: 'shift + tab',

        focusNext: 'tab'
      }
    })

    this.setTemplate({
      tag: 'form',
      attributes: {
        class: ['ck', 'ck-lang-form'],
        tabindex: '-1'
      },
      children: this.childViews
    })
  }

  render () {
    super.render()
    UI.submitHandler({
      view: this
    })

    this.childViews._items.forEach(view => {
      this.focusTracker.add(view.element)
    })
    this.keystrokes.listenTo(this.element)
  }

  focus () {
    if (this.langInputView.isEnabled) {
      this.spanInputView.focus()
    } else {
      this.langInputView.focus()
    }
  }

  _createInput (label) {
    const labeledInput = new UI.LabeledFieldView(this.locale, UI.createLabeledInputText)

    labeledInput.label = label

    return labeledInput
  }

  _createButton (label, icon, className) {
    const button = new UI.ButtonView()

    button.set({
      label,
      icon,
      tooltip: true,
      class: className
    })

    return button
  }

  destroy () {
    super.destroy()

    this.focusTracker.destroy()
    this.keystrokes.destroy()
  }
}
