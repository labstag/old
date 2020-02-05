import 'whatwg-fetch';
import 'jquery-resizable-columns';
import 'bootstrap-table';
import 'tableexport.jquery.plugin/tableExport.min';
import 'bootstrap-table/dist/bootstrap-table-locale-all';
import 'bootstrap-table/dist/extensions/resizable/bootstrap-table-resizable';
import 'bootstrap-table/dist/extensions/toolbar/bootstrap-table-toolbar';
import 'bootstrap-table/dist/extensions/export/bootstrap-table-export';
import 'bootstrap-table/dist/extensions/page-jump-to/bootstrap-table-page-jump-to.min';
import 'bootstrap-table/dist/extensions/multiple-sort/bootstrap-table-multiple-sort';
import 'bootstrap-table/dist/extensions/mobile/bootstrap-table-mobile';
import 'bootstrap-table/dist/extensions/cookie/bootstrap-table-cookie';
import '@fancyapps/fancybox';
let moment = require('moment');

export class datatables {
    constructor() {
        this.execute();
        let body = document.querySelector('body');

        moment.locale(body.getAttribute('data-momentlang'));
    }

    setWindows() {
        window.dateFormatter = this.dateFormatter;
        window.imageFormatter = this.imageFormatter.bind(this);
        window.queryParams = this.queryParams;
        window.operationDatatable = this.operations;
        window.dataTotalFormatter = this.dataTotal;
        window.enableFormatter = this.enable.bind(this);
        window.endFormatter = this.end.bind(this);
        window.dataFormatter = this.dataFormatter;
        window.urlData = [];
    }

    execute() {
        this.setWindows();
        window.ajaxOptions = {
            'headers': {
                'Accept': 'application/ld+json'
            },
            'dataFilter': function (data) {
                var json = JSON.parse(data);
                var data = {
                    'count': json['hydra:totalItems'],
                    'totalNotFiltered': json['hydra:totalItems'],
                    'rows': json['hydra:member'],
                    'total': json['hydra:member'].length
                };

                return JSON.stringify(data);
            }
        };

        $('#CrudList').on('post-header.bs.table', this.postheader.bind(this));
        $(document).on(
            'change',
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
        window.
        fetch(url, {}).
        then((response) => {
            return response.text();
        }).
        then((text) => {
            return JSON.parse(text);
        }).
        then((json) => {
            this.dataTreat(json);
        });
    }

    dataTreat(json) {
        const id = json['@id'];
        const type = json['@type'];

        if (json.name != undefined) {
            this.changeDiv(id, json.name);
        } else if (type == 'User') {
            this.changeDiv(id, json.username);
        } else {
            console.log(type);
        }
    }

    changeDiv(id, value) {
        document.querySelectorAll('.DataSpan').forEach((element) => {
            if (element.getAttribute('data-id') == id) {
                element.innerHTML = value;
            }
        });
    }

    dataFormatter(value, row) {
        if (value != null) {
            window.urlData[value] = 1;
        }
        let span = document.createElement('span');

        span.setAttribute('data-id', value);
        span.setAttribute('class', 'DataSpan');
        span.innerHTML = '';

        return span.outerHTML;
    }

    changeEnable(event) {
        const element = $(event.currentTarget);
        const enable = $(element).attr('data-enable');
        const state = $(element).is(':checked');
        const table = $(element).closest('table');
        const idTable = $(table).attr('id');
        const url = $('#' + idTable).attr('data-enableurl-' + enable);

        window.fetch(url, {
            'method': 'POST',
            'body': JSON.stringify({
                'state': state,
                'id': $(element).attr('data-id')
            })
        });
    }

    end(value, row) {
        return this.switch(value, row, 'end');
    }

    enable(value, row) {
        return this.switch(value, row, 'enable');
    }

    uniqid() {
        return (
            new Date().getTime() + Math.floor(Math.random() * 10000 + 1)
        ).toString(16);
    }

    setAttribute(input, data) {
        for (let [key, value] of Object.entries(data)) {
            input.setAttribute(key, value);
        }
    }

    setInput(uniqId, row, id, value) {
        const input = document.createElement('input');
        let data = {
            'type': 'checkbox',
            'class': 'custom-control-input',
            'data-enable': id,
            'id': `customSwitch${uniqId}`,
            'data-id': row.id
        };

        if (value == true) {
            data.checked = 'checked';
        }

        this.setAttribute(input, data);

        return input;
    }

    setLabel(uniqId) {
        const label = document.createElement('label');
        const data = {
            'class': 'custom-control-label',
            'for': `customSwitch${uniqId}`
        };

        this.setAttribute(label, data);

        return label;
    }

    switch (value, row, id) {
        const div = document.createElement('div');
        const uniqId = this.uniqid();

        div.setAttribute('class', 'custom-control custom-switch');
        const input = this.setInput(uniqId, row, id, value);
        const label = this.setLabel(uniqId);

        div.append(input);
        div.append(label);

        return div.outerHTML;
    }

    dataTotal(value, row) {
        return value.length;
    }

    operations(value, row) {
        const div = document.querySelector('.OperationCrud');
        const links = div.querySelectorAll('a');
        let html = '';
        let idEntity = row.id;

        if (value != undefined) {
            idEntity = value;
        }

        links.forEach((element) => {
            let a = element.outerHTML;

            a = a.replace('code', idEntity);
            html = html + a;
        });

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
            let val = 'order[' + params.sort + ']';
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
            const link = document.createElement('a');
            const img = document.createElement('img');
            const url = $('#CrudList').attr('data-files');
            const data = {
                'data-fancybox': true,
                'href': `${url}${value}`,
                'src': `${url}${value}`,
                'class': 'img-thumbnail'
            };

            this.setAttribute(link, data);
            link.appendChild(img);
            return link.outerHTML;
        }

        return '';
    }
}
