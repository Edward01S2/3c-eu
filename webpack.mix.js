const mix = require('laravel-mix');
            require('laravel-mix-purgecss');
            require('laravel-mix-copy-watched');

const tailwindcss = require('tailwindcss');
const { resolve } = require('path')
const whitelister = require('purgecss-whitelister')

mix.setPublicPath('./dist')
   .browserSync({
      proxy: '3c.test',
      port: 5000,
    });

mix.sass('assets/scss/all.scss', 'styles')
  .options({
    processCssUrls: false,
    postCss: [ tailwindcss('tailwind.config.js')]
  });
  // .purgeCss({
  //   extensions: ['htm', 'html', 'js', 'php'], 
  //   folders: ['templates', 'lib'],
  //   globs: [
  //     path.join(__dirname, '*.php'),
  //     path.join(__dirname, '/lib/*.php'),
  //     path.join(__dirname, '/templates/*.php')
  //   ],
  //   whitelist: [
  //     require('purgecss-with-wordpress').whitelist,
  //     'fill-current',
  //     'h-20',
  //     'w-20',
  //   ],
  //   whitelistPatterns: require('purgecss-with-wordpress').whitelistPatterns,
  //   whitelistPatternsChildren: [
  //     /^fab/,
  //     /^fas/,
  //     /^far/,
  //     /^fa-/,
  //     /^stateface/,
  //   ],
  // });

mix.js('assets/js/app.js', 'scripts')

mix.copyWatched('assets/images/**', 'dist/images')
  .copyWatched('assets/img/**', 'dist/images')
  .copyWatched('assets/fonts/**', 'dist/fonts');

mix.autoload({
  jquery: ['$', 'window.jQuery', 'jquery'],
});

mix.sourceMaps(false, 'source-map')
   .version();
