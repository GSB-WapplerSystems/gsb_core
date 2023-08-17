import { Core, UI, Utils } from '@typo3/ckeditor5-bundle.js';



const ITALIC$1 = 'italic';
const italicIcon = "<svg viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"m9.586 14.633.021.004c-.036.335.095.655.393.962.082.083.173.15.274.201h1.474a.6.6 0 1 1 0 1.2H5.304a.6.6 0 0 1 0-1.2h1.15c.474-.07.809-.182 1.005-.334.157-.122.291-.32.404-.597l2.416-9.55a1.053 1.053 0 0 0-.281-.823 1.12 1.12 0 0 0-.442-.296H8.15a.6.6 0 0 1 0-1.2h6.443a.6.6 0 1 1 0 1.2h-1.195c-.376.056-.65.155-.823.296-.215.175-.423.439-.623.79l-2.366 9.347z\"/></svg>";
// Replace i Tags with em
export default class GSBAdjustments extends Core.Plugin {
  static pluginName = 'GSBAdjustments';


  init() {
    const editor = this.editor;

    editor.model.schema.extend('$text', { allowAttributes: ITALIC$1 });
    editor.model.schema.setAttributeProperties(ITALIC$1, {
        isFormatting: true,
        copyOnEnter: true
    });
    editor.conversion.attributeToElement({
        model: ITALIC$1,
        view: 'em',
        converterPriority: 'high',

        upcastAlso: [
            'i',
            {
                styles: {
                    'font-style': 'italic'
                }
            }
        ]
    });
      editor.commands.add(ITALIC$1, new AttributeCommand(editor, ITALIC$1));


    // Set the Ctrl+I keystroke.
    editor.keystrokes.set('CTRL+I', ITALIC$1);
    const t = editor.t;
    // Add bold button to feature components.
    editor.ui.componentFactory.add(ITALIC$1, locale => {
        const command = editor.commands.get(ITALIC$1);
        const view = new UI.ButtonView();
        view.set({
            label: t('Italic'),
            icon: italicIcon,
            keystroke: 'CTRL+I',
            tooltip: true,
            isToggleable: true,
        });

        view.bind('isOn', 'isEnabled').to(command, 'value', 'isEnabled');

        // Execute command.
        this.listenTo(view, 'execute', () => {
            editor.execute(ITALIC$1);
            editor.editing.view.focus();
        });
        return view;
    });
  
  }

}

