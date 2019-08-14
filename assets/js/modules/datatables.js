import 'whatwg-fetch';
import 'jquery-resizable-columns';
import 'bootstrap-table';
import 'tableexport.jquery.plugin/tableExport.min';
import 'bootstrap-table/dist/bootstrap-table-locale-all';
import 'bootstrap-table/dist/extensions/resizable/bootstrap-table-resizable';
import 'bootstrap-table/dist/extensions/export/bootstrap-table-export';
import '@fancyapps/fancybox';
let moment = require('moment');

export class datatables {
    constructor() {
        this.execute();
        moment.locale($('body').attr('data-momentlang'));
    }

    execute() {
        window.dateFormatter      = this.dateFormatter;
        window.imageFormatter     = this.imageFormatter;
        window.queryParams        = this.queryParams;
        window.operationDatatable = this.operations;
        window.dataTotalFormatter = this.dataTotal;
        window.enableFormatter    = this.enable.bind(this);
        window.endFormatter       = this.end.bind(this);
        window.dataFormatter      = this.dataFormatter;
        window.urlData            = [];
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

        $('#CrudList').on('post-header.bs.table', this.postheader.bind(this));
        $(document).on('change',
            '.custom-control-input',
            this.changeEnable.bind(this)
        );
    }

    postheader() {
        setTimeout(this.postheaderTime.bind(this), 2000);
    }

    postheaderTime() {
        for (let url in window.urlData) {
            this.launchData(url);
        }
        window.urlData = [];
    }

    launchData(url) {
        window.fetch(
            url, {}
        ).then(
            (response) => {
                return response.text();
            }
        ).then((text) => {
            return JSON.parse(text);
        } ).then((json) => {
            this.dataTreat(json);
        } );
    }

    dataTreat(json) {
        let id   = json['@id'];
        let type = json['@type'];

        if (json.name != undefined) {
            this.changeDiv(id, json.name);
        } else if (type == 'User') {
            this.changeDiv(id, json.username);
        } else {
            console.log(type);
        }
    }

    changeDiv(id, value) {
        document.querySelectorAll('.DataSpan').forEach(
            (element) => {
                if (element.getAttribute('data-id') == id) {
                    element.innerHTML = value;
                }
            }
        );
    }

    dataFormatter(value, row) {
        if (value != null) {
            window.urlData[value] = 1;
        }
        let span = document.createElement('span');

        span.setAttribute('data-id', value);
        span.setAttribute('class', 'DataSpan');
        span.innerHTML = value;

        return span.outerHTML;
    }

    changeEnable(event) {
        let element = $(event.currentTarget);
        let enable  = $(element).attr('data-enable');

        let state   = $(element).is(':checked');
        let table   = $(element).closest('table');
        let idTable = $(table).attr('id');
        let url     = $('#' + idTable).attr('data-enableurl-' + enable);

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

    end(value, row) {
        return this.switch(value, row, 'end');
    }

    enable(value, row) {
        return this.switch(value, row, 'enable');
    }

    uniqid() {
        return (new Date().getTime() + Math.floor((Math.random() * 10000) + 1)).toString(16);
    }

    switch (value, row, id) {
        let div    = document.createElement('div');
        let uniqid = this.uniqid();

        div.setAttribute('class', 'custom-control custom-switch');
        let input = document.createElement('input');

        input.setAttribute('type', 'checkbox');
        input.setAttribute('class', 'custom-control-input');
        input.setAttribute('data-enable', id);
        input.setAttribute('id', 'customSwitch' + uniqid);
        input.setAttribute('data-id', row.id);
        if (value == true) {
            input.setAttribute('checked', 'checked');
        }

        let label = document.createElement('label');

        label.setAttribute('class', 'custom-control-label');
        label.setAttribute('for', 'customSwitch' + uniqid);
        div.append(input);
        div.append(label);

        return div.outerHTML;
    }

    dataTotal(value, row) {
        return value.length;
    }

    operations(value, row) {
        let div      = document.querySelector('.OperationCrud');
        let links    = div.querySelectorAll('a');
        let html     = '';
        let idEntity = row.id;

        if (value != undefined) {
            idEntity = value;
        }

        links.forEach(
            (element) => {
                let a = element.outerHTML;

                a    = a.replace('code', idEntity);
                html = html + a;
            }
        );

        return html;
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
            let link = document.createElement('a');
            let img  = document.createElement('img');
            let url  = $('#CrudList').attr('data-files');

            link.setAttribute('data-fancybox', true);
            link.setAttribute('href', url + value);
            img.setAttribute('src', url + value);
            img.setAttribute('class', 'img-thumbnail');
            link.appendChild(img);
            return link.outerHTML;
        }

        return '';
    }
}
