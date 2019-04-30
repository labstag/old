import 'bootstrap-table';
import 'bootstrap-table/dist/bootstrap-table-locale-all';
export class datatables {
    constructor() {
        this.execute();
    }
    execute() {
        window.dateFormatter = this.dateFormatter;
    }
    dateFormatter(value, row) {
        return value;
    }
}
