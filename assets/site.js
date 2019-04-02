import 'bootstrap';
class Site {

    /**
     * TODO: Test
     */
    launch() {
        const NUMBER = 1;

        let test = NUMBER;

        console.warn('coucou');
        console.error('test');
        console.debug('test');
        test += NUMBER;

        debugger;

        alert('coucou');

        return test;
    }

}
const site = new Site();

site.launch();
