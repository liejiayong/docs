总结的的起源于一次同事说在360浏览器可以使用scrollTop，在chrome和Firefox不能正常使用这情景。

开始的时候使用window操作完全没有问题，但使用到body，html之后，各浏览器的不同体验让我诧异，肯定兼容性在作怪。在搞清楚其中渊源之前，先看一些总结

<h3 class="border-solid-left">
先看效果
</h3>

<script async src="//jsfiddle.net/CHOVitaminL/8tu7tgvm/6/embed/"></script>

各浏览器下 scrollTop的差异
IE6/7/8：
对于没有doctype声明的页面里可以使用:document.body.scrollTop 来获取scrollTop高度 ；
对于有doctype声明的页面则可以使用 :document.documentElement.scrollTop；

Safari:
safari 比较特别，有自己获取scrollTop的函数 ： window.pageYOffset ；

Firefox:
火狐等等相对标准些的浏览器:直接用 :document.documentElement.scrollTop ；

关于window.pageYOffset(Safari)被放置在 || 的中间位置的问题：
因为当数字0 与 undefine 进行 或运算时，系统默认返回最后一个值。即或运算中 0 == undefine ;
当页面滚动条刚好在最顶端，即scrollTop值为0时，IE下window.pageYOffset(Safari)返回为 undefine ，此时将window.pageYOffset(Safari)放在或运算最后面时,scrollTop返回undefine,undefine 用在接下去的运算就会报错;
而其他浏览器无论 scrollTop 赋值或运算顺序如何都不会返回undefine.可以安全使用..
所以说到头还是IE的问题,杯具…
不过最后总结出来这句实验过OK，大家放心使用；
let scrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;

<h3 class="border-solid-left">
DTD相关说明：
</h3>

1.页面具有 DTD，或者说指定了 DOCTYPE 时，使用document.documentElement。
2.页面不具有DTD，或者说没有指定了 DOCTYPE 时，使用document.body。
在 IE 和 Firefox 中均是如此。
为了兼容，不管有没有 DTD，可以使用如下代码：
var scrollTop = window.pageYOffset  //用于FF
                || document.documentElement.scrollTop
                || document.body.scrollTop
                || 0;

documentElement 和 body 相关说明：
body是DOM对象里的body子节点，即 <body> 标签；
documentElement 是整个节点树的根节点root，即<html> 标签；
DOM把层次中的每一个对象都称之为节点，就是一个层次结构，你可以理解为一个树形结构，就像我们的目录一样，一个根目录，根目录下有子目录，子目录下还有子目录。
以HTML超文本标记语言为例：整个文档的一个根就是,在DOM中可以使用document.documentElement来访问它，它就是整个节点树的根节点。而body是子节点，要访问到body标签，在脚本中应该写：document.body。

下面着重说一下$(”body“).animate({“scrollTop”:top})不被Firefox支持的问题
$("body").animate({"scrollTop":top})
不被Firefox支持问题的解决。
其实是因为使用了body的：
$("body").animate({"scrollTop":top})
只被chrome支持，而不被Firefox支持。
而使用html的：
$("html").animate({"scrollTop":top})
只被Firefox支持，而不被chrome支持。
如果想让这段js被chrome和Firefox都支持的话，应该这样：
$("html,body").animate({"scrollTop":top})
看到了吗，就是将html和body这两者都加上就可以了。

然而在使用jQuery的时候

在浏览器兼容性亲测中（2017-10-17）
发现
各大最新浏览器都不兼容$('body')

使用JQuery时，要直接使用在jQuery获取document对象是应该使用：$("html,body").animate({scrollTop: height}, 500);来获取文档
