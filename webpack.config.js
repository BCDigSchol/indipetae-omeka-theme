const path = require('path');

module.exports = {
    entry: {
        app: './src/js/index.js',
    },
    output: {
        filename: 'indipetae.bundle.js',
        path: path.resolve(__dirname, 'javascripts')
    },
    module: {
        rules: [
            { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader" }
        ]
    },
    watch: true
};