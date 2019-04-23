require('../css/app.scss');
import $ from 'jquery';
import 'jquery-ui';
import 'jquery-ui-sortable';
import 'bootstrap';
import 'formBuilder';
import 'moment';
import {
    Calendar
} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import allLocales from '@fullcalendar/core/locales-all';
import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-select';
import 'datatables.net-select-bs4';
import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.colVis.min.js';
import 'datatables.net-buttons/js/buttons.flash.min.js';
import 'datatables.net-buttons/js/buttons.html5.min.js';
import 'datatables.net-buttons/js/buttons.print.min.js';
import 'datatables.net-buttons-bs4';
import 'tinymce/tinymce';
import 'tinymce/themes/silver/theme';
import 'clipboard';
import 'prismjs';
import 'prismjs/plugins/normalize-whitespace/prism-normalize-whitespace';
import 'prismjs/plugins/toolbar/prism-toolbar';
import 'prismjs/plugins/autolinker/prism-autolinker';
import 'prismjs/plugins/previewers/prism-previewers';
import 'prismjs/plugins/show-language/prism-show-language';
import 'prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard';
import 'prismjs/components';

global.$      = $;
global.Jquery = $;
class Site {
    /**
     * TODO: Test
     */
    launch() {
        this.setWysiwyg();
        this.setDatatables();
        this.initFormBuilder();
        this.initFullCalendar();
        this.initFormCollection();
        const NUMBER = 1;

        let test = NUMBER;

        console.warn('coucou');
        console.error('test');
        console.debug('test');
        test = test + NUMBER;

        return test;
    }

    initFullCalendarDomContentLoaded() {
        if ($('#fullCalendar').length == 0) {
            return;
        }
        let calendarEl   = document.getElementById('fullCalendar');
        let dataCalendar = {
            'header': {
                'left'  : 'prev,next today',
                'center': 'title',
                'right' : 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            'locales'    : allLocales,
            'locale'     : $('html').attr('lang'),
            'plugins'    : [dayGridPlugin, timeGridPlugin, listPlugin],
            'defaultView': 'dayGridMonth'
        };
        let calendar     = new Calendar(calendarEl, dataCalendar);

        // console.log(calendar);

        calendar.render();
    }

    initFullCalendar() {
        document.addEventListener('DOMContentLoaded', this.initFullCalendarDomContentLoaded);
    }

    initFormCollectionAdd() {
        if ($('.BtnCollectionAdd').length == 0) {
            return;
        }
        $(document).on(
            'click',
            '.BtnCollectionAdd',
            function (event) {
                let fieldset      = $(event.currentTarget).closest('fieldset');
                let prototype     = $(fieldset).attr('data-prototype');
                let index         = $(fieldset).find('tr').length;
                let tags          = prototype.replace(/__name__/g, index);
                let tabCollection = $(fieldset).find('.TabCollection');

                $(tabCollection).append(tags);
            }
        );
    }
    initFormCollectionDelete() {
        if ($('.BtnCollectionDelete').length == 0) {
            return;
        }
        $(document).on(
            'click',
            '.BtnCollectionDelete',
            function () {
                $(this).closest('.CollectionRow').remove();
            }
        );
    }

    initFormCollection() {
        this.initFormCollectionAdd();
        this.initFormCollectionDelete();
    }

    initFormBuilder() {
        let dataFormBuilder = {
            'i18n': {
                'location': $('#formBuilder').attr('data-url'),
                'locale'  : 'fr-FR'
            },
            'disabledActionButtons': ['data', 'save'],
            'roles'                : ''
        };

        this.formBuilder = $('#formBuilder').formBuilder(dataFormBuilder);
        $('#SaveFormBuilder').on('click', this.saveFormBuilder.bind(this));
    }

    saveFormBuilder(event) {
        event.preventDefault();
        this.getFormBuilder();
    }

    getFormBuilder() {
        console.log(this.formBuilder.actions.getData('json', true));
    }

    ajaxThen1(response) {
        return response.blob();
    }

    ajaxThen2(myBlob) {
        var objectURL = URL.createObjectURL(myBlob);

        console.log(objectURL);
    }

    ajax() {
        fetchPolyfill('flowers.jpg').then(this.ajaxThen1).then(this.ajaxThen2);
    }

    setDatatables() {
        let $tables = $('.dataTable');

        $($tables).each(
            function () {
                let $table = $(this);

                $($table).DataTable( {
                    'orderCellsTop': true,
                    'fixedHeader'  : true,
                    'dom'          : 'Bfrtip',
                    'buttons'      : [
                        'selected',
                        'selectedSingle',
                        'selectAll',
                        'selectNone',
                        'selectRows',
                        'selectColumns',
                        'selectCells',
                        'copy', 'csv', 'print'
                    ],
                    'columnDefs': [{
                        'orderable': false,
                        'className': 'select-checkbox',
                        'targets'  : 0
                    }],
                    'select': {
                        'style': 'multi'
                    }
                } );
            }
        );
    }

    setWysiwyg() {
        console.log('aa');
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
            'plugins'      : 'code emoticons visualblocks print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
            'toolbar'      : 'formatselect | bold italic strikethrough forecolor backcolor | link image media | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
            'language'     : 'fr_FR',
            'images_upload_handler'(blobInfo, success, failure) {}
        };

        $('.wysiwyg').each(
            function () {
                var $id         = $(this).attr('id');
                var tinymceData = dataTinymce;

                tinymceData.selector = '#' + $id;

                tinymce.init(tinymceData);
            }
        );
    }
}
const site = new Site();

site.launch();
