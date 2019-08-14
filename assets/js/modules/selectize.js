import 'selectize';
export class selectize {
    constructor() {
        document.querySelectorAll('select').forEach(this.launch.bind(this));
    }
    launch(select) {
        const url   = select.getAttribute('data-url');
        let options = this.setOptions(url);

        $(select).selectize(options);
    }

    setOptions(url) {
        let options = {};

        if (url == undefined) {
            return options;
        }
        options = {
            'valueField' : 'value',
            'labelField' : 'name',
            'searchField': 'name',
            'create'     : true,
            'render'     : {
                'options': function (item, escape) {
                    return item.name
                }
            },
            'load': function (query, callback) {
                if (!query.length) {
                    return callback();
                }
                $.ajax( {
                    'url' : url,
                    'type': 'POST',
                    'data': {
                        'req': query
                    },
                    'error': function () {
                        callback();
                    },
                    'success': function (res) {
                        callback(res.data);
                    }
                } );
            }
        }
        return options;
    }
}
