// Requires
var gulp = require('gulp');
var usemin = require('gulp-usemin');
var uglify = require('gulp-uglify');
var minifyCss = require('gulp-minify-css');
var jshint = require('gulp-jshint');
var stylish = require('jshint-stylish');
var del = require('del');

// Variables
var paths = {
    deploy: 'deploy',
    publish: 'publish',
    public: 'public',
    bower: 'bower_components',
    node: 'node_modules'    
};

var files = {};

files.exclude = [
    '!**/' + paths.bower + '/**',
    '!**/' + paths.node + '/**',
    '!' + paths.deploy + '/**',
    '!' + paths.publish + '/**'
];

files = {
    deploy: paths.deploy + '/*',
    publish: paths.publish + '/*',
    public: ['public/**',
            '!public/{script,script/**}',
            '!public/download/**',
            '!public/test.php',
            '!public/vs_startup.php'].concat(files.exclude),
    private: [
        'src/**/*',
        'templates/**/*',
        'vendor/**/*',
        'logs',
        '!logs/**'],
    js: ['**/*.js'].concat(files.exclude)
};


// Functions
function copyFromPublic(toDir) {
    gulp.src(files.public)
        .pipe(usemin({
            assetsDir: 'public',
            css: [minifyCss(), 'concat'],
            js: [uglify()]
        }))
        .pipe(gulp.dest(toDir));
}


// Minimal tasks
gulp.task('clean:publish', function () {
    del([files.publish]);
});

gulp.task('clean:deploy', function () {
    del([files.deploy]);
});

gulp.task('copy:publish', function () {
    copyFromPublic(paths.publish);
});

gulp.task('copy:deploy', function () {
    copyFromPublic(paths.deploy + '/' + paths.public);
    gulp.src(files.private, { "base": "." })
        .pipe(gulp.dest(paths.deploy));
});

gulp.task('lint:js', function () {
    gulp.src(files.js)
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter('jshint-stylish'));
});


// Global tasks
gulp.task('default', ['clean']);
gulp.task('clean', ['clean:deploy', 'clean:publish']);
gulp.task('publish', ['clean:publish', 'lint:js', 'copy:publish']);
gulp.task('deploy', ['clean:deploy', 'lint:js', 'copy:deploy']);
