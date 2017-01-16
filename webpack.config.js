const webpack              = require('webpack');
const ExtractTextPlugin    = require('extract-text-webpack-plugin');
const autoprefixer         = require('autoprefixer');
const cssnano              = require('cssnano');

let plugins = [];

const PROD = JSON.parse(process.env.PROD_ENV || false);

if (PROD) {
    plugins.push(
        new webpack.optimize.UglifyJsPlugin({
            compress: { warnings: false },
            sourceMap: false,
        })
    );

    plugins.push(
        new webpack.SourceMapDevToolPlugin({
            test: /app.bundle.js$/,
            filename: './public/js/app.bundle.js.map',
        })
    );
}

plugins.push(
    new webpack.SourceMapDevToolPlugin({
        test: /app.bundle.css$/,
        filename: './public/css/app.bundle.css.map',
    })
);

plugins.push(
    new webpack.ProvidePlugin({
        $: 'jquery',
        'jQuery': 'jquery',
    })
);

plugins.push(
    new ExtractTextPlugin('./public/css/app.bundle.css')
);

module.exports = {
    entry: [
        'bootstrap-loader/extractStyles',
        './resources/assets/js/app.js',
    ],
    output: {
        filename: './public/js/app.bundle.js',
    },
    module: {
        loaders: [
            { 
                test: /\.css$/,
                exclude: /node_modules|dist/,
                loader: ExtractTextPlugin.extract("style-loader", "css!postcss"),
            },
            {
                test: /\.js$/,
                exclude: /node_modules|dist/,
                loader: 'babel-loader',
            },
            {
                test: /\.(woff2?|svg)$/,
                exclude: /dist/,
                loader: 'file?name=./dist/fonts/[name].[ext]',
            },
            {   
                test: /\.(ttf|eot)$/,
                exclude: /dist/,
                loader: 'file?name=./dist/fonts/[name].[ext]',
            },
        ],
    },
    plugins: plugins,
    postcss: function() {
        return [
            autoprefixer({ browsers: [ '>2%' ] }),
        ];
    },
};

