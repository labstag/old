export class admin {
    constructor() {
        $(document).on(
            'click',
            '.OperationLinkDelete',
            function (event) {
                event.preventDefault();
            }
        );
        this.userList();
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
