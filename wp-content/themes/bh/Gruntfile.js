module.exports = function(grunt) {

    // Project configuration
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
            },
            
            js: {
                src: ['js/*.js', 'js/forms/*.js'],
                dest: 'js/min/',
                expand: true,
                flatten: true, 
                ext: '.min.js'
            },
        },

        sass: {
            dist: {
                options: {
                    style: 'compressed'
                },
                
                files: [{
                    cwd: 'scss',
                    src: ['*.scss', '**/*.scss'],
                    dest: 'css/',
                    expand: true,
                    flatten: false, 
                    ext: '.css'
                }]   
            }
        },

        watch: {
            css: {
                files: ['scss/*.scss', 'scss/**/*.scss'],
                tasks: ['sass']
            },

            js: {
                files: ['js/*.js', 'js/forms/.*js'],
                tasks: ['uglify']  
            }
        }
    });

    // Load plugins
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default tasks
    grunt.registerTask('default', ['uglify', 'sass']);

};
