var gulp = require('gulp'),
    less = require('gulp-less');
    watch = require('gulp-watch');
    plumber = require('gulp-plumber');

//注册默认任务
// gulp.task('default', function(){
//     gulp.watch('src/topmenu.less', function(event){
//         console.log('监听到 ' + event.type + ' 事件，相关文件是 ' + event.path);
//     });
// });
gulp.task('testless',function () {
    return gulp.src('src/topmenu.less').pipe(plumber({errorHandler: notify.onError('Error: <%= error.message %>')})).pipe(less()).pipe(gulp.dest('../public/js/plugin/topmenu'));
});
//
gulp.task('watch',function(){
    gulp.watch('src/topmenu.less',['testless']);
});
//
gulp.task('default', ['testless','watch']);