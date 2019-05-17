const { mix } = require('laravel-mix');
require('laravel-mix-merge-manifest');

if (mix.inProduction()) {
    var publicPath = 'publishable/assets';
} else {
    var publicPath = '../../../public/vendor/webkul/pwa/assets';
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js(path.resolve('src/Resources/assets/js/app.js'), 'js/app.js')
    .webpackConfig({
        resolve: {
            alias: {
                '@': path.resolve('src/Resources/assets/sass')
            }
        }
    })
    .copy(path.resolve('src/Resources/assets/images'), publicPath + '/images')
    .sass(path.resolve('src/Resources/assets/sass/app.scss'), 'css/pwa.css')
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}