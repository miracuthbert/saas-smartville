let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/assets/js/app.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css');

// admin theme based on bootstrap 4 + CoreUI
mix.js('resources/assets/js/admin.js', 'public/js')
    .sass('resources/assets/sass/admin/admin.scss', 'public/css');

// default theme assets
mix.js('resources/themes/default/assets/js/app.js', 'public/themes/default/js')
    .sass('resources/themes/default/assets/sass/app.scss', 'public/themes/default/css');

var LiveReloadPlugin = require('webpack-livereload-plugin');

mix.webpackConfig({
    plugins: [
        new LiveReloadPlugin()
    ]
});