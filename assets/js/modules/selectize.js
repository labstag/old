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
            'valueField' : 'url',
            'labelField' : 'name',
            'searchField': 'name',
            'render'     : {
                'options': function (item, escape) {
                    return 'aa';
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
                        callback(res);
                    }
                } );
            }
        }
        return options;
    }
}
