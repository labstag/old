export class admin {
    constructor() {
        $(document).on(
            'click',
            '.OperationLinkDelete',
            function (event) {
                event.preventDefault();
            }
        );
    }
}
