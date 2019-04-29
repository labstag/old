import 'formBuilder';
export class formbuilder {
    constructor(emplacement) {
        this.emplacement = emplacement;
        this.execute();
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
            'disabledActionButtons': ['data', 'save'],
            'roles'                : ''
        };

        this.formBuilder = $('#' + this.emplacement).formBuilder(dataFormBuilder);
        $('#SaveFormBuilder').on('click', this.save.bind(this));
    }

    save(event) {
        event.preventDefault();
        this.get();
    }

    get() {
        console.log(this.formBuilder.actions.getData('json', true));
    }
}
