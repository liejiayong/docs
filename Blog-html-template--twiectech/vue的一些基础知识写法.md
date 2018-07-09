<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<h3 class="border-solid-left">
    #总结在前
</h3>
<p>本次的总结就是没总结，因为没一点都是总结 ^v^</p>
<div class="padding-default">
    <h3>关于绑定</h3>
    <p>{{msg}} 双向绑定--输出字符串</p>
    <p>{{ * msg}} 单向绑定--v-once</p>
    <p>{{{msg}}}双向绑定--解析输出字符串，如果为dom时解析dom效果</p>

    <ul class="item-list">
        <li>v-bind 绑定特性(元素的特性attribute)</li>
        <li>v-on 绑定事件</li>
    </ul>
    <h3>v-bind和v-on的缩写</h3>
    <p>Vue.js为最常用的两个指令v-bind和v-on提供了缩写方式。</p>
    <ul class="item-list">
        <li>>v-bind指令可以缩写为一个冒号
            <p>&lt;div :href="variable">xxx&lt;/div></p>
        </li>
        <li>v-on指令可以缩写为@符号
            <p>&lt;div @href="variable">xxx&lt;/div></p>
        </li>
    </ul>
</div>

<div class="padding-default">
    <h3>v-show和v-if</h3>
    <p>v-show 判断条件False设置style=“display:none”</p>
    <p>v-else 紧跟在v-if 或 v-show 后面使用</p>
    <h3>过滤器：</h3>
    <p>过滤器可以用在两个地方：mustache 插值和 v-bind 表达式。过滤器应该被添加在 JavaScript 表达式的尾部，由“管道”符指示：</p>
    <p><!-- in mustaches --></p>
    <p>{{ message | capitalize }}</p>
    <p><!-- in v-bind --></p>
    <p>Vue(</p>
    <p>filter:{</p>
    <p>filterfun:function(){</p>
    <p>}</p>
    <p>}</p>
    <p>});</p>
</div>

<div class="padding-default">
    <h3>路由：</h3>
    <ul class="item-list">
        <li>1.路由map</li>
        <li>2.路由视图</li>
        <li>3.路由导航</li>
    </ul>
    <h4>keep-alive 配合router-link的使用 =》在每次访问页面之后，被访问组件会有缓存，增强用户体验</h4>
    <h4>to=</h4>
    <p>可以是v-bind绑定 ：to=""，里面可以跟对象 :to = {path:"/pathName"},</p>
    <p>也可以是字符串 :to = " ' /pathName ' "</p>
    <h4>tag=</h4>
    <p>可以绑定router-link的标签，tag="li"等</p>
    <h3>访问路由可以直接使用</h3>
    <ul class="item-list">
        <li>1.path来访问</li>
        <li>2.name来访问</li>
    </ul>
</div>
<div class="padding-default">
    <h3>路由一些写法：</h3>
    <p>import Router from 'vue-router';</p>
    <p>vue.use(Router);</p>
    <p>let router = new Router(</p>
    <p>{</p>
    <p>routes:[</p>
    <p>{</p>
    <p>path:' / ',--------------------------------------指定组件路由路径</p>
    <p>name: ' routerName ',</p>
    <p>components:' routerComponentsName ' ---设置组件名，用于调用</p>
    <p>或</p>
    <p>compnents：{</p>
    <p>viewA:apple,</p>
    <p>viewB:redapple</p>
    <p>}--------------------------------------------此方法为”路由命名视图“</p>
    <p>}</p>
    <p>]</p>
    <p>}</p>
    <p>);</p>
</div>

</body>
</html>