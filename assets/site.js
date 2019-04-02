import 'bootstrap';
class Site {
    constructor() {
        console.log('test');
    }

    /**
     * TODO: Test
     */
    launch() {
        console.warn('coucou');
        console.error('test');
        console.debug('test');
        debugger;
        alert('coucou');
    }
}
let site = new Site();
site.launch();
