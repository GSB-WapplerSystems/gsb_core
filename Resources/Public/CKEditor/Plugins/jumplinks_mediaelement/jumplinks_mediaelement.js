(function() {
    'use strict';

    let $;
    require(['jquery'], function (jquery) {
        $ = jquery;
    });

    CKEDITOR.plugins.add('jumplinks_mediaelement', {
        icons: 'JumpLink',
        allowedContent: 'button[data-seek]',
        init: function( editor ) {

            editor.addCommand( 'jumplink', new CKEDITOR.dialogCommand( 'customLinkDialog' ), {

                exec: function( editor ) {

                    const selection = editor.getSelection().getSelectedText();

                    const parsedData = editor.getData();

                    editor.setData( parsedData );
                }
            });
            editor.ui.addButton( 'JumpLink', {
                label: 'Sprungmarke für Videos',
                command: 'jumplink',
                toolbar: 'insert,60',
                icon: this.path + 'icons/jumplinks_mediaelement.png',
            });
            CKEDITOR.dialog.add( 'customLinkDialog', this.path + 'dialogs/customLink.js' );
        }
    });
}());