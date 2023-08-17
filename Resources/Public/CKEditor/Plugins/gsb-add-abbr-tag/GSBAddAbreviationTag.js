import {Core} from '@typo3/ckeditor5-bundle.js'
import FormView from './FormView.js'
import AbbreviationUI from './AbbreviationUI.js'
import AbbreviationEditing from './AbbreviationEditing.js'

export default class Abbreviation extends Core.Plugin {
  static get requires () {
    return [AbbreviationEditing, AbbreviationUI, FormView]
  }
}
