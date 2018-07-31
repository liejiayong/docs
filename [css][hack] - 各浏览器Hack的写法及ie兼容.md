在Web页面制作中尽量不要使用CSS Hack来处理兼容问题。因为任何浏览器下出现渲染不一致都极有可能是我们自己的结构或样式不符合W3C的某些要求，或者说违背了浏览器的某些规则而造成的，所以我们应该尽量通过结构或CSS的修改来达到各浏览器渲染一致效果，除非到了万不得已情况下，才考虑CSS的Hack。

## Firefox
    @-moz-document url-prefix() {
    .selector {
    	property: value;
    }
    }

## Webkit内核浏览器(chrome and safari)
    @media screen and (-webkit-min-device-pixel-ratio:0){
    	Selector { property: value; }
    }

## Opera浏览器
    @media all and (min-width:0) {
    	Selector {property: value;}
    }

## IE9浏览器
    :root Selector {property: value\9;}

## IE9以及IE9以下版本
    Selector {property:value\9;}

## IE8浏览器
    @media \0screen{
    	Selector {property: value;}
    }

## IE8以及IE8以上的版本
    Selector {property: value\0;}

## IE7浏览器
    *+html Selector{property:value;}
    或
    *:first-child+html Selector {property:value;}

## IE7及IE7以下版本浏览器
    Selector {*property: value;}

## IE6浏览器
    Selector {_property/**/:/**/value;}
    或者：
    Selector {_property: value;}
    或者：
    *html Selector {property: value;}

# 引用外部文件hack写法

