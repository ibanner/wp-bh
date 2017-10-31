module.exports = function(grunt) {

	// Project configuration
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},

			js: {
				src: ['js/*.js'],
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
					src: ['*.scss'],
					dest: 'css/',
					expand: true,
					flatten: false, 
					ext: '.css'
				}]
			}
		},

		svgstore: {
			options: {
				prefix: 'shape-',
				includeTitleElement: false
			},

			default: {
				files: {
					'images/general/svg-defs.svg': ['images/svgs/*.svg']
				}
			}
		},

		watch: {
			js: {
				files: ['js/*.js'],
				tasks: ['uglify']  
			},

			css: {
				files: ['scss/*.scss'],
				tasks: ['sass']
			},

			svg: {
				files: ['images/svgs/*.svg'],
				tasks: ['svgstore']
			}
		}
	});

	// Load plugins
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-sass');
	grunt.loadNpmTasks('grunt-svgstore');
	grunt.loadNpmTasks('grunt-contrib-watch');

	// Default tasks
	grunt.registerTask('default', ['uglify', 'sass', 'svgstore']);

};