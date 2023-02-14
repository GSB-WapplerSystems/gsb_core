CKEDITOR.dialog.add( 'customFootnoteDialog', function( editor ) {
    return {
        title: 'Fußnote konfigurieren',
        minWidth: 400,
        minHeight: 200,

        contents: [
            {
                id: 'tab-basic',
                label: 'Basic Einstellungen',
                elements: [
                    {
                        type: 'text',
                        id: 'footnoteNumber',
                        label: 'Fußnote Nummer',
                        validate: CKEDITOR.dialog.validate.notEmpty("Die Zahl der Fußnote muss gesetzt sein.")
                    },
                    {
                        type: 'html',
                        html: '<h2>Nur die jeweilige Sprache die genutzt wird befüllen.</h2>'
                    },
                    {
                        type: 'text',
                        id: 'footnoteTextDe',
                        label: 'Fußnoten Text DE',
                    },
                    {
                        type: 'text',
                        id: 'footnoteTextEN',
                        label: 'Fußnoten Text EN',
                    }
                ],
            },
        ],

        onOk: function() {
            var dialog = this;
            var selectedText = editor.getSelection().getSelectedText();
            var prependItem = editor.document.createElement('a');

            if (dialog.getValueOf('tab-basic', 'footnoteTextDe').length) {
                prependItem.setAttributes({'data-number': dialog.getValueOf('tab-basic', 'footnoteNumber'),'data-note': dialog.getValueOf('tab-basic', 'footnoteTextDe') ,'class': 'external-link footnote-link', 'role': 'button', 'href': '#footnote-number-'+ dialog.getValueOf('tab-basic', 'footnoteNumber'), 'aria-label':'Springe zur Fußnote: ' + dialog.getValueOf('tab-basic', 'footnoteNumber'), 'id': 'footnoteMark-'+ dialog.getValueOf('tab-basic', 'footnoteNumber')});
            } else {
                prependItem.setAttributes({'data-number': dialog.getValueOf('tab-basic', 'footnoteNumber'),'data-note': dialog.getValueOf('tab-basic', 'footnoteTextEN') ,'class': 'external-link footnote-link', 'role': 'button', 'href': '#footnote-number-'+ dialog.getValueOf('tab-basic', 'footnoteNumber'), 'aria-label':'Jump to footnote: ' + dialog.getValueOf('tab-basic', 'footnoteNumber'), 'id': 'footnoteMark-'+ dialog.getValueOf('tab-basic', 'footnoteNumber')});
            }

            // Todo Prevent default on link. + Englisch (doctitle)
            prependItem.setText('[' + dialog.getValueOf( 'tab-basic', 'footnoteNumber' ) + selectedText + ']');
            editor.insertElement(prependItem);
        }
    };
});