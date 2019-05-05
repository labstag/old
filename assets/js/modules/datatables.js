import 'whatwg-fetch';
import 'jquery-resizable-columns';
import 'bootstrap-table';
import 'bootstrap-table/dist/bootstrap-table-locale-all';
import 'bootstrap-table/dist/extensions/resizable/bootstrap-table-resizable';
let moment = require('moment');

export class datatables {
    constructor() {
        this.execute();
        moment.locale('fr');
    }

    execute() {
        window.dateFormatter      = this.dateFormatter;
        window.imageFormatter     = this.imageFormatter;
        window.queryParams        = this.queryParams;
        window.operationDatatable = this.operations;
        window.dataTotalFormatter = this.dataTotal;
        window.enableFormatter    = this.enable;
        window.ajaxOptions        = {
            'headers': {
                'Accept': 'application/ld+json'
            },
            'dataFilter': function (data) {
                var json = JSON.parse(data);
                var data = {
                    'count'           : json['hydra:totalItems'],
                    'totalNotFiltered': json['hydra:totalItems'],
                    'rows'            : json['hydra:member'],
                    'total'           : json['hydra:member'].length
                };

                return JSON.stringify(data);
            }
        };

        $(document).on('change',
            '.custom-control-input',
            this.changeEnable.bind(this)
        );
    }

    changeEnable(event) {
        let element = $(event.currentTarget);
        let state   = $(element).is(':checked');
        let table   = $(element).closest('table');
        let url     = table.attr('data-enableurl');

        window.fetch(
            url, {
                'method': 'POST',
                'body'  : JSON.stringify( {
                    'state': state,
                    'id'   : $(element).attr('data-id')
                } )
            }
        );
    }

    enable(value, row) {
        let div = document.createElement('div');

        div.setAttribute('class', 'custom-control custom-switch');
        let input = document.createElement('input');

        input.setAttribute('type', 'checkbox');
        input.setAttribute('class', 'custom-control-input');
        input.setAttribute('id', 'customSwitch' + row.id);
        input.setAttribute('data-id', row.id);
        if (value == true) {
            input.setAttribute('checked', 'checked');
        }

        let label = document.createElement('label');

        label.setAttribute('class', 'custom-control-label');
        label.setAttribute('for', 'customSwitch' + row.id);
        div.append(input);
        div.append(label);

        return div.outerHTML;
    }

    dataTotal(value, row) {
        return value.length;
    }

    operations(value, row) {
        let operationDelete = document.querySelector('.OperationDelete').innerHTML;
        let operationUpdate = document.querySelector('.OperationUpdate').innerHTML;

        operationUpdate = operationUpdate.replace('code', row.id);
        return operationUpdate + operationDelete;
    }

    queryParams(params) {
        if (params.offset == 0) {
            params.page = 1;
        } else {
            params.page = params.offset / params.limit + 1;
        }
        if (params.page == 0) {
            delete params.page;
        }

        delete params.offset;
        if (params.search == '') {
            delete params.search;
        }

        if (params.sort != undefined) {
            let val  = 'order[' + params.sort + ']';
            let sort = params.order;

            delete params.order;
            delete params.sort;
            params[val] = sort;
        } else {
            delete params.order;
        }

        return params;
    }

    dateFormatter(value, row) {
        return moment(value).format('MMMM Do YYYY, H:mm:ss');
    }

    imageFormatter(value, row) {
        if (value != null) {
            let img = document.createElement('img');

            img.setAttribute('src', '/file/' + value);
            img.setAttribute('class', 'img-thumbnail');
            return img.outerHTML;
        }

        return '';
    }
}
