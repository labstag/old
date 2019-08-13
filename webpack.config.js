const Encore            = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const mode              = String((process.env.NODE_ENV != undefined) ? process.env.NODE_ENV : 'php').trim();

Encore.setOutputPath('public/build/');
if (Encore.isProduction() || mode == 'php') {
    Encore.setPublicPath('/build');
} else {
    Encore.setPublicPath('/labstag/public/build');
}
// Encore.addEntry('app', './assets/ts/app.ts');
Encore.addEntry('app', [
    './assets/js/app.js'
]);
Encore.splitEntryChunks();
Encore.enableSingleRuntimeChunk();
Encore.cleanupOutputBeforeBuild();
Encore.enableBuildNotifications();
Encore.enableSourceMaps(!Encore.isProduction());
Encore.enableVersioning(Encore.isProduction());
Encore.configureBabel(() => {}, {
    'useBuiltIns': 'usage',
    'corejs'     : 3
} );
Encore.configureUrlLoader( {
    'images': {
        'limit': 4096
    }
} )
Encore.enableSassLoader();
Encore.enableLessLoader();
Encore.autoProvidejQuery();
Encore.autoProvideVariables( {
    'jsPDF'        : 'jspdf',
    '$'            : 'jquery',
    'jQuery'       : 'jquery',
    '$.formBuilder': 'formBuilder',
    'window.jQuery': 'jquery'
} );
Encore.configureBabel();
Encore.addPlugin(new CopyWebpackPlugin([{
        'from': 'node_modules/tinymce/skins',
        'to'  : 'skins'
    },
    {
        'from': 'node_modules/tinymce-i18n/langs',
        'to'  : 'langs'
    },
    {
        'from': 'node_modules/tinymce/plugins',
        'to'  : 'plugins'
    },
    {
        'from': 'node_modules/formbuilder-languages',
        'to'  : 'formbuilder-lang'
    }
]));
// Encore.enableTypeScriptLoader();
// Encore.enableForkedTypeScriptTypesChecking();
if (Encore.isProduction()) {
    Encore.enableIntegrityHashes();
}

let webpack = Encore.getWebpackConfig();

if (Encore.isProduction()) {
    webpack.output.jsonpFunction = 'labstag';
}
module.exports = webpack;
