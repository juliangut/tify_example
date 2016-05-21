'use strict';

module.exports.tasks = {
  browserSync: {
    options: {
      proxy: 'localhost:9000',
      logLevel: 'silent',
      watchTask: true
    },
    application: {
      src: [
        'src/**/*.php',
        'public/**/*.*'
      ]
    }
  }
};
