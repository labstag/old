import 'datatables.net';
import 'datatables.net-bs4';
import 'datatables.net-select';
import 'datatables.net-select-bs4';
import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.colVis.min.js';
import 'datatables.net-buttons/js/buttons.flash.min.js';
import 'datatables.net-buttons/js/buttons.html5.min.js';
import 'datatables.net-buttons/js/buttons.print.min.js';
import 'datatables.net-buttons-bs4';
export class datatables {
    constructor() {
        let $tables = document.querySelectorAll('.dataTable');

        $tables.forEach(
            function (element, index) {
                let $table         = $tables[index];
                let body           = document.querySelector('body');
                let lang           = body.getAttribute('data-langdatatables');
                let thead          = $tables[index].querySelector('thead');
                let th             = thead.querySelectorAll('th');
                let dataDataTables = {
                    'language': {
                        'url': lang
                    },
                    'orderCellsTop': true,
                    'fixedHeader'  : true,
                    'dom'          : 'Bfrtip',
                    'buttons'      : [
                        'selected',
                        'selectedSingle',
                        'selectAll',
                        'selectNone',
                        'selectRows',
                        'selectColumns',
                        'selectCells',
                        'copy', 'csv', 'print'
                    ],
                    // 'columnDefs': [{
                    //     'orderable': false,
                    //     'className': 'select-checkbox',
                    //     'targets'  : 0
                    // }],
                    'select': {
                        'style': 'multi'
                    }
                };
                let dataApi        = $tables[index].getAttribute('data-api');

                if (dataApi != '') {
                    dataDataTables.processing = true;
                    dataDataTables.ServerSide = true;
                    dataDataTables.columns    = [];
                    th.forEach((element) => {
                        let column = {
                            'data'  : element.getAttribute('data-field'),
                            'render': function (data, type, row, meta) {
                                return data;
                            }
                        };

                        dataDataTables.columns.push(column);
                    } );
                    dataDataTables.ajax       = {
                        'url'    : dataApi,
                        'headers': {
                            'Accept': 'application/ld+json'
                        },
                        'dataFilter': function (data) {
                            let json = JSON.parse(data);

                            json.recordTotal     = json['hydra:totalItems'];
                            json.recordsFiltered = json['hydra:totalItems'];
                            json.data            = json['hydra:member'];
                            return JSON.stringify(json);
                        }
                    };
                }

                $($table).DataTable(dataDataTables);
            }
        );
    }
}
