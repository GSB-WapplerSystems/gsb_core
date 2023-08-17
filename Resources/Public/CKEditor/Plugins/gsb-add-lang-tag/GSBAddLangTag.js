import {Core} from '@typo3/ckeditor5-bundle.js'
import FormView from './FormView.js'
import LangUI from './LangUI.js'
import LangEditing from './LangEditing.js'

export default class Lang extends Core.Plugin {
  static get requires () {
    return [LangEditing, LangUI, FormView]
  }
}
