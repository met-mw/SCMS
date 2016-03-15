require(
    ['tinymce'],
    function() {
        tinymce.init({
            selector: "#catalogue-category-description, #catalogue-item-description",
            plugins: [
                "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak",
                "searchreplace wordcount visualblocks visualchars code insertdatetime media nonbreaking",
                "table contextmenu directionality emoticons template textcolor paste textcolor",
                "colorpicker textpattern responsivefilemanager"
            ],

            toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
            toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
            toolbar3: "responsivefilemanager | table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

            image_advtab: true,

            external_filemanager_path:"/filemanager/",
            filemanager_title: "Файловый менеджер",
            external_plugins: { "filemanager" : "/filemanager/plugin.min.js"},

            menubar: false,
            toolbar_items_size: 'small',

            height: "300",

            style_formats: [{
                title: 'Bold text',
                inline: 'b'
            }, {
                title: 'Red text',
                inline: 'span',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Red header',
                block: 'h1',
                styles: {
                    color: '#ff0000'
                }
            }, {
                title: 'Example 1',
                inline: 'span',
                classes: 'example1'
            }, {
                title: 'Example 2',
                inline: 'span',
                classes: 'example2'
            }, {
                title: 'Table styles'
            }, {
                title: 'Table row 1',
                selector: 'tr',
                classes: 'tablerow1'
            }],

            templates: [{
                title: 'Test template 1',
                content: 'Test 1'
            }, {
                title: 'Test template 2',
                content: 'Test 2'
            }],
            content_css: [
                '/public/assets/js/bower_components/bootstrap/dist/css/bootstrap.css',
                '/public/assets/css/tinymce/default.css'
            ]
        });
    }
);