require(
    ['tinymce'],
    function() {
        tinymce.init({
            selector: "#page-edit-content",
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

            extended_valid_elements: "a[*],abbr[*],acronym[*],address[*],applet[*],area[*],article[*],aside[*],audio[*],b[*],base[*],basefont[*],bdi[*],bdo[*],bgsound[*],big[*],blink[*],blockquote[*],body[*],br[*],button[*],canvas[*],caption[*],center[*],cite[*],code[*],col[*],colgroup[*],command[*],comment[*],datalist[*],dd[*],del[*],details[*],dfn[*],dir[*],div[*],dl[*],dt[*],em[*],embed[*],fieldset[*],figcaption[*],figure[*],font[*],footer[*],form[*],frame[*],frameset[*],h1[*],h2[*],h3[*],h4[*],h5[*],h6[*],head[*],header[*],hgroup[*],hr[*],html[*],i[*],iframe[*],img[*],input[*],ins[*],isindex[*],kbd[*],keygen[*],label[*],legend[*],li[*],link[*],listing[*],map[*],mark[*],marquee[*],menu[*],meta[*],meter[*],multicol[*],nav[*],nobr[*],noembed[*],noframes[*],noscript[*],object[*],ol[*],optgroup[*],option[*],output[*],p[*],param[*],plaintext[*],pre[*],progress[*],q[*],rp[*],rt[*],ruby[*],s[*],samp[*],script[*],section[*],select[*],small[*],source[*],spacer[*],span[*],strike[*],strong[*],style[*],sub[*],summary[*],sup[*],table[*],tbody[*],td[*],textarea[*],tfoot[*],th[*],thead[*],time[*],title[*],tr[*],track[*],tt[*],u[*],ul[*],var[*],video[*],wbr[*],xmp[*]",

            menubar: false,
            toolbar_items_size: 'small',

            height: "600",

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