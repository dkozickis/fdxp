module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            production: {
                options: {
                    paths: ["vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/less"]
                },
                files: {
                    "web/css/bootstrap-braincrafted.css": "vendor/braincrafted/bootstrap-bundle/Braincrafted/Bundle/BootstrapBundle/Resources/less/form.less"
                }
            }
        },
        bowercopy: {
            options: {
                srcPrefix: 'bower_components'
            },
            scripts: {
                options: {
                  destPrefix: 'web/js'
                },
                files: {
                    'jquery.js': 'jquery/dist/jquery.js',
                    'jquery-json.js': 'jquery-json/src/jquery.json.js',
                    'bootstrap.js': 'bootstrap/dist/js/bootstrap.js',
                    'bootstrap-fileinput.js': 'bootstrap-fileinput/js/fileinput.js',
                    'bootstrap-switch.js': 'bootstrap-switch/dist/js/bootstrap-switch.min.js'
                }
            },
            stylesheets: {
                options: {
                  destPrefix: 'web/css'
                },
                files: {
                    'bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
                    'bootstrap-fileinput.css': 'bootstrap-fileinput/css/fileinput.css',
                    'bootstrap-datepicker.css': 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
                    'bootstrap-switch.css': 'bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.min.css'

                }
            }
        },
        concat: {
            options: {
                stripBanners: true
            },
            css: {
                src: [
                    'web/css/bootstrap.css',
                    'web/css/bootstrap-fileinput.css',
                    'web/css/bootstrap-braincrafted.css',
                    'web/css/bootstrap-switch.css',
                    'web/css/bootstrap-datepicker.css',
                    'src/AppBundle/Resources/public/css/*.css'
                ],
                dest: 'web/css/bundled.css'
            },
            js : {
                src : [
                    'web/js/jquery.js',
                    'web/js/jquery-json.js',
                    'web/js/bootstrap.js',
                    'web/js/bootstrap-fileinput.js',
                    'web/js/bootstrap-switch.js'
                ],
                dest: 'web/js/bundled.js'
            }
        },
        cssmin : {
            bundled:{
                src: 'web/css/bundled.css',
                dest: 'web/css/bundled.min.css'
            }
        },
        uglify : {
            js: {
                files: {
                    'web/js/bundled.min.js': ['web/js/bundled.js'],
                    'web/js/leaflet-ajax.js': ['src/AppBundle/Resources/public/js/leaflet-ajax.js']
                }
            }
        },
        purifycss:{
            options: {},
            target: {
                src: ['src/AppBundle/Resources/views/*/*.twig', 'src/AppBundle/Resources/views/*.twig'],
                css: ['web/css/bundled.css'],
                dest: 'web/css/purestyle.css'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-uncss');
    grunt.loadNpmTasks('grunt-purifycss');

    grunt.registerTask('default', ['less', 'bowercopy', 'concat', 'cssmin', 'uglify']);
};