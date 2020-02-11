const path = require('path');

module.exports = {
    entry: {
        indipetae: './src/js/index.js'
    },
    output: {
        filename: '[name].bundle.js',
        path: path.resolve(__dirname, 'javascripts'),
        libraryTarget: 'var',
        libraryExport: "default",
        library: 'OmekaElasticsearch'
    },
    devtool: 'source-map',
    module: {
        rules: [
            { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" }
        ]
    },
    externals: {
        jquery: 'jQuery'
    },
    watch: true
};