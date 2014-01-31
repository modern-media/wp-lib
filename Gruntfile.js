/* jshint node: true */
module.exports = function (grunt) {
	'use strict';

	// Force use of Unix newlines
	grunt.util.linefeed = '\n';

	RegExp.quote = function (string) {
		return string.replace(/[-\\^$*+?.()|[\]{}]/g, '\\$&')
	};
	// Project configuration.
	grunt.initConfig({


		less: {
			compile: {
				options: {
					strictMath: true
				},
				files: {
					'assets/css/admin.css': 'assets/less/admin.less'
				}
			},
			minify: {
				options: {
					cleancss: true,
					report: 'min'
				},
				files: {
					'assets/css/admin.min.css': 'assets/css/admin.css'
				}
			}
		},
		watch: {

			less: {
				files: [
					'wp-content/themes/ubmtech/assets/less/*.less'

				],
				tasks: 'less'
			}
		}
	});

	// These plugins provide necessary tasks.
	require('load-grunt-tasks')(grunt, {scope: 'devDependencies'});

	// CSS distribution task.
	grunt.registerTask('default', ['less']);
};
