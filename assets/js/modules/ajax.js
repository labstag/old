export class ajax {
    constructor() {

    }
    delete(url, data) {
        window.fetch(
            url, {
                'method': 'DELETE',
                'body'  : JSON.stringify(data)
            }
        ).then((response) => {
            return response.text();
        } ).then((text) => {
            return JSON.parse(text);
        } ).then((json) => {
            if (json.redirect != undefined) {
                window.location.replace(json.redirect);
            }
        } );
    }
}
