const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .disableSuccessNotifications()
    .options({
        // 啟用程式碼分割
        runtimeChunkPath: 'js',
        // 優化構建
        processCssUrls: false,
    })
    .webpackConfig({
        optimization: {
            splitChunks: {
                chunks: 'all',
                cacheGroups: {
                    vendors: {
                        test: /[\\/]node_modules[\\/]/,
                        name: 'vendors',
                        chunks: 'all',
                    },
                },
            },
        },
    });

if (mix.inProduction()) {
    mix.version()
       .sourceMaps(false) // 關閉 source maps 避免第三方 .map 404 錯誤
       .options({
           // 啟用壓縮
           terser: {
               terserOptions: {
                   compress: {
                       drop_console: true,
                   },
               },
           },
       });
}
