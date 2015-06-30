module.exports = function(grunt) {

    // Project configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            
            js: {
                src: 'js/bh-slideshow.js',
                dest: '',
                expand: true, 
                ext: '.min.js',
            },
        },

        sass: {
            dist: {
                options: {
                    style: 'compressed',
                },

                files: {
                    'css/main.css' : 'scss/main.scss',
                    'css/general.css' : 'scss/general.scss',
                    'css/rtl.css' : 'scss/rtl.scss'
                }
                /*
                files: [{
                    src: ['scss/*.scss'],
                    dest: './css',
                    expand: true,
                    flatten: false, 
                    ext: '.css',
                }]
                */  
            }
        },

        watch: {
            css: {
                files: 'scss/*.scss',
                tasks: ['sass']
            }
        },

        karma: {
            unit: {
                configFile: 'js/test/karma.conf.js'
            }
        }
    });

    // Load plugins
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-karma');

    // Default tasks
    grunt.registerTask('default', ['uglify', 'sass']);

};
