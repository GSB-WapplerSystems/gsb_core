CKEDITOR.dialog.add( 'customLinkDialog', function( editor ) {
    return {
        title: 'Link Eigenschaften Zeitangabe',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Einstellungen',
                elements: [
                    {
                        type: 'text',
                        id: 'timeStamp',
                        label: 'Zeiteintrag HH:MM:SS',
                        'default': '00:00:00',
                        validate: CKEDITOR.dialog.validate.notEmpty( "Zeitstempel muss gesetzt werden." )
                    }
                ]
            },
        ],

        onOk: function() {
            const dialog = this;
            const selectedText = editor.getSelection().getSelectedText();
            const prependItem = editor.document.createElement('span');
            const customLink = editor.document.createElement('a');

            customLink.setAttributes( {'data-seek': dialog.getValueOf( 'tab-basic', 'timeStamp' ) ,'class': 'external-link jump-link', 'href': '#', 'aria-label':'Springe zur Videozeit: ' + dialog.getValueOf( 'tab-basic', 'timeStamp' ), } );
            prependItem.setAttributes({'class' : 'video-jumplink'});
            // Todo Prevent default on link. + Englisch (doctitle)
            prependItem.setText( dialog.getValueOf( 'tab-basic', 'timeStamp' )+ ' ' );
            customLink.setText( selectedText );

            editor.insertElement( prependItem);
            editor.insertElement(customLink);

        }
    };
});