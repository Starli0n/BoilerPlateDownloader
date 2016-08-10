// Requires
var gulp = require('gulp');
var usemin = require('gulp-usemin');
var inject = require('gulp-inject-string');
var uglify = require('gulp-uglify');
var minifyCss = require('gulp-minify-css');
var jshint = require('gulp-jshint');
var rename = require("gulp-rename");
var stylish = require('jshint-stylish');
var del = require('del');
var zip = require('gulp-zip');


// Variables
var project = 'boilerplatedownloader';

var paths = {
    deploy: project,
    publish: 'publish',
    public: 'public',
    bower: 'bower_components',
    node: 'node_modules'
};

paths.deploy_public = project + '/' + paths.public;

var files = {};

files.exclude = [
    '!**/' + paths.bower + '/**',
    '!**/' + paths.node + '/**',
    '!' + paths.deploy + '/**',
    '!' + paths.publish + '/**'
];

files = {
    deploy: paths.deploy + '/**/*',
    publish: paths.publish + '/**/*',
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
    return gulp.src(files.public, { dot : true })
        .pipe(usemin({
            assetsDir: 'public',
            css: [minifyCss(), 'concat'],
            js: [uglify()]
        }))
        .pipe(gulp.dest(toDir));
}

function lintjs() {
    return gulp.src(files.js)
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter('jshint-stylish'));
}


// Minimal tasks
gulp.task('clean:publish', function () {
    return del([files.publish], { dot : true });
});

gulp.task('clean:deploy', function () {
    return del([files.deploy], { dot : true });
});

gulp.task('lint:publish', ['clean:publish'], function () {
    return lintjs();
});

gulp.task('lint:deploy', ['clean:deploy'], function () {
    return lintjs();
});

gulp.task('copy:publish', ['lint:publish'], function () {
    return copyFromPublic(paths.publish);
});

gulp.task('copy:deploy', ['lint:deploy'], function () {
    copyFromPublic(paths.deploy_public);

    gulp.src('deploy.htaccess')
        .pipe(rename('.htaccess'))
        .pipe(gulp.dest(paths.deploy));

    return gulp.src(files.private, { base: "." })
        .pipe(gulp.dest(paths.deploy));
});

gulp.task('base:deploy', ['copy:deploy'], function () {
    return gulp.src(paths.deploy_public + '/index.html')
        .pipe(inject.after('<head>', '\n    <base href="public/" />'))
        .pipe(gulp.dest(paths.deploy_public));
});

gulp.task('zip:deploy', ['base:deploy'], function () {
    return gulp.src(files.deploy, { base: ".", dot : true })
        .pipe(zip(project + '.zip'))
        .pipe(gulp.dest(paths.deploy));
});


// Global tasks
gulp.task('default', ['clean']);
gulp.task(':clean', ['clean:deploy', 'clean:publish']);
gulp.task(':publish', ['clean:publish', 'lint:publish', 'copy:publish']);
gulp.task(':deploy', ['clean:deploy', 'lint:deploy', 'copy:deploy', 'base:deploy', 'zip:deploy']);
