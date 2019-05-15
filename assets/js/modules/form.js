import 'whatwg-fetch';
export class form {
    constructor() {
        this.add();
        this.delete();
        this.session();
        this.save();
        this.btndelete();
        this.minimize();
    }

    minimize() {
        $('.ToggleFieldset').on('click', function () {
            let $maximize = $(this).find('.fa-window-maximize');
            let $minimize = $(this).find('.fa-window-minimize');
            let $table    = $(this).closest('fieldset').find('table');
            let $row      = $(this).closest('fieldset').find('.row');

            if ($table.length == 0) {
                $row.toggle('blind');
            } else {
                $table.toggle('blind');
            }
            if ($minimize.hasClass('d-none')) {
                $minimize.removeClass('d-none');
                $maximize.addClass('d-none');
            }
            if ($maximize.hasClass('d-none')) {
                $maximize.removeClass('d-none');
                $minimize.addClass('d-none');
            }
        } );
    }

    confirmDelete(event) {
        event.preventDefault();
        let data = [];
        let url  = $('.BtnDeleteModalConfirm').attr('href');

        if ($('main').find('form').length == 1) {
            let id = $('main').find('form').attr('data-id');

            data.push(id);
        } else if ($('#CrudList').length == 1) {
            let json = $('#CrudList').bootstrapTable('getSelections');

            $(json).each(
                function (index, row) {
                    data.push(row.id);
                }
            );
        }

        window.fetch(
            url, {
                'method': 'DELETE',
                'body'  : JSON.stringify(data)
            }
        ).then((response) => {
            return response.text();
        } ).then((text) => {
            return JSON.parse(text);
        } ).then((json) => {
            if (json.redirect != undefined) {
                window.location.replace(json.redirect);
            }
        } );
    }

    btndeleteOnClick(event) {
        event.preventDefault();
        $('.BtnDeleteModalConfirm').attr('href', $(event.currentTarget).attr('href'));
        $('.BtnDeleteModalConfirm').off('click');
        $('.BtnDeleteModalConfirm').on('click', this.confirmDelete.bind(this));
        if ($('main').find('form').length == 1) {
            $('#deleteModal').modal();
        } else if ($('#CrudList').length != 0) {
            let json = $('#CrudList').bootstrapTable('getSelections');

            if (json.length != 0) {
                $('#deleteModal').modal();
            }
        }
    }

    btndelete() {
        $('.BtnActionDelete').on('click', this.btndeleteOnClick.bind(this));
    }

    paramDelete(object) {
        let parameters = [];

        for (var property in object) {
            if (object.hasOwnProperty(property)) {
                parameters.push(encodeURI('id[' + property + ']=' + object[property]));
            }
        }

        return parameters.join('&');
    }

    save() {
        $('.BtnActionSave').on('click', function (event) {
            $(event.currentTarget).attr('disable', true);
            event.preventDefault();
            $('main').find('form').trigger('submit');
        } );
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
