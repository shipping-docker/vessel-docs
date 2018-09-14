var gulp = require('gulp');
var elixir = require('laravel-elixir');
var bin = require('./tasks/bin');

elixir.config.assetsPath = 'source/_assets';
elixir.config.publicPath = 'source';

elixir(function(mix) {

    mix.sass('main.scss')
        .webpack('main.js')
        // .exec(bin.path() + ' build ' + 'local', ['./source/*', './source/**/*', '!./source/_assets/**/*'])
        // .browserSync({
        //     port: port,
        //     server: { baseDir: 'build_' + env },
        //     proxy: null,
        //     files: [ 'build_' + env + '/**/*' ]
        // });
});

