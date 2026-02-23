// Source Map Configuration for Production
// Add this to your webpack.mix.js or Laravel Mix configuration

const mix = require('laravel-mix');

if (mix.inProduction()) {
    // Generate source maps for production debugging
    mix.sourceMaps();
    
    // Optimize for production
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    drop_console: true, // Remove console.log in production
                },
            },
        },
    });
}

// Compile JS with source maps
mix.js('resources/js/app.js', 'public/js')
    .sourceMaps()
    .version();
