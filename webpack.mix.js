const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .vue() // Cette ligne gère automatiquement le chargement des fichiers .vue
   .postCss('resources/css/app.css', 'public/css', [
       //
   ]);
