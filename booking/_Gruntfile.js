'use strict';
module.exports = function (grunt) {

    var mozjpeg = require('imagemin-mozjpeg');

    // Load grunt tasks automatically
    require('load-grunt-tasks')(grunt);

    // Time how long tasks take. Can help when optimizing build times
    require('time-grunt')(grunt);

    // Define the configuration for all the tasks
    grunt.initConfig({

        // Watches files for changes and runs tasks based on the changed files
        watch: {
            options: {
                event: ['changed', 'added', 'deleted']
            },

            css: {
                files: 'scss/**/*.scss',
                tasks: ['sass:dist']
                // tasks: ['sass:dist', 'autoprefixer']
            }

            // images: {
            //     files: ['images/**/*.{png,jpg,gif,svg}', '!images/dist/**/*.{png,jpg,gif,svg}', '!images/svg/**/*.{png,jpg,gif,svg}', '!images/svg-fallback/**/*.{png,jpg,gif,svg}'],
            //     tasks: ['newer:imagemin:dist']
            // }
        },

        // Compiles Sass to CSS and generates necessary files if requested
        sass: {
            dist: {
                options: {
                    sourceMap: true,
                    outputStyle: 'expanded'
                    // includePaths: ['bower_components/foundation/scss']
                },
                files: {
                    'css/global.css': 'scss/global.scss' // 'destination': 'source'
                }
            }
        },

        // Minify CSS
        cssmin: {
            dist: {
                expand: true,
                cwd: 'css/',
                src: ['global.css', '!*.min.css'],
                dest: 'css/',
                ext: '.min.css'
            }
        },

        // Minify images
        imagemin: {
            dist: {
                options: {
                    optimizationLevel: 3,
                    svgoPlugins: [{ removeViewBox: false }],
                    use: [mozjpeg()]
                },
                files: [{
                    expand: true,
                    cwd: 'images/',
                    src: ['**/*.{png,jpg,gif,svg}', '!dist/**', '!svg/**', '!svg-fallback/**'],
                    dest: 'images/dist/'
                }]
            }
        },

        uglify:{
            dist: {
                files: {
                    'javascript/misc.min.js': ['javascript/misc.js']
                }
            }
        },

        critical: {
            test: {
                options: {
                    base: './',
                    css: [
                        'css/normalize.css',
                        'css/global.css'
                    ],
                    width: 3000,
                    height: 10000,
                    minify: true
                },
                src: 'critical.html',
                dest: 'css/critical.css'
            }
        }


    });


    grunt.registerTask('build', [
        'uglify',
        'cssmin'
    ]);

    grunt.registerTask('default', [
        'watch'
    ]);


};
