# 使用gulp+browser-sync搭建Sass自动化编译以及自动刷新所需要的插件
## 按照gulp需求插件
### 安装browser-sync（在命令行中输入）

	  npm install --save-dev browser-sync

如果网速比较慢，可以用下面的cnpm命令运行：

	  cnpm install --save-dev browser-sync

### 下面列一些安装其它的插件，如需用到可以安装下面的这些插件

   npm install --save-dev gulp-concat//js合并插件

   npm install --save-dev gulp-uglify//js压缩插件

    npm install --save-dev gulp-cssnano//css压缩插件

    npm install --save-dev gulp-imagemin//图片压缩插件

    npm install --save-dev gulp-htmlmin//html压缩插件

    npm install --save-dev del//文件删除模块

    npm install --save-dev gulp merge-stream//在一个任务中使用多个文件来源

### 搭建gulp环境
#### 按装gulp
1、安装全局gulp

    npm install gulp -g

2、初始化package.json

   npm init

3、在本项目安装引入的gulp

    npm install gulp --save-dev

4、在本项目按照sass相关插件

    npm install --save-dev gulp-sass

5、本步骤按需求来按照，下面gulp相关插件可以搭建完整的css、js相关压缩

   npm install --save-dev gulp-concat gulp-uglify gulp-cssnano gulp-imagemin gulp-htmlmin merge-stream

### 配置gulpfile.js
在项目根目录中新建gulpfile.js文件（重要！！！文件名为固定不变的。）输入以下内容：

    const gulp = require('gulp'); //获取gulp
    const sass = require('gulp-sass');  //获取gulp
    const browsersync = require('browser-sync').create(); //获取browsersync
    const cssnano = require('gulp-cssnano'); //css压缩插件
    const merge = require('merge-stream');

    //操作css文件
    /**
    *  如果是一个任务处理多文件夹的话，
    *  只要声明不同的变量，
    *  然后return merge(xx, xx)合并返回即可
    *  如下 style 任务
    */
    gulp.task('style', function() {
        const scssIndex = gulp.src('./common/scss/*.scss')  //需要编译scss的文件
        .pipe(sass({outputStyle: 'compressed'})   //压缩格式：nested(嵌套)、compact（紧凑）、expanded（扩展）、compressed（压缩）
        .on('error', sass.logError))
        .pipe(cssnano())                 //css压缩
        .pipe(gulp.dest('./common/css'))    //输出路径
        .pipe(browsersync.stream());    //文件有更新自动执行

        const scssComponents = gulp.src('./common/components-scss/*.scss')  //需要编译scss的文件
        .pipe(sass({outputStyle: 'compressed'})   //压缩格式：nested(嵌套)、compact（紧凑）、expanded（扩展）、compressed（压缩）
        .on('error', sass.logError))
        .pipe(cssnano())                 //css压缩
        .pipe(gulp.dest('./common/components-css'))    //输出路径
        .pipe(browsersync.stream());    //文件有更新自动执行
        return merge(scssIndex, scssComponents);
    });

    //监听scss文件
    gulp.task('serve',function() {
        gulp.start('style');
        gulp.watch('./common/scss/*.scss', ['style']);
        gulp.watch('./common/components-scss/*.scss', ['style']);
    });

    //编译scss文件：gulp default
    gulp.task('default',['serve']);