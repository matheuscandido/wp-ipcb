var gulp = require('gulp');
var sass = require('gulp-sass');
var imagemin = require('gulp-imagemin');
const concat = require('gulp-concat');
var header = require('gulp-header');

sass.compiler = require('node-sass');

var fs = require('fs');
var toml = require('toml');

/**
 *  GET CONFIG DATA
 */

const fileData = toml.parse(fs.readFileSync('config.sh', 'utf8'));
const themeName = fileData.THEME_NAME;
const themeAuthor = fileData.THEME_AUTHOR;
const themeAuthorURI = fileData.THEME_AUTHOR_URI;
const themeDescription = fileData.THEME_DESCRIPTION;
const themeVersion = fileData.THEME_VERSION;

/**
 *  CREATE STYLE'S HEADER STRING
 */

const HEADER_STRING = `/*\n\
Theme Name: ${themeName}\n\
Author: ${themeAuthor}\n\
Author URI:  ${themeAuthorURI}\n\
Description: ${themeDescription}.\n\
Version: ${themeVersion}.\n\
*/\n`;

/**
 * SASS COMPILER AND WATCHER
 */

gulp.task('sass', function() {
  return gulp
    .src('scss/**/*.scss')
    .pipe(sass())
    .pipe(concat('style.css'))
    .pipe(header(HEADER_STRING))
    .pipe(gulp.dest('./'));
});

gulp.task('sass:watch', function() {
  return gulp.watch('scss/**/*.scss', gulp.series('sass'));
});

/**
 * IMAGES MINIFICATION AND WATCHER
 */

gulp.task('images-min', function() {
  return gulp
    .src('assets/images/**/*')
    .pipe(
      imagemin({
        interlaced: true,
        progressive: true,
        optimizationLevel: 5,
        svgoPlugins: [
          {
            removeViewBox: true
          }
        ]
      })
    )
    .pipe(gulp.dest('./assets/min-images/'));
});

gulp.task('images-min:watch', function() {
  return gulp.watch('assets/images/**/*', gulp.series('images-min'));
});

/**
 * RUN
 */

gulp.task('default', gulp.parallel(['sass', 'sass:watch', 'images-min', 'images-min:watch']));
