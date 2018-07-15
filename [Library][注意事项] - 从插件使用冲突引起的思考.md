---
title: 从插件使用冲突引起的思考
---

# 前言

正所谓，事出必有因，本文的核心是记录自坑过程，引出插件使用建议。

## 起因

在制作某次专题微端时，专题里面有个类似于电影网站 banner 轮播那样多重事件监听效果（banner 轮播、小图导航、电影简介文本和左右点击），开始时本想着使用自己写的轮播插件完成，这也就避免了本次采坑。但事情本来就不是自己想象中那样，佬大要求使用 jQuery.superSlide.js 插件完成，监于上面的效果，需要调用两次插件函数 `$('xx').slide()` 才能完成功能。

## 问题与排查

问题来了：于是我顺其自然的使用官网上的案例套用模板，然后直接套用

先贴出问题代码，如下：

    <style>
    .jy-server-list{width:383px;}
    .jy-server-list .hd{
        position: relative;
        overflow: hidden;
        width: 208px;
        height: 50px;
        z-index: 1;}
    .jy-server-list .hd .tempWrap{
        margin-left: 25px;
    }
    .jy-server-list .hd .jy-btn{
      width:21px;
      height: 26px;
      z-index: 100;
    }
    .jy-server-list .btn-arrow-lt{
        position: absolute;
        left: 0;
        top: 0;
    }
    .jy-server-list .btn-arrow-rt{
        position: absolute;
        right: 0;
        top: 0;
    }
    .jy-server-list .hd ul{
        position: relative;
        padding: 0 25px;
    }
    .jy-server-list .hd ul li{
        float: left;
        margin-left: 3px;
        display: inline;
        position: relative;
        cursor: pointer;
    }
    .jy-server-list .hd ul li .jy-btn{
        width: 84px;
        height: 25px;
        text-align: center;
        line-height: 25px;
    }
    .jy-server-list .hd ul li:hover .jy-btn,.jy-server-list .hd ul li.on .jy-btn{
        color: #ffab49;
    }
    .scroll_box{
        width: 582px;
        height: 120px;
        overflow: auto;
    }
    .jy-server-list .bd{margin-top:8px;}
    .jy-server-list .bd .server_list .scroll_box .list ul li{
        margin: 0 10px 6px 0;
        float: left;
        width: 131px;
        height: 35px;
        font-size: 12px;
        text-indent: 12px;
        line-height: 35px;
        text-align: center;
        color: #e3c096;
        cursor: pointer;
    }
    .jy-server-list .bd .server_list .scroll_box .list ul li:hover{
        color: #ff0000;
    }
    .jy-server-list .bd .server_list .scroll_box .list ul li:hover a{color: inherit;}
    </style>
    <div id="serverTab" class="jy-server-list">
        <div class="hd">
            <ul id="jy-choose" class="cl jy-choose-wrap">
                <li><a>151-200服</a></li>
                <li><a>101-150服</a></li>
                <li><a>51-100服</a></li>
                <li><a>1-50服</a></li>
            </ul>
            <a id="jpre" href="javascript:;" title="上一个" class="jy-btn btn-arrow-lt">&lt;</a>
            <a id="jnext" href="javascript:;" title="下一个" class="jy-btn btn-arrow-rt">&gt;</a>
        </div>
        <div class="bd">
            <div class="server_list">
                <!-- 151-200服 -->
                <div class="scroll_box">
                    <div class="list">
                        <ul class="cl">
                            <li><a href="#" class="red">200</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                        </ul>
                    </div>
                </div>
                <!-- 151-200服 end -->
                <!-- 101-150服 -->
                <div class="scroll_box">
                    <div class="list">
                        <ul class="cl">
                            <li><a href="#" class="red">150</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                        </ul>
                    </div>
                </div>
                <!-- 101-150服 end -->
                <!-- 51-100服 -->
                <div class="scroll_box">
                    <div class="list">
                        <ul class="cl">
                            <li><a href="#" class="red">100</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                        </ul>
                    </div>
                </div>
                <!-- 51-100服 end -->
                <!-- 1-50服 -->
                <div class="scroll_box">
                    <div class="list">
                        <ul class="cl">
                            <li><a href="#" class="red">50</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                            <li><a href="#" class="red">公测9999服</a></li>
                        </ul>
                    </div>
                </div>
                <!-- 1-50服 end -->
            </div>
        </div>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="../jquery.SuperSlide.2.1.1.js"></script>
    <script type="text/javascript">
        $("#serverTab").slide({
            titCell:".hd li", 
            mainCell:".server_list",  
            autoPlay: false, 
            delayTime: 100
        });
        //  使用superslide菜单切换
        $("#serverTab").slide({
            mainCell: ".hd ul",
            prevCell: "#jpre",
            nextCell: "#jnext",
            effect: "left",
            vis: 6,
            scroll: 6,
            delayTime: 0,
            autoPage: true,
            pnLoop: false
        });    
    </script>

