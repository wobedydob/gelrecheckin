const WebpackNotifierPlugin = require('webpack-notifier');
const path = require('path');

module.exports = {

    mode: 'development',
//    mode: 'production',

    entry: [
        './assets/js/app.js',
        './assets/scss/style.scss'
    ],

    output: {
        filename: 'app.min.js',
        path: path.resolve(__dirname, './assets/js/'),
        publicPath: '.'
    },

    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '../css/style.min.css'
                        }
                    },
                    {
                        loader: 'extract-loader'
                    },
                    {
                        loader: 'css-loader?-url'
                    },
                    {
                        loader: 'postcss-loader',
                        options: {
                            postcssOptions: {
                                plugins: () => [require('autoprefixer')]
                            }
                        }
                    },
                    {
                        loader: 'sass-loader'
                    }
                ]
            }
        ]
    }
};