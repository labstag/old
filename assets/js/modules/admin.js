export class admin {
    constructor() {
        this.userList();
        this.btndelete();
        this.sortable();
    }

    sortable() {
        $('#sortable').sortable( {
            'stop': this.positionChange
        } );
    }

    positionChange(event, ui) {
        $('#sortable').find('input').each(
            function (index) {
                $(this).val(index);
            }
        );
    }
    btndelete() {
        $(document).on(
            'click',
            '.OperationLinkDelete',
            this.btndeleteOnClick.bind(this)
        );
    }

    btndeleteOnClick(event) {
        event.preventDefault();
        $('.BtnDeleteModalConfirm').attr('href', $(event.currentTarget).attr('href'));
        $('.BtnDeleteModalConfirm').attr('data-id', $(event.currentTarget).attr('data-id'));
        $('.BtnDeleteModalConfirm').off('click');
        $('.BtnDeleteModalConfirm').on('click', this.confirmDelete.bind(this));
        $('#deleteModal').modal();
    }

    confirmDelete(event) {
        event.preventDefault();
        let data = [];
        let url  = $('.BtnDeleteModalConfirm').attr('href');
        let id   = $('.BtnDeleteModalConfirm').attr('data-id');

        data.push(id);
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

    userList() {
        window.rolesFormatter = this.rolesFormatter;
    }

    rolesFormatter(roles, row) {
        let ul = document.createElement('ul');

        $(roles).each(
            function (value) {
                let li = document.createElement('li');

                li.innerHTML = roles[value];

                ul.append(li);
            }
        );

        return ul.outerHTML;
    }
}
