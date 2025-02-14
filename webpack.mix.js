const mix = require('laravel-mix');

mix.setPublicPath('resources/assets');

mix.js('resources/js/app.js', 'js')
   .sass('resources/sass/app.scss', 'css')
   .copy('node_modules/ace-builds/src-min', 'resources/assets/js/ace')
   .copy('node_modules/@fortawesome/fontawesome-free/webfonts', 'resources/assets/webfonts')
   .version();

// Copy third-party libraries
mix.copy('node_modules/jquery/dist/jquery.min.js', 'resources/assets/js/lib')
   .copy('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js', 'resources/assets/js/lib')
   .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'resources/assets/css/lib');
