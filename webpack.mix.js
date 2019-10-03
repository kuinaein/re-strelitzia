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

const usePug = {
  loader: 'pug-plain-loader',
  options: { basedir: path.resolve(__dirname, 'resources/js') },
};

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
            use: [usePug],
          },
          {
            use: ['raw-loader', usePug],
          },
        ],
      }],
    },
  })
  .sass('resources/sass/app.scss', 'public/css');
