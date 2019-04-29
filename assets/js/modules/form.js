export class form {
    constructor() {
        this.add();
        this.delete();
        this.session();
    }

    session() {
        $('input, select, textarea').each(
            function (index) {
                let $form     = $(this).closest('form');
                let $formName = $($form).attr('name');
                let $inputID  = $(this).attr('id');

                if ($formName != undefined) {
                    console.log($formName + ' ' + $inputID);
                }
            }
        )
    }
    add() {
        let collectionAdd = document.querySelectorAll('.BtnCollectionAdd');

        if (collectionAdd.length == 0) {
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
    delete() {
        $(document).on(
            'click',
            '.BtnCollectionDelete',
            function () {
                $(this).closest('.CollectionRow').remove();
            }
        );
    }
}
