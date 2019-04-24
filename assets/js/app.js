require('../css/app.scss');
import $ from 'jquery';
import 'jquery-ui';
import 'jquery-ui-sortable';
import 'bootstrap';
import {
    fullCalendar
} from './modules/fullcalendar';
import {
    datatables
} from './modules/datatables';
import {
    wysiwyg
} from './modules/wysiwyg';
import {
    formbuilder
} from './modules/formbuilder';
import {
    prismjs
} from './modules/prismjs';
import {
    form
} from './modules/form';
import {
    workflow
} from './modules/workflow';
import 'whatwg-fetch';
global.$      = $;
global.Jquery = $;
class Site {
    /**
     * TODO: Test
     */
    launch() {
        this.workflow     = new workflow('workflow');
        this.form         = new form();
        this.prismjs      = new prismjs();
        this.formbuilder  = new formbuilder('formBuilder');
        this.wysiwyg      = new wysiwyg();
        this.datatables   = new datatables();
        this.fullcalendar = new fullCalendar('fullCalendar');
        this.test('aa', 'bb', 'cc', 'dd');
        this.ajax();
        const NUMBER      = 1;

        let test = NUMBER;

        console.warn('coucou');
        console.error('test');
        console.debug('test');
        test = test + NUMBER;

        return test;
    }

    test(a, b, c, d) {
        console.log(arguments);
    }

    ajaxThen1(response) {
        return response.blob();
    }

    ajaxThen2(myBlob) {
        var objectURL = URL.createObjectURL(myBlob);

        console.log(objectURL);
    }

    ajax() {
        window.fetch('flowers.jpg').then(this.ajaxThen1).then(this.ajaxThen2);
    }
}
const site = new Site();

site.launch();
