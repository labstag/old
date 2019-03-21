const path = require('path')
const CleanWebpackPlugin   = require('clean-webpack-plugin')
const ManifestPlugin       = require('webpack-assets-manifest')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const UglifyJSPlugin       = require('uglifyjs-webpack-plugin')
const webpack              = require('webpack')
const dev                  = process.env.NODE_ENV == 'dev'
let   config               = {
    'mode' : dev ? 'development': 'production',
    'entry': {
        'site': [
            'jquery',
            'bootstrap',
            'datatables.net',
            'datatables.net-fixedcolumns',
            'datatables.net-rowgroup',
            'moment',
            'bootstrap-datepicker',
            'detect-mobile-browser',
            './assets/site.scss',
            './assets/site.js'
        ]
    },
    resolve: {
        alias: {
            node_modules: path.resolve(__dirname, 'node_modules')
        },
        modules: [
            'node_modules',
            path.resolve(__dirname, 'node_modules')
        ]
    },
    'devtool': dev ? 'cheap-module-eval-source-map': '',
    'module' : {
        'rules': [{
                'enforce': 'pre',
                'test'   : /\.js$/,
                'exclude': /node_modules/,
                'use'    : ['babel-loader', 'eslint-loader']
            },
            {
                'test'   : /\.js$/,
                'exclude': /node_modules/,
                'use'    : [{
                    'loader' : 'babel-loader',
                    'options': {
                        'presets': [
                            ['@babel/preset-env']
                        ],
                        'plugins': [
                            'syntax-dynamic-import'
                        ]
                    }
                }]
            },
            {
                'test': /\.(sa|sc|c)ss$/,
                'use' : [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                'test'  : /\.(woff2|woff|eot|ttf|otf)(\?.*)?$/,
                'loader': 'file-loader'
            },
            {
                'test': /\.(png|jpe?g|gif|svg)$/,
                'use' : [{
                    'loader' : 'url-loader',
                    'options': {
                        'name' : '[name].[hash:7].[ext]',
                        'limit': 8192
                    }
                }]
            }
        ]
    },
    'output': {
        'crossOriginLoading': 'anonymous',
        'path'              : path.resolve(__dirname, 'public/assets'),
        'filename'          : '[name].[chunkhash:8].js',
        'publicPath'        : ''
    },
    'plugins': [
        new MiniCssExtractPlugin({
            'filename'     : '[name].[contenthash:8].css',
            'chunkFilename': '[id].[hash].css'
        }),
        new ManifestPlugin({
            output         : 'manifest.json',
            integrityHashes: ['sha256'],
            integrity      : true
        }),
        // new CleanWebpackPlugin(
        //     ['assets'], {
        //         'root'   : path.resolve('./public/'),
        //         'verbose': true,
        //         'dry'    : false
        //     }
        // ),
        new webpack.ProvidePlugin({
            $              : 'jquery',
            jQuery         : 'jquery',
            'window.jQuery': 'jquery',
            'window.$'     : 'jquery',
        })
    ]
}

if (!dev) {
    config.plugins.push(
        // new UglifyJSPlugin({
        //     comments     : false,
        //     uglifyOptions: {
        //         compress: true,
        //         warnings: false,
        //     },
        // })
    )
}

module.exports = config
