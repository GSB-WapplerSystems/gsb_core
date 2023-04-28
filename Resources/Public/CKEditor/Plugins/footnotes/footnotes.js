(function() {
    'use strict';

    var $;
    require(['jquery'], function (jquery) {
        $ = jquery;
    });

    CKEDITOR.plugins.add('footnotes', {
        icons: 'Fußnoten',
        allowedContent: 'button[data-jump]',
        init: function( editor ) {

            editor.addCommand( 'footnote', new CKEDITOR.dialogCommand( 'customFootnoteDialog' ), {

                exec: function( editor ) {

                    let selection = editor.getSelection().getSelectedText();

                    let parsedData = editor.getData();

                    editor.setData( parsedData );
                }
            });
            editor.ui.addButton( 'footnote', {
                label: 'Fußnote hinzufügen',
                command: 'footnote',
                toolbar: 'insert,60',
                icon: this.path + 'icons/asterisk-solid.svg',
            });
            CKEDITOR.dialog.add( 'customFootnoteDialog', this.path + 'dialogs/footnote.js');
        }
    });
}());