// require : settings
const gulp = require('gulp');
const packageImporter = require('node-sass-package-importer');
const rename = require("gulp-rename");

// require : scss
const plumber = require('gulp-plumber');
const notify = require('gulp-notify');
const sass = require('gulp-sass');
const sassGlob = require('gulp-sass-glob');
const autoprefixer = require('autoprefixer');
const cssdeclsort = require('css-declaration-sorter');
const mmq = require('gulp-merge-media-queries');
const postcss = require('gulp-postcss');

// require : css
const gulpStylelint = require('gulp-stylelint');
const header = require('gulp-header');
const cleanCSS = require('gulp-clean-css');

// require : js
const uglify = require('gulp-uglify');
const webpack = require('webpack');
const webpackStream = require('webpack-stream');

// require : images
const imagemin = require('gulp-imagemin');
const pngquant = require('imagemin-pngquant');
const mozjpeg = require('imagemin-mozjpeg');

// require : ローカルサーバー
const browserSync = require('browser-sync');
const connect = require('gulp-connect-php');

// task : scss
gulp.task('scss', function() {
  return gulp
    .src( './src/scss/style.scss' )
    .pipe( plumber({ errorHandler: notify.onError( 'Error: <%= error.message %>' ) }) )
    .pipe( sassGlob() )
    .pipe( sass({
      outputStyle: 'expanded',
      importer: packageImporter({
          extensions: ['.scss', '.css']
      }),
      includePaths: require('node-reset-scss').includePath
    }) )
    .pipe( postcss([ autoprefixer() ]) )
    .pipe( postcss([ cssdeclsort({ order: 'alphabetically' }) ]) )
    .pipe( mmq() )
    .pipe( gulp.dest( 'dist/css' ) );
});

// task : css
gulp.task('css', function(){
  return gulp
    .src( './dist/css/style.css' )
    .pipe( header('@charset "utf-8";\n') )
    .pipe( cleanCSS() )
    .pipe( rename({ extname: '.min.css' }) )
    .pipe( gulp.dest( 'dist/css' ) );
});

// task : html
gulp.task('php', function(){
  return gulp
    .src(['./src/php/**/*.php'])
    .pipe(plumber({
        handleError: function(err){
            this.emit('end');
        }
    }))
    .pipe(rename({extname: '.php'}))
    .pipe(gulp.dest('./dist'));
});

// task : images
gulp.task('imagemin', function () {
  return gulp
    .src('src/images/*.{jpg,jpeg,png,gif,svg}')
    .pipe(imagemin([
      pngquant({
        quality: [.65, .85],
        speed: 1
      }),
      mozjpeg({
        quality: 85,
        progressive: true
      })
    ]))
    .pipe(gulp.dest('dist/images'));
});

// task : webpack
gulp.task('webpack',function(){
  return webpackStream({
    entry: './src/js/script.js',
      output: {
        filename: 'script.js'
      }
    }, webpack)
    .pipe(gulp.dest('dist/js'));
});
// task : js minify
gulp.task('minjs', function() {
  return gulp.src("dist/js/script.js")
    .pipe(uglify())
    .pipe(rename({ suffix: '.min' }))
    .pipe(gulp.dest('dist/js/'));
});

// gulp.task('connect-sync', function() {
//   connect.server({
//     base: './dist/'
//   }, function (){
//     browserSync({
//       proxy: 'localhost:8000'
//     });
//   });
  
//   const browserReload = () => {
//     browserSync.reload();
//   };
//   gulp.watch('src/scss/**/*.scss').on('change', gulp.series('scss', 'css', 'imagemin', browserReload));
//   gulp.watch('src/**/*.php').on('change', gulp.series('php', 'imagemin', browserReload));
//   gulp.watch('src/**/*.js').on('change', gulp.series('webpack', 'minjs', 'imagemin', browserReload));
// });

// 監視
function watchFiles(done) {
  gulp.watch('src/scss/**/*.scss').on('change', gulp.series('scss', 'css', 'imagemin'));
  gulp.watch('src/**/*.php').on('change', gulp.series('php', 'imagemin'));
  gulp.watch('src/**/*.js').on('change', gulp.series('webpack', 'minjs', 'imagemin'));
}

gulp.task('default',
  gulp.series(
    'scss', 'css',
    'php',
    'webpack', 'minjs',
    'imagemin',
    // 'connect-sync',
    watchFiles)
);