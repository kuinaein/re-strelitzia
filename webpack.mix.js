/* eslint-env node */
const mix = require('laravel-mix');
const path = require('path');

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

mix.js('resources/js/app.js', 'public/js')
  .webpackConfig({
    resolve: {
      extensions: ['.js', '.json', '.vue'],
      alias: {
        '@': path.resolve(__dirname, './resources/js'),
        vue$: 'vue/dist/vue.runtime.esm.js',
      },
    },
    module: {
      rules: [{
        test: /\.pug$/,
        oneOf: [
          {
            resourceQuery: /^\?vue/,
            use: ['pug-plain-loader'],
          },
          {
            use: ['raw-loader', 'pug-plain-loader'],
          },
        ],
      }],
    },
  })
  .sass('resources/sass/app.scss', 'public/css');
