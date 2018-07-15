---
title: vue引入全局sass引发的报错血案
---

# 琐碎片语
事情是发生在最近使用vue项目的时候使用sass作为css辅助开始的，因为之前使用习惯stylus都是一帆风顺的，而在这次项目里我在main.js 上引入全局sass文件突然报了一下错误，如下：
![vue引入sass全局](https://github.com/liejiayong/docs/blob/master/Blog-images/vue%E5%BC%95%E5%85%A5%E5%85%A8%E5%B1%80sass%E5%BC%95%E5%8F%91%E7%9A%84%E6%8A%A5%E9%94%99%E8%A1%80%E6%A1%88-twicetech.top.png?raw=true)

# vue项目引入全局sass的两种方式：
## 针对自己搭建vue+webpack+sass的项目
这里有个重要的注意事项要讲：看网上很多讲解“vue 引入全局sass”的文章，许多文章都存在着误导，现在我先将一下网上存在误导的大致流程以及我的解决方案。
相信使用vue引入sass的娃可能都有这样的经历：
很高兴在vue项目上使用了sass了， 所以根据网上教程安装个node-sass和sass-loader，接着顺手在webpack.base.conf.js上配置类似如下的选项：
{
   test: /\.scss$/,
   loaders: ["style", "css", "sass"]
}
配置成功，接着以为可以在vue组件上用得风生水起了，
解决当在main.js 文件上引起reset.scss和global.scss文件时出现上图报错，
接着搜索度娘说需要使用sass-resources-loader引入全局sass什么的，
跟着网上的教程安装插件，然后在build/utils.js上配置
    scss: generateLoaders('sass').concat(
      {
        loader: 'sass-resources-loader',
        options: {
          resources: path.resolve('src/common/scss/index.scss')
        }
      }
    ),
上述配置直接再vue项目中引入全局sass样式了，所以在main.js删除相关的引入（import 'xxx.sass'。。。）

## 针对直接使用vue-cli搭建的项目
+ 安装node-sass和sass-loader
+ 在main.js上引入import 'xxx.sass'就大公告成

## 总结
没错，这样的配置可以在vue正确引入sass全局样式，但我想说的是，如果你是使用vue-cli搭建的vue项目的话，因为vue-cli早已在build/utils.js上配置好所有有关css辅助工具（sass、stylus、less。。。）了，

因此如果是使用vue-cli搭建vue项目的话请直接安装好对应的css辅助工具的插件，然后在main.js上引入import 'xxx.sass'就大公告成，sass的配置成功了；

另外如果是自己使用vue+webpack搭建的项目可以重复上面第一步操作，sass的配置也就成功了

