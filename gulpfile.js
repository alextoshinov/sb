var elixir = require('laravel-elixir');
require('laravel-elixir-sass-compass');
require('laravel-elixir-angular');
require('laravel-elixir-livereload');
//require('./tasks/angular.task.js');
//require('./tasks/bower.task.js');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

 var paths = {
 	'jquery'   :'./bower_components/jquery/',
    'modernizr':'./bower_components/modernizr/',
 	'bootstrap':'./bower_components/bootstrap-sass-official/assets/',
    'angular'  :'./bower_components/angular/'
 }

elixir(function(mix) {
    mix
        //.copy(paths.bootstrap + 'stylesheets/**', './resources/assets/sass/bootstrap')
    	//.copy(paths.bootstrap + 'fonts/bootstrap/**', 'public/fonts')

        
    	.compass("frontend/app.scss", 'resources/assets/css', {
            config_file: "config.rb",
    		style: "nested",
    		sass: "resources/assets/sass",
            font: "public/fonts",

            
    	})
        .styles([
            'frontend/app.css',
            'frontend/angular-toastr.css'
         ], 'public/css/frontend.css')
        
        /*
        .compass("*", "./resources/assets/css/frontend/app.css", {
            require: ['susy'],
            config_file: "./config.rb",
            style: "nested"
            sass: "./resources/assets/scss",
            font: "./public/fonts",
            image: "./public/images",
            javascript: "./public/js",
            sourcemap: true,
            comments: true,
            relative: true,
            http_path: false,
            generated_images_path: false
        })
        */
        /*
    	.scripts([
    		paths.jquery + "dist/jquery.js",
            paths.modernizr + "modernizr.js",            
    		paths.bootstrap + "javascripts/bootstrap.js",
            paths.angular + "angular.js"
    		], 'public/js/main.js');
        */
      .livereload([
            
            'public/css/frontend.css'
        ], {
            liveCSS: true
        })  
    /**
      * Apply version control
      */
     .version(["public/css/frontend.css"])
     ;
});
