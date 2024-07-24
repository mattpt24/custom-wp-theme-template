
let mix = require('laravel-mix');
mix.js('src/js/script.js', './').sass('src/scss/style.scss', './').setPublicPath('./');

// var LiveReloadPlugin = require('webpack-livereload-plugin');

// mix.webpackConfig({
//     plugins: [new LiveReloadPlugin()]
// });