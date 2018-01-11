const path = require('path')
const webpack = require('webpack')
const mix = require('laravel-mix')
// const { BundleAnalyzerPlugin } = require('webpack-bundle-analyzer')

mix
  .js('resources/assets/js/app.js', 'public/js')
  .sass('resources/assets/sass/app.scss', 'public/css')
  // .sourceMaps()
  .disableNotifications()

if (mix.inProduction()) {
  mix.version()

  mix.extract([
    'axios',
    'buefy',
    'highlight.js',
    'jquery',
    'js-cookie',
    'laravel-echo',
    'lodash',
    'marked',
    'pusher-js',
    'vform',
    'vue',
    'vue-i18n',
    'vue-meta',
    'vue-router',
    'vue-simplemde',
    'vue-timeago',
    'vuex',
    'vuex-router-sync',
  ])
}

mix.webpackConfig({
  plugins: [
    // new BundleAnalyzerPlugin(),
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      // Popper: ['popper.js', 'default'],
      _: 'lodash'
    })
  ],
  resolve: {
    alias: {
      '~': path.join(__dirname, './resources/assets/js')
    }
  },
  output: { filename: '[name].js', chunkFilename: 'js/[name].app.js', publicPath: '/' }
})
