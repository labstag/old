import 'tinymce/tinymce';
import 'tinymce/themes/silver/theme';
export class wysiwyg {
    constructor() {
        let body        = document.querySelector('body');
        var dataTinymce = {
            'branding'            : false,
            'selector'            : '.wysiwyg',
            'height'              : 400,
            'image_title'         : true,
            'automatic_uploads'   : true,
            'file_picker_types'   : 'image',
            'images_upload_url'   : 'images.php',
            'file_picker_callback': function (callback, value, meta) {
                let input = document.createElement('input');

                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.onchange = function () {
                    let file   = this.files[0];
                    let reader = new FileReader();

                    reader.onload = function () {
                        let idBlob    = 'blobid' + (new Date()).getTime();
                        let blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        let base64    = reader.result.split(',')[1];
                        let blobInfo  = blobCache.create(idBlob, file, base64);

                        blobCache.add(blobInfo);
                        callback(blobInfo.blobUri(), {
                            'title': file.name
                        } );
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            },
            'relative_urls': false,
            'plugins'      : 'code emoticons visualblocks print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
            'toolbar'      : 'formatselect | bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify | numlist bullist outdent indent | removeformat | addcomment',
            'language'     : body.getAttribute('data-wysiwyglang'),
            'images_upload_handler'(blobInfo, success, failure) {}
        };

        tinymce.init(dataTinymce);
    }
}