class Command extends Utils.ObservableMixin() {
  /**
   * Creates a new `Command` instance.
   *
   * @param editor The editor on which this command will be used.
   */
  constructor(editor) {
      super();
      this.editor = editor;
      this.set('value', undefined);
      this.set('isEnabled', false);
      this._affectsData = true;
      this._disableStack = new Set();
      this.decorate('execute');
      // By default every command is refreshed when changes are applied to the model.
      this.listenTo(this.editor.model.document, 'change', () => {
          this.refresh();
      });
      this.on('execute', evt => {
          if (!this.isEnabled) {
              evt.stop();
          }
      }, { priority: 'high' });
      // By default commands are disabled when the editor is in read-only mode.
      this.listenTo(editor, 'change:isReadOnly', (evt, name, value) => {
          if (value && this.affectsData) {
              this.forceDisabled('gsb');
          }
          else {
              this.clearForceDisabled('gsb');
          }
      });
  }
  /**
   * A flag indicating whether a command execution changes the editor data or not.
   *
   * Commands with `affectsData` set to `false` will not be automatically disabled in
   * the {@link module:core/editor/editor~Editor#isReadOnly read-only mode} and
   * {@glink features/read-only#related-features other editor modes} with restricted user write permissions.
   *
   * **Note:** You do not have to set it for your every command. It is `true` by default.
   *
   * @default true
   */
  get affectsData() {
      return this._affectsData;
  }
  set affectsData(affectsData) {
      this._affectsData = affectsData;
  }
  /**
   * Refreshes the command. The command should update its {@link #isEnabled} and {@link #value} properties
   * in this method.
   *
   * This method is automatically called when
   * {@link module:engine/model/document~Document#event:change any changes are applied to the document}.
   */
  refresh() {
      this.isEnabled = true;
  }
  /**
   * Disables the command.
   *
   * Command may be disabled by multiple features or algorithms (at once). When disabling a command, unique id should be passed
   * (e.g. the feature name). The same identifier should be used when {@link #clearForceDisabled enabling back} the command.
   * The command becomes enabled only after all features {@link #clearForceDisabled enabled it back}.
   *
   * Disabling and enabling a command:
   *
   * ```ts
   * command.isEnabled; // -> true
   * command.forceDisabled( 'MyFeature' );
   * command.isEnabled; // -> false
   * command.clearForceDisabled( 'MyFeature' );
   * command.isEnabled; // -> true
   * ```
   *
   * Command disabled by multiple features:
   *
   * ```ts
   * command.forceDisabled( 'MyFeature' );
   * command.forceDisabled( 'OtherFeature' );
   * command.clearForceDisabled( 'MyFeature' );
   * command.isEnabled; // -> false
   * command.clearForceDisabled( 'OtherFeature' );
   * command.isEnabled; // -> true
   * ```
   *
   * Multiple disabling with the same identifier is redundant:
   *
   * ```ts
   * command.forceDisabled( 'MyFeature' );
   * command.forceDisabled( 'MyFeature' );
   * command.clearForceDisabled( 'MyFeature' );
   * command.isEnabled; // -> true
   * ```
   *
   * **Note:** some commands or algorithms may have more complex logic when it comes to enabling or disabling certain commands,
   * so the command might be still disabled after {@link #clearForceDisabled} was used.
   *
   * @param id Unique identifier for disabling. Use the same id when {@link #clearForceDisabled enabling back} the command.
   */
  forceDisabled(id) {
      this._disableStack.add(id);
      if (this._disableStack.size == 1) {
          this.on('set:isEnabled', forceDisable, { priority: 'highest' });
          this.isEnabled = false;
      }
  }
  /**
   * Clears forced disable previously set through {@link #forceDisabled}. See {@link #forceDisabled}.
   *
   * @param id Unique identifier, equal to the one passed in {@link #forceDisabled} call.
   */
  clearForceDisabled(id) {
      this._disableStack.delete(id);
      if (this._disableStack.size == 0) {
          this.off('set:isEnabled', forceDisable);
          this.refresh();
      }
  }
  /**
   * Executes the command.
   *
   * A command may accept parameters. They will be passed from {@link module:core/editor/editor~Editor#execute `editor.execute()`}
   * to the command.
   *
   * The `execute()` method will automatically abort when the command is disabled ({@link #isEnabled} is `false`).
   * This behavior is implemented by a high priority listener to the {@link #event:execute} event.
   *
   * In order to see how to disable a command from "outside" see the {@link #isEnabled} documentation.
   *
   * This method may return a value, which would be forwarded all the way down to the
   * {@link module:core/editor/editor~Editor#execute `editor.execute()`}.
   *
   * @fires execute
   */
  execute(...args) { return undefined; }
  /**
   * Destroys the command.
   */
  destroy() {
      this.stopListening();
  }
} 
class AttributeCommand extends Command {
  /**
   * @param attributeKey Attribute that will be set by the command.
   */
  constructor(editor, attributeKey) {
      super(editor);
      this.attributeKey = attributeKey;
  }
  /**
   * Updates the command's {@link #value} and {@link #isEnabled} based on the current selection.
   */
  refresh() {
      const model = this.editor.model;
      const doc = model.document;
      this.value = this._getValueFromFirstAllowedNode();
      this.isEnabled = model.schema.checkAttributeInSelection(doc.selection, this.attributeKey);
  }
  /**
   * Executes the command &mdash; applies the attribute to the selection or removes it from the selection.
   *
   * If the command is active (`value == true`), it will remove attributes. Otherwise, it will set attributes.
   *
   * The execution result differs, depending on the {@link module:engine/model/document~Document#selection}:
   *
   * * If the selection is on a range, the command applies the attribute to all nodes in that range
   * (if they are allowed to have this attribute by the {@link module:engine/model/schema~Schema schema}).
   * * If the selection is collapsed in a non-empty node, the command applies the attribute to the
   * {@link module:engine/model/document~Document#selection} itself (note that typed characters copy attributes from the selection).
   * * If the selection is collapsed in an empty node, the command applies the attribute to the parent node of the selection (note
   * that the selection inherits all attributes from a node if it is in an empty node).
   *
   * @fires execute
   * @param options Command options.
   * @param options.forceValue If set, it will force the command behavior. If `true`,
   * the command will apply the attribute, otherwise the command will remove the attribute.
   * If not set, the command will look for its current value to decide what it should do.
   */
  execute(options = {}) {
      const model = this.editor.model;
      const doc = model.document;
      const selection = doc.selection;
      const value = (options.forceValue === undefined) ? !this.value : options.forceValue;
      model.change(writer => {
          if (selection.isCollapsed) {
              if (value) {
                  writer.setSelectionAttribute(this.attributeKey, true);
              }
              else {
                  writer.removeSelectionAttribute(this.attributeKey);
              }
          }
          else {
              const ranges = model.schema.getValidRanges(selection.getRanges(), this.attributeKey);
              for (const range of ranges) {
                  if (value) {
                      writer.setAttribute(this.attributeKey, value, range);
                  }
                  else {
                      writer.removeAttribute(this.attributeKey, range);
                  }
              }
          }
      });
  }
  /**
   * Checks the attribute value of the first node in the selection that allows the attribute.
   * For the collapsed selection returns the selection attribute.
   *
   * @returns The attribute value.
   */
  _getValueFromFirstAllowedNode() {
      const model = this.editor.model;
      const schema = model.schema;
      const selection = model.document.selection;
      if (selection.isCollapsed) {
          return selection.hasAttribute(this.attributeKey);
      }
      for (const range of selection.getRanges()) {
          for (const item of range.getItems()) {
              if (schema.checkAttribute(item, this.attributeKey)) {
                  return item.hasAttribute(this.attributeKey);
              }
          }
      }
      return false;
  }
}

/**
 * Helper function that forces command to be disabled.
 */
function forceDisable(evt) {
    evt.return = false;
    evt.stop();
}