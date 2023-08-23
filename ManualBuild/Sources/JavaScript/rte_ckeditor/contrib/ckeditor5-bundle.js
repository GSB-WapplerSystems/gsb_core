import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor.js';
import BlockQuote from '@ckeditor/ckeditor5-block-quote/src/blockquote.js';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials.js';
import FindAndReplace from '@ckeditor/ckeditor5-find-and-replace/src/findandreplace';
import Heading from '@ckeditor/ckeditor5-heading/src/heading.js';
import Indent from '@ckeditor/ckeditor5-indent/src/indent.js';
import Link from '@ckeditor/ckeditor5-link/src/link.js';
import List from '@ckeditor/ckeditor5-list/src/list.js';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph.js';
import PastePlainText from '@ckeditor/ckeditor5-clipboard/src/pasteplaintext.js';
import PasteFromOffice from '@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice.js';
import RemoveFormat from '@ckeditor/ckeditor5-remove-format/src/removeformat.js';
import Table from '@ckeditor/ckeditor5-table/src/table.js';
import TableToolbar from '@ckeditor/ckeditor5-table/src/tabletoolbar.js';
import TableProperties from '@ckeditor/ckeditor5-table/src/tableproperties.js';
import TableCellProperties from '@ckeditor/ckeditor5-table/src/tablecellproperties';
import TextTransformation from '@ckeditor/ckeditor5-typing/src/texttransformation.js';
import SourceEditing from '@ckeditor/ckeditor5-source-editing/src/sourceediting';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import Style from '@ckeditor/ckeditor5-style/src/style';
import GeneralHtmlSupport from '@ckeditor/ckeditor5-html-support/src/generalhtmlsupport';
import { Bold, Italic, Subscript, Superscript, Strikethrough, Underline } from '@ckeditor/ckeditor5-basic-styles/src';
import { SpecialCharacters, SpecialCharactersEssentials } from '@ckeditor/ckeditor5-special-characters/src';
import { HorizontalLine } from '@ckeditor/ckeditor5-horizontal-line/src';
import { WordCount } from '@ckeditor/ckeditor5-word-count/src';
import Autoformat from '@ckeditor/ckeditor5-autoformat/src/autoformat.js';
import CloudServices from '@ckeditor/ckeditor5-cloud-services/src/cloudservices.js';
import DataSchema from '@ckeditor/ckeditor5-html-support/src/dataschema.js';
import IndentBlock from '@ckeditor/ckeditor5-indent/src/indentblock.js';
import PageBreak from '@ckeditor/ckeditor5-page-break/src/pagebreak.js';
import SelectAll from '@ckeditor/ckeditor5-select-all/src/selectall.js';
import ShowBlocks from '@ckeditor/ckeditor5-show-blocks/src/showblocks.js';
import SpecialCharactersArrows from '@ckeditor/ckeditor5-special-characters/src/specialcharactersarrows.js';
import SpecialCharactersCurrency from '@ckeditor/ckeditor5-special-characters/src/specialcharacterscurrency.js';
import SpecialCharactersLatin from '@ckeditor/ckeditor5-special-characters/src/specialcharacterslatin.js';
import SpecialCharactersMathematical from '@ckeditor/ckeditor5-special-characters/src/specialcharactersmathematical.js';
import SpecialCharactersText from '@ckeditor/ckeditor5-special-characters/src/specialcharacterstext.js';
import TableCaption from '@ckeditor/ckeditor5-table/src/tablecaption.js';
import TableColumnResize from '@ckeditor/ckeditor5-table/src/tablecolumnresize.js';
import { TextPartLanguage } from "@ckeditor/ckeditor5-language/src";

export const CKEditor5Plugins = {
  Alignment,
  BlockQuote,
  Bold,
  Essentials,
  FindAndReplace,
  GeneralHtmlSupport,
  Heading,
  HorizontalLine,
  Indent,
  Italic,
  Link,
  List,
  Paragraph,
  PastePlainText,
  PasteFromOffice,
  RemoveFormat,
  SpecialCharacters,
  SpecialCharactersEssentials,
  SourceEditing,
  Style,
  Subscript,
  Superscript,
  Strikethrough,
  Table,
  TableToolbar,
  TableProperties,
  TableCellProperties,
  TextTransformation,
  TextPartLanguage,
  Underline,
  WordCount,
  Autoformat,
  CloudServices,
  DataSchema,
  IndentBlock,
  PageBreak,
  SelectAll,
  ShowBlocks,
  SpecialCharactersArrows,
  SpecialCharactersCurrency,
  SpecialCharactersLatin,
  SpecialCharactersMathematical,
  SpecialCharactersText,
  TableCaption,
  TableColumnResize,
};

export class CKEditor5 extends ClassicEditor {}
CKEditor5.builtinPlugins = Object.values(CKEditor5Plugins);

// Re-Exports
export * as Core from '@ckeditor/ckeditor5-core/src/index.js';
export * as UI from '@ckeditor/ckeditor5-ui/src/index.js';
export * as Engine from '@ckeditor/ckeditor5-engine/src/index.js';
export * as Utils from '@ckeditor/ckeditor5-utils/src/index';
export * as Clipboard from '@ckeditor/ckeditor5-clipboard/src/index.js';
export * as Essentials from '@ckeditor/ckeditor5-essentials/src/index.js';
export * as Link from '@ckeditor/ckeditor5-link/src/index.js';
export * as LinkUtils from '@ckeditor/ckeditor5-link/src/utils.js';
export * as Typing from '@ckeditor/ckeditor5-typing/src/index.js';
export * as Widget from '@ckeditor/ckeditor5-widget/src/index.js';

// Single or prefixed exports
export { default as LinkActionsView } from '@ckeditor/ckeditor5-link/src/ui/linkactionsview';
export { default as WordCount } from '@ckeditor/ckeditor5-word-count/src/wordcount.js';
