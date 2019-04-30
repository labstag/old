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
    }
    dateFormatter(value, row) {
        return moment(value).format('MMMM Do YYYY, H:mm:ss');
    }
    imageFormatter(value, row) {
        if (value != '') {
            return '<img src="/file/' + value + '" class="img-thumbnail" />';
        }

        return '';
    }
}
