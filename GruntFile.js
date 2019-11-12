/**
 * Created by pavel on 10.8.17.
 */


module.exports = function(grunt) {

    var wwwDir = 'www/';
    var templateDir = 'app/modules/pexeso-module/src/PexesoModule/Presenters/templates/';
    var layoutFile = templateDir + '@layout.latte';
    var autoPrefixOptions = {browsers: ["last 2 versions", "Android 4.3", "ie 9", "ios_saf 6.0-7.1"]};
    var AutoPrefixPlugin = require('less-plugin-autoprefix');
    var autoPrefix = new AutoPrefixPlugin(autoPrefixOptions);
    var inlineUrlsPlugin = require('less-plugin-inline-urls');
    var groupMediaQueries = require('less-plugin-group-css-media-queries');

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        copy: {
            main: {
                files: [
                    // {expand: true, cwd: '/var/www/html/nivea-2017-02-care/app/modules/front-module/resources/assets/src/', src: ['less/*'], dest: wwwDir + 'src/', filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/bootstrap/dist/', src: ['css/*', 'js/*'], dest: wwwDir + 'assets/bootstrap/', filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/bootstrap/dist/', src: 'fonts/*', dest: wwwDir, filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/font-awesome/', src: ['css/*', 'fonts/*'], dest: wwwDir + '/assets/font-awesome/', filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/jquery/dist/', src: '*', dest: wwwDir + '/assets/jquery/', filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/moment/src/', src: ['locale/*', '*.js'], dest: wwwDir + '/assets/moment/', filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/jquery-smooth-scroll/', src: '*.js', dest: wwwDir + '/assets/jquery-smooth-scroll/', filter: 'isFile'},
                    {expand: true, cwd: 'bower_components/nette-live-form-validation/', src: '*.js', dest: wwwDir + '/assets/nette-live-form-validation/'},
                    {expand: true, cwd: 'bower_components/nette-forms/src/assets/', src: 'netteForms.js', dest: wwwDir + '/assets/nette-forms/'},
                    {expand: true, cwd: 'bower_components/nette.ajax.js/', src: ['nette.ajax.js', 'extensions/*'], dest: wwwDir + '/assets/nette.ajax.js/'},
                    {expand: true, cwd: 'bower_components/history.nette.ajax.js/client-side/', src: '*.js', dest: wwwDir + '/assets/history.ajax.js/'},
                    {expand: true, cwd: 'bower_components/qtip2/basic/', src: '*', dest: wwwDir + '/assets/jquery.qtip/'},
                    {expand: true, cwd: 'bower_components/select2/dist/', src: '**', dest: wwwDir + '/assets/select2/'},
                    {expand: true, cwd: 'bower_components/typeahead.js/dist/', src: '*.js', dest: wwwDir + '/assets/typeahead.js/'},
                    {expand: true, cwd: 'bower_components/modernizr/src/', src: '**', dest: wwwDir + '/assets/modernizr/'},
                    {expand: true, cwd: 'bower_components/background-blur/dist/', src: '**', dest: wwwDir + '/assets/background-blur/'},
                    {expand: true, cwd: 'bower_components/jquery.wait/', src: '*.js', dest: wwwDir + '/assets/jquery.wait/'},
                    // {expand: true, cwd: 'bower_components/jquery-ui/', src: 'themes/base/**', dest: wwwDir + '/assets/jquery-ui/', filter: 'isFile'},
                    // {expand: true, cwd: 'bower_components/jquery-ui/', src: 'jquery-ui.min.js', dest: wwwDir + '/assets/jquery-ui/', filter: 'isFile'},
                    // {expand: true, cwd: 'bower_components/jquery/', src: 'jquery-migrate.min.js', dest: wwwDir + '/assets/jquery/', filter: 'isFile'},
                    // {expand: true, cwd: 'bower_components/jquery-ui/ui/minified/', src: 'jquery-ui.min.js', dest: wwwDir + '/assets/jquery-ui/', filter: 'isFile'},
                    // {expand: true, cwd: 'bower_components/jquery-hashchange/', src: 'jquery.ba-hashchange.min.js', dest: wwwDir + '/assets/jquery-hashchange/', filter: 'isFile'},
                    // {expand: true, cwd: 'bower_components/holderjs/', src: 'holder.js', dest: wwwDir + '/assets/holder/'},
                    // {expand: true, cwd: 'node_modules/hogan.js/web/builds/3.0.2/', src: 'hogan-3.0.2.min.js', dest: wwwDir + '/assets/hogan/'},
                    // {expand: true, cwd: 'bower_components/history.nette.ajax.js/client-side', src: 'history.ajax.js', dest: wwwDir + '/assets/history.ajax.js/'},
                ]
            },
            initBootstrap: {
                expand: true, cwd: 'app/modules/front-module/resources/assets/src/bootstrap/', src: '**', dest: wwwDir + 'src/bootstrap/',

                // Copy if file does not exist.
                filter: function (filepath) {

                    // NPM load file path module.
                    var path = require('path');

                    // Construct the destination file path.
                    var dest = path.join(
                        grunt.config('copy.initBootstrap.dest'),
                        path.basename(filepath)
                    );

                    // Return false if the file exists.
                    return !(grunt.file.exists(dest));
                }
            },
            initLess: {
                expand: true, cwd: 'app/modules/front-module/resources/assets/src/less/', src: ['**'], dest: 'www/src/less/',

                // Copy if file does not exist.
                filter: function (filepath) {

                    // NPM load file path module.
                    var path = require('path');

                    // Construct the destination file path.
                    var dest = path.join(
                        grunt.config('copy.initLess.dest'),
                        path.basename(filepath)
                    );

                    // Return false if the file exists.
                    return !(grunt.file.exists(dest));
                }
            }
        },

        // make a zipfile
        compress: {
            main: {
                options: {
                    archive: '/var/www/archives/nivea/care-0.1.zip'
                },
                files: [
                    {expand: true, src: ['app/**', 'www/**'], dest: '/'},
                    {expand: true, src: ['*.json', '*.js', '.gitignore'], dest: '/'},
                ]
            },
            framework: {
                options: {
                    archive: '/var/www/archives/devrun/framework-0.1.zip'
                },
                files: [
                    {expand: true, cwd: '/var/www/html/devrun-framework/Devrun/', src: ['**'], dest: 'Devrun/', filter: 'isFile'}, // includes files in path
                    {expand: true, cwd: '/var/www/html/devrun-framework/', src: ['*.json', '*.js', '.gitignore'], dest: '/'}, // includes files in path
                ]
            },
            sandbox: {
                options: {
                    archive: '/var/www/archives/devrun/sandbox-0.1.zip'
                },
                files: [
                    {expand: true, cwd: '/var/www/html/devrun-sandbox/', src: ['app/**', 'log/**', 'temp/**', 'www/**', '*.json', '.gitignore'], dest: '/'}, // includes files in path
                ]
            }
        },

        clean: {
            options: {
                'no-write': true,
                force: true
            },
            main: ['temp/cache/*/', 'log/*.log', 'log/*.html'],
            sandbox: ['/var/www/html/nivea/nivea-2017-08-pexeso/temp/cache/*/', '/var/www/html/nivea/nivea-2017-08-pexeso/log/*.txt']
        },



        shell: {
            options: {
                stderr: false
            },
            clearCache: {
                command: 'sh rmcache.sh',
                options: {
                    stdout: true,
                    stderr: true,
                    execOptions: {
                        encoding : 'utf8'
                    }
                }
            },
            projectCommands: {
                command: 'php web/www/index.php',
                options: {
                    stdout: true,
                    stderr: true,
                    execOptions: {
                        encoding : 'utf8'
                    }
                }
            },
            validateSchema: {
                command: 'php index.php orm:validate-schema'
            },
            dumpSchema: {
                command: 'php index.php orm:schema-tool:update --dump-sql'
            },
            dumpSchemaSql: {
                command: 'php index.php orm:schema-tool:update --dump-sql > saved.sql'
            },
            updateSchema: {
                command: 'php index.php orm:schema-tool:update --force'
            },
            ssh: {
                command: 'ssh -i /home/pavel/.ssh/pavel.paulik_ssh.key pavel.paulik@pixmen.cz'
            },
            tester: {
                command: 'c:/wamp/smart-up/vendor/bin/tester -c c:/wamp/bin/php/php5.5.12/php.ini module/modules/front-module/Tests/FrontModuleTests'
            },
            migrationsCreateStructure: {
                command: 'php index.php migrations:create s new'
            },
            migrationsCreateBasic: {
                command: 'php index.php migrations:create b new'
            },
            composerDumpAutoload: {
                command: 'composer dumpautoload -o'
            }
        },


        sync: {
            main: {
                files: [{
                    cwd: '/var/www/html/nivea/nivea-2017-08-pexeso/',
                    src: [
                        'app/**',
                        'js/**',
                        'css/**',
                        'images/**',
                        'fonts/**',
                        'assets/**',
                        '!temp/**',
                        '!log/',
                        'vendor/**',

                        // '**' /* Include everything */
                        // '!**/*.txt' /* but exclude txt files */
                    ],
                    dest: '/home/pavel/MT5/nivea-2017-08-pexeso/'
                }],
                updateAndDelete: true,
                ignoreInDest: ["temp/**", "log/**", "vendor/**", "*", '**/.*'],
                compareUsing: "md5",
                // pretend: true, // Don't do any IO. Before you run the task with `updateAndDelete` PLEASE MAKE SURE it doesn't remove too much.
                verbose: true // Display log messages when copying files
            },
            cmsDeveloper: {
                files: [{
                    cwd: '/var/www/html/nivea/nivea-2017-08-pexeso/vendor/devrun/',
                    src: [
                        'framework/**',
                        'cms-module/**',
                        'article-module/**',
                        'catalog-module/**'

                        // '**' /* Include everything */
                        // '!**/*.txt' /* but exclude txt files */
                    ],
                    dest: '/var/www/html/devrun/'
                }],
                updateAndDelete: false,
                ignoreInDest: ["temp/**", "log/**", ".git/**", ".idea/**"],
                compareUsing: "md5",
                pretend: true, // Don't do any IO. Before you run the task with `updateAndDelete` PLEASE MAKE SURE it doesn't remove too much.
                verbose: true // Display log messages when copying files
            }
        },

        chmod: {
            options: {
                mode: '775'
            },
            mainWritable: {
                // Target-specific file/dir lists and/or options go here.
                src: ['temp/cache/', 'log/']
            }
        },

        less: {
            development: {

                options: {
                    // paths: ["htdocs/design/images", "/var/www/html/smart-up/htdocs"],  // # musí být uvedena abosolutní cesta [Linux?]
                    paths: ["app/modules/pexeso-module/resources/public/images"],
                    // yuicompress: false,
                    optimization: 2,
                     sourceMap: true,
                    //sourceMapFilename: "css/index.map",
                    //sourceMapURL: 'index.map',
                    //sourceMapRootpath: 'css/',
                    //sourceMapBasepath: 'htdocs/css'
                    outputSourceFiles: true,
                    //sourceMapFileInline: true,
                    plugins: [
                        autoPrefix,
                        //inlineUrlsPlugin,
                        groupMediaQueries,
                        //new (require('less-plugin-clean-css'))(),
                    ],
                    globalVars: {
                        myModifiedVariable: '"../images"'
                    },
                    modifyVars: {
                        myModifiedVariable: '"../images"'
                    }

                },
                files: {
                    "app/modules/pexeso-module/resources/public/pexeso.css": "app/modules/pexeso-module/resources/src/less/index.less",
                    //"css/bootstrap.css": "src/less/bootstrap.less",
                }
            },
            production: {
                options: {
                    compress: false,
                    yuicompress: false,
                    // optimization: 2,
                    paths: ["app/modules/pexeso-module/resources/public/images"],
                    plugins: [
                        autoPrefix,
                        inlineUrlsPlugin,
                        groupMediaQueries,
                        new (require('less-plugin-clean-css'))(),
                    ],
                    modifyVars: {
                        //imgPath: '"http://mycdn.com/path/to/images"',
                        //bgColor: 'red'
                    }
                },
                files: {
                    "app/modules/pexeso-module/resources/public/pexeso.min.css": "app/modules/pexeso-module/resources/src/less/index.less",
                    // "css/index.min.css": "src/less/index.less",
                    //"css/bootstrap.min.css": "src/less/bootstrap.less",
                }
            }
        },

        modernizr: {
            dist: {
                "crawl": false,
                "parseFiles": true,
                "customTests": [],
                "devFile": "js/modernizr.js",
                "dest": "js/modernizr.js",
                "outputFile": "js/modernizr.min.js",
                "tests": [
                    "svg",
                    "cssanimations",
                    "cssfilters",
                    "cssremunit",
                    "csstransforms",
                    "csstransforms3d",
                    "csstransitions",
                    "placeholder",
                    "svgfilters",
                    "preserve3d",
                    "flexbox",
                    "touchevents"
                ],
                "options": [
                    "setClasses"
                ],
                "uglify": false
            }
        },

        useminPrepare: {
            html: [layoutFile],
            options: {dest: '.'}
        },
        netteBasePath: {
            task: {
                basePath: '.',
                options: {
                    // removeFromPath: ['app\\modules\\front-module\\src\\Presenters\\templates\\']
                    removeFromPath: [templateDir]
                }
            }
        },

        uglify: {
            options: {
                compress: {
                    global_defs: {
                        "DEBUG": false
                    },
                    dead_code: true
                }
            }
        },

        cachebreaker: {
            dev: {
                options: {
                    match: ['pexeso.min.js', 'main-pexeso.min.css']
                },
                files: {
                    src: [layoutFile]
                }
            }
        },

        browserSync: {
            dev: {
                options: {
                    //watchTask: true,
                    proxy: "http://localhost/nivea/nivea-2019-10-pexeso",
                    browser: ["firefox"],   // "google chrome",
                    //reloadOnRestart: false,
                    //reloadDelay: 2000,
                    //reloadDebounce: 2000
                    files: [
                        'resources/pexesoModule/pexeso.css',
                        'css/bootstrap.css',
                    //    'htdocs/css/*.css',
                        //'htdocs/js/**/*.js',
                        'app/modules/**/src/**/*.latte',
                        'app/modules/**/src/**/*.php'
                    ]
                    //server: {
                    //    baseDir: "htdocs"
                    //}
                }
            }
        },

        watch: {
            options: {
                spawn: true
            },

            less: {
        		files: ['app/modules/pexeso-module/resources/src/**/*.less'],  // 'www/src/less/*/*.less'
        		tasks: ['less:development' /*, 'less:production' */  ]
        	},
            final: {
        		files: ['src/**/*.less', 'js/*.js'],  // 'www/src/less/*/*.less'
        		tasks: ['less:development', 'less:production', 'useminPrepare', 'netteBasePath', 'concat', 'uglify', 'cssmin', 'cachebreaker', 'sync' ]
        	},
            javascript: {
        		files: ['js/*.js'],
        		tasks: ['cachebreaker' ]
        	}
        }
    });

    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-usemin');
    grunt.loadNpmTasks('grunt-nette-basepath');
    grunt.loadNpmTasks('grunt-cache-breaker');
    grunt.loadNpmTasks('grunt-shell');
    grunt.loadNpmTasks('grunt-sync');
    // grunt.loadNpmTasks('grunt-contrib-clean');
    // grunt.loadNpmTasks('grunt-chmod');
    grunt.loadNpmTasks('grunt-contrib-less');
    // grunt.loadNpmTasks("grunt-modernizr");
    grunt.loadNpmTasks('grunt-browser-sync');

    grunt.registerTask('watch-default', ['watch:less']);
    grunt.registerTask('watch-browser', ['browserSync']);
    grunt.registerTask('watch-final', ['watch:final']);

    grunt.registerTask('default', ['less']);
    grunt.registerTask('nette-resources', ['useminPrepare', 'netteBasePath', 'concat', 'uglify', 'cssmin', 'cachebreaker']);

};
