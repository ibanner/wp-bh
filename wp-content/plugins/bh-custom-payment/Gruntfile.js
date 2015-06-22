module.exports = function(grunt) {

    // Project configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            
            js: {
                src: 'js/bh-custom-payment.js',
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
                    'css/bh-custom-payment.css' : 'scss/bh-custom-payment.scss'
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
