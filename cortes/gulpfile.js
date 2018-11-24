var autoprefixer = require('gulp-autoprefixer');
var browserSync = require('browser-sync').create();
var cleanCSS = require('gulp-clean-css');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var gulp = require('gulp');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

gulp.task('sass-cmarp', function(){
 return gulp.src('./src/scss/main.scss')
  .pipe(sourcemaps.init())
  .pipe(sass().on('error', sass.logError))
  .pipe(cleanCSS())
  .pipe(autoprefixer({
   browsers: ['last 10 versions'],
   cascade: false
  }))
  .pipe(sourcemaps.write('/', {
   includeContent: false,
   sourceRoot: 'src/scss/main.scss'
  }))
  .pipe(gulp.dest('dist/css'))
  .pipe(browserSync.stream({match: '**/*.css'}));
});

gulp.task('uglify', function(){
 return gulp.src('src/js/*.js')
  .pipe(uglify())
  .pipe(rename({ suffix: '.min' }))
  .pipe(gulp.dest('dist/js'));
});

gulp.task('default', function(){
 gulp.start('sass-cmarp');
 gulp.start('uglify');
 gulp.watch('src/scss/**/*.scss', ['sass-cmarp']);
 gulp.watch('src/js/*.js', ['uglify']);
 browserSync.init({
        server: {
            baseDir: "/"
        }
    });
 gulp.watch('src/js/*.js').on('change', browserSync.reload);
 gulp.watch('*.html').on('change', browserSync.reload);
});