const Encore = require('@symfony/webpack-encore');
const CopyWebpackPlugin = require('copy-webpack-plugin');

Encore.setOutputPath('public/build/');
Encore.setPublicPath('/build');
// Encore.addEntry('app', './assets/ts/app.ts');
Encore.addEntry('app', './assets/js/app.js');
Encore.splitEntryChunks();
Encore.enableSingleRuntimeChunk();
Encore.cleanupOutputBeforeBuild();
Encore.enableBuildNotifications();
Encore.enableSourceMaps(!Encore.isProduction());
Encore.enableVersioning(Encore.isProduction());
Encore.configureBabel(() => {}, {
    'useBuiltIns': 'usage',
    'corejs': 3,
});
Encore.configureUrlLoader({
    images: {
        limit: 4096
    }
})
Encore.enableSassLoader();
Encore.autoProvidejQuery();
Encore.autoProvideVariables({
    $: 'jquery',
    jQuery: 'jquery',
    'window.jQuery': 'jquery',
});
Encore.configureBabel();
Encore.addPlugin(new CopyWebpackPlugin([{
        'from': 'node_modules/tinymce/skins',
        'to': 'skins',
    },
    {
        'from': 'node_modules/tinymce-i18n/langs',
        'to': 'langs',
    },
    {
        'from': 'node_modules/tinymce/plugins',
        'to': 'plugins',
    }
]));
// Encore.enableTypeScriptLoader();
// Encore.enableForkedTypeScriptTypesChecking();
Encore.enableIntegrityHashes();

let webpack = Encore.getWebpackConfig();
webpack.output.jsonpFunction = "labstag";
module.exports = webpack;
