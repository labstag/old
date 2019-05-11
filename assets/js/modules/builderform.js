import $ from 'jquery';
import 'jquery-ui';
import 'jquery-ui-sortable';
import 'formBuilder';
export class builderform {
    constructor(emplacement) {
        this.emplacement                = emplacement;
        window.dataFormBuilderFormatter = this.formatter;
        this.execute();
    }

    formatter(value, row) {
        return JSON.parse(value).length;
    }

    execute() {
        if ($('#' + this.emplacement).length == 0) {
            return;
        }
        let dataFormBuilder = {
            'i18n': {
                'location': $('#' + this.emplacement).attr('data-url'),
                'locale'  : 'fr-FR'
            },
            'textarea'             : ['textarea', 'tinymce'],
            'disabledActionButtons': ['data', 'save'],
            'roles'                : '',
            'formData'             : $('#formbuilder_formbuilder').val()
        };

        this.formBuilder = $('#' + this.emplacement).formBuilder(dataFormBuilder);
        $("form[name='formbuilder']").on('submit', this.submit.bind(this));
    }

    submit() {
        $('#formbuilder_formbuilder').val(this.formBuilder.actions.getData('json', true));
    }
}