点击链接查看效果[jQuery.superSlide（直接套用模板问题版](https://codepen.io/liejiayong/pen/RBWEqw)效果

套用模板之后，你会发现点击轮播的时候出现问题的，而且这个问题很尴尬

经过几个小时的思考后，最终将思路的重点放获取元素上，也就是 superSlide 代码里面的 $()上，于是查看源码没问题，再仔细查看官方文档发现了蛛丝马迹，顺着思路下去找发现几个问题：

+ 插件的标配元素类名是:
  + .hd ul
  + .hd li
  + .bd ul
+ 本专题也是使用上述的类
+ 本专题业务比较复杂，使用了两次 `$('xx').slide({})`

再深入思考这些类发现，插件完整的配置应该包含几个获取元素的配置属性

    // 现列出[官方标配的类名(http://www.superslide2.com/param.html)
    $('xx').slide({
      targetCell: null,
      pageStateCell: '.pageState',
      titCell: '.hd li',
      mainCell: '.bd',
      prevCell: '.prev',
      nextCell: '.next',
    })

看着这些配置，大佬一面出现千万只曹尼玛奔腾不息，一面想着获取元素问题，突然想着相同脑海里出现一点头绪，“两次调用函数”

哈哈哈，是不是忽然间豁然开朗了，问题的重点在 **“两次调用函数”**

## 解决

接着将代码稍作修改：

    <script type="text/javascript">
        $("#serverTab").slide({
            titCell:".hd li", 
            mainCell:".server_list",  
            autoPlay: false, 
            delayTime: 100
        });
        //  使用superslide菜单切换
        $("#serverTab").slide({

            targetCell: "none",
            titCell: "none",

            mainCell: ".hd ul",
            prevCell: "#jpre",
            nextCell: "#jnext",
            effect: "left",
            vis: 6,
            scroll: 6,
            delayTime: 0,
            autoPage: true,
            pnLoop: false
        });    
    </script>


再点击链接查看效果[jQuery.superSlide（多重嵌套完整版）](https://codepen.io/liejiayong/pen/jpbXmV)效果

好bug修复大功告成，回想起曾经与superSilde一起翻山越海，有时候还是不得不说superSlide还有没有想象中的灵活呀，不过还是感谢作者的无私奉献。

## 由问题引出的思考

不论这是作者初衷还是自己的不完善，问题的起点还是自己，来点自我反思吧。

想一下，如果自己能不那么僵化直接套用现有的，或许不管怎么配置 `$('xx').slide({})` 都不会出现问题的,可能自己太懒了吧。

好，到总结的时候了：

+ 细心的朋友可能已经找到问题根源了，刚才的问题就在于配置两次 `$('xx').slide({})` ，每次配置的时候targetCell、titCell、mainCell、prevCell、nextCell都有默认值，而本次专题我是直接套用官方模板的，所以 在两次配置后 targetCell 和titCell因为没有配置完全，所以存在复用监听的情况，于是出现上面的问题诞生。

## 建议

+ 要养成一个良好的编码习惯，多使用自己风格的代码，避免与他人冲突（这不是重点）
+ 日常积累沉淀、编写自己的程序库 或 插件 (这才是重点，多使用是自己东西)
+ 建议在使用插件别人的时候，不要套用模板，而是使用自己那套规范
+ 养成勤于探究善于思考的习惯
+ 塑造并提升自身的素质

因为

# 年轻人， 
## 你的路
### 还很长

# ~(*^v^*)~