![ie-hack-compact-list](https://gitee.com/liejiayong/json-library/blob/master/article-image/ie-hack-list.jpg)

+ (1) IE条件注释法，即在正常代码之外添加判别IE浏览器或对应版本的条件注释，符合条件的浏览器或者版本号才回执行里边的代码。

      <!--[if IE 6]>
          <link href="ie/ie6.css" rel="stylesheet" type="text/css" />
      <![endif]-->
      <!--[if IE 9]>
          <link href="ie/ie9.css" rel="stylesheet" type="text/css" />
      <![endif]-->
      <!--[if IE 8]>
          <link href="ie/ie8.css" rel="stylesheet" type="text/css" />
      <![endif]-->
      <!--[if IE 7]>
          <link href="ie/ie7.css" rel="stylesheet" type="text/css" />
      <![endif]-->
      <!--[if IE]>
          <link href="ie/ie.css" rel="stylesheet" type="text/css" />
      <![endif]-->
      <!--[if lt IE 9]>
          <script src="html5_shrrr.js"></script>
      <![endif]-->

+ (2)CSS属性前缀法，即是给css的属性添加前缀。比如 * 可以被IE6/IE7识别，但 _ 只能被IE6识别，IE6-IE10都可以识别 "\9"，IE6不能识别!important  FireFox不能识别 * _  \9

可以先使用“\9"标记，将IE分离出来，再用”*"分离出IE6/IE7，最后可以用“_”分离出IE6

        .type{
                color: #111; /* all */
        color: #222\9; /* IE */
                *color: #333; /* IE6/IE7 */
                _color: #444; /* IE6 */
                }

所以可以按着优先级就能给特定的版本捎上特定颜色

可以先使用“\9"标记，将IE分离出来，再用”*"分离出IE6/IE7，最后可以用“_”分离出IE6

        .type{
                color: #111; /* all */
        color: #222\9; /* IE */
                *color: #333; /* IE6/IE7 */
                _color: #444; /* IE6 */
                }

所以可以按着优先级就能给特定的版本捎上特定颜色

为什么说一般呢...你看看下面这个例子，IE6貌似还认得出!important 

其实也能看出来了，当属性一起写在{}里头时，前者肯定会被后者覆盖。要使!important有效，就应置于多个{}间。

            h1{color: #f00 !important; }
            h1{color: #000;}
            h2{color: #f00 !important; color: #000;}
        <h1>test1</h1>
        <h2>test2</h2>
        说明：在标准模式中
            • “-″减号是IE6专有的hack
            • “\9″ IE6/IE7/IE8/IE9/IE10都生效
            • “\0″ IE8/IE9/IE10都生效，是IE8/9/10的hack
            • “\9\0″ 只对IE9/IE10生效，是IE9/10的hack

+ （3）选择器前缀法，顾名思义，就是给选择器加上前缀。

            IE6可识别 *div{color:red;}  
            IE7可识别 *+div{color:red;}
            @media screen\9{...}只对IE6/7生效
            @media \0screen {body { background: red; }}只对IE8有效
            @media \0screen\,screen\9{body { background: blue; }}只对IE6/7/8有效
            @media screen\0 {body { background: green; }} 只对IE8/9/10有效
            @media screen and (min-width:0\0) {body { background: gray; }} 只对IE9/10有效
            @media screen and (-ms-high-contrast: active), (-ms-high-contrast: none) {body { background: orange; }} 只对IE10有效 等等

## 再来看看主要的兼容问题：

+ 最主要也是最常见的，就是浏览器对标签的默认支持不同，所以我们要统一，就要进行CSS reset . 最简单的初始化方法是 *{margin:0; padding:0;} 但不推荐，而且它也并不完善。
贴一个淘宝的样式初始化~

            body, h1, h2, h3, h4, h5, h6, hr, p, blockquote, dl, dt, dd, ul, ol, li, pre, form, fieldset, legend, button, input, textarea, th, td { margin:0; padding:0; }
                body, button, input, select, textarea { font:12px/1.5tahoma, arial, \5b8b\4f53; }
                h1, h2, h3, h4, h5, h6{ font-size:100%; }
                address, cite, dfn, em, var { font-style:normal; }
                code, kbd, pre, samp { font-family:couriernew, courier, monospace; }
                small{ font-size:12px; }
                ul, ol { list-style:none; }
                a { text-decoration:none; }
                a:hover { text-decoration:underline; }
                sup { vertical-align:text-top; }
                sub{ vertical-align:text-bottom; }
                legend { color:#000; }
                fieldset, img { border:0; }
                button, input, select, textarea { font-size:100%; }
                table { border-collapse:collapse; border-spacing:0; }

+ IE6双边距bug: 块属性标签添加了浮动float之后，若在浮动方向上也有margin值，则margin值会加倍。其实这种问题主要就是会把某些元素挤到了第二行

            <style type="text/css">
                html,body,div{ margin: 0;padding: 0;}
                .wrap{width: 200px; height: 200px; border: 1px solid #333;}
                .box{float: left; /* display:inline */ ;margin-left: 10px; width: 80px; height: 80px; background-color: green;}
                </style>
            </head>
            <body>
            <div class="wrap">
                <div class="box"></div>
                <div class="box"></div>
            </div>
            <script type="text/javascript">
            </script>
            </body>

    + 解决的方式有两个：
        + 1.给float元素添加display：inline 即可正常显示
        + 2.就是hack处理了，对IE6进行 _margin-left:5px;
    
+ IE6双边距bug: 行内属性标签，为了设置宽高，我们经常就会设置成display：block; 这样一来就产生上述的问题。

        解决办法display：inline; 但是这样一来我们就不能设置宽高了，所以呢需要再加个 display:table.
        所以你设置display:block后，再添上display:inline和display:table

+ 上下margin重合问题，相邻的两个div margin-left margin-right 不会重合，但相邻的margin-top margin-bottom会重合。

            .box1{width: 200px;height: 200px; border: 1px solid #333; }
                .mt{margin-top: 10px;}
                .mb{margin-bottom: 10px;}
            <div class="box1 mb"></div>
            <div class="box1 mt"></div>

+ 有些浏览器解析img标签也有不同，img是行内的，一般都会紧接着排放，但是在有些情况下还是会突然出现个间距

            解决办法是给它来个浮动  float 

+ 标签属性min-height是不兼容的，所以使用的时候也要稍微改改

            .box{min-height:100px;height:auto !important; height:100px; overflow:visible;}

+ 超链接访问过后样式混乱，hover样式不出现了。其实主要是其CSS属性的排序问题

            最好按照这个顺序：L-V-H-A
            简单的记法是  love  hate 
            a:link{}  a:visited{}  a:hover{}  a:active{}

+ chrome下默认会将小于12px的文本强制按照12px来解析。

            -webkit-text-size-adjust: none; 

+ png24位的图片在IE6下面会出现背景，所以最好还是使用png8格式的

+ 两种盒子模式：IE盒子模式和W3C标准模式，所以对象的实际宽度也要注意。

            IE/Opera：对象的实际宽度 = (margin-left) + width + (margin-right)
            Firefox/Mozilla：对象的实际宽度= (margin-left) + (border-left-width) + (padding- left) + width + (padding-right) + (border-right-width) + (margin-right)

+ 鼠标的手势也有问题：FireFox的cursor属性不支持hand，但是支持pointer，IE两个都支持；所以为了兼容都用pointer

+ 有个说法是：FireFox无法解析简写的padding属性设置。

            如padding 5px 4px 3px 1px
            必须改为 padding-top:5px; padding-right:4px; padding-bottom:3px; padding-left:1px。
            但我试了一下，发现还是可以解析的，难道是版本的原因？

+ 消除ul、ol等列表的缩进时

            list-style:none;margin:0px;padding:0px;
            其中margin属性对IE有效，padding属性对FireFox有效

+ CSS控制透明度问题：一般就直接 opacity: 0.6 ; IE就 filter: alpha(opacity=60)

            IE6下 filter:progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=60);

+ 有些时候图片下方会出现一条间隙，通常会出现在FF和IE6下面比如

            <div><img src="1.jpg"/></div>
            img{verticle-align:center;}

+ IE6下div高度无法小于10px 

比如定义一条高2px的线条，FF和IE7都正常

但IE6就是10px

解决的办法有两种：添加overflow属性或设置fontsize大小为高度大小  如：

        <div style="height:2px;overflow:hidden;background:#000000;width:778px;"></div>
        <div style="height:2px;font-size:2px;background:#000000;width:778px;">&nbps;</div>

