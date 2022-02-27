module.exports = function (grunt) {
    require("load-grunt-tasks")(grunt);

    grunt.initConfig({
        pkg: grunt.file.readJSON("package.json"),

        makepot: {
            options: {
                exclude: ["node_modules/.*"],
                domainPath: "/languages",
                type: "wp-plugin",
                potHeaders: {
                    "report-msgid-bugs-to":
                        "https://github.com/eric-mathison/wpforms-honeypot/issues",
                    poedit: true,
                    "x-poedit-keywordslist": true,
                },
            },
            files: {
                src: ["**/*.php", "src/*.js"],
            },
        },

        addtextdomain: {
            options: {
                textdomain: "wpforms-honeypot",
                updateDomains: true,
            },
            php: {
                files: {
                    src: ["**/*.php", "!node_modules/**/*.php"],
                },
            },
        },
    });

    grunt.registerTask("build", ["addtextdomain", "makepot"]);
};
