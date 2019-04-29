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
                let $table = $tables[index];

                $($table).DataTable( {
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
                    'columnDefs': [{
                        'orderable': false,
                        'className': 'select-checkbox',
                        'targets'  : 0
                    }],
                    'select': {
                        'style': 'multi'
                    }
                } );
            }
        );
    }
}
