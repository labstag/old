import 'bootstrap-table';
import 'bootstrap-table/dist/bootstrap-table-locale-all';
var moment = require('moment');

export class datatables {
    constructor() {
        this.execute();
        moment.locale('fr');
    }

    execute() {
        window.dateFormatter  = this.dateFormatter;
        window.imageFormatter = this.imageFormatter;
        window.ajaxOptions    = {
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
    }

    dateFormatter(value, row) {
        return moment(value).format('MMMM Do YYYY, H:mm:ss');
    }

    imageFormatter(value, row) {
        if (value != null) {
            return '<img src="/file/' + value + '" class="img-thumbnail" />';
        }

        return '';
    }
}
