function tinymceInit(selector = '#content') {
    let tips = '<i style="color: red">*</i>';
    tinymce.init({
        selector: selector,
        //移除右下角的POWERED BY TINYMCE文字
        branding: false,
        menubar: 'edit insert format table',
        height: '800px',
        // content_css: "/front_design/front_end/css/m_video.css?="+ new Date().getTime(),
        // body_class: 'interduce__box',
        relative_urls: false,
        convert_urls: false,
        language: "zh_TW",
        //格式全開
        valid_elements: '*[*]',
        // plugins: 'a11ychecker advcode casechange formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinymcespellchecker image',
        plugins: 'autolink lists media table image code autolink link',
        // fontsize_formats: "fontsize_formats8pt 10pt 12pt 13pt 14pt 15pt 17pt 18pt 24pt 36pt",
        toolbar: "insertfile undo redo | styleselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link media image code",
        toolbar_mode: 'floating',
        style_formats: [
            {title: '標題', block: 'div', selector: 'p,div', classes: 'title'},
            {title: '內文', block: 'div', selector: 'p,div', classes: 'description'}
        ],
        setup: function (editor) {
            editor.on('change', function () {
                this.save();
                need_update = true;
                let target = $('.nav-tabs > li:eq(' + $('#' + this.id).parents('div.tab-pane').index() + ')').find('a');

                if (target.find('i').length === 0) {
                    target.append(tips);
                }
            });
        },
        paste_postprocess: function (pl, o) {
            // remove &nbsp
            o.node.innerHTML = o.node.innerHTML.replace(/&nbsp;/ig, " ");
        },
        // cleanup_callback: 'my_cleanup_callback',
        images_upload_handler: function (blobInfo, success) {
            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/api/image');
            xhr.setRequestHeader('X-CSRF-TOKEN',
                document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            );
            xhr.setRequestHeader('X-AJAX', true);

            xhr.onload = function () {
                var json;

                if (xhr.status != 200) {
                    alert('上傳失敗');
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (json.status != true) {
                    alert('檔案格式僅支援jpg, jpeg 或 png');
                    return;
                }

                success(json.url);
            };

            formData = new FormData();
            formData.append('uploadedFile', blobInfo.blob(), blobInfo.filename());
            xhr.send(formData);
        }
    });
}
