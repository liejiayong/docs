---
title: JavaScript之惰性模式
---

# 前言

最近受到很多种刺激，也都是生活、工作、学习和专业度上面的事情了,但我需要沉得下来。有时候打开各种技术论坛，不知不觉之间已经看过了同一话题超过三次了---有关js的惰性模式。正所谓相见不如偶遇，在我看来三次已经是很大的概率了，因此我觉得自己应该静下心来总结一下JavaScript的惰性设计模式，这会是代码性能优化的一个不错小杆子。

# 总结在前

#### 惰性初始模式

概念：[是一種拖延戰術。在第一次需求出現以前，先延遲創建物件、計算值或其它昂貴程序。](https://zh.wikipedia.org/wiki/%E6%83%B0%E6%80%A7%E5%88%9D%E5%A7%8B%E6%A8%A1%E5%BC%8F?_blank)( 是一种允许我们延迟初始化消耗资源比较大的进程，直到需要他们的时候（才初始化）).

通俗理解：使用一种策略可以让代码在特定情景时才**被初始化**运行，从而减少代码每次执行时的重复性判断，提高效率

使用情景：在只需要判断一次就可以复用代码的环境下。常见的有浏览器中的window对象（dom处理、事件处理、XMLHTTPRequest等），线程池、全局缓存。

案例：有个点击事件触发动态创建的浮窗，而这个浮窗是唯一的，在没有遮罩的情况下我可以一直点击创建，这样一来就会创建很多节点，可是只能创建一次，这时候就需要用到。

两种实现方法：1.加载即执行 2.惰性执行

特点：初始化，即函数在初始化之后，后面的复用能起到惰性

区别：
加载即执行：js文件加载后，函数被重新定义
惰性执行：js文件加载后首次被调用后，函数会被重新定义

# 分析

以常用的点击事件为例

### 普通写法为：

    var addEventListener = function(dom, type, fn){

        if(dom.addEventListener){
        
            dom.addEventListener(type, fn, false);
            
        }else if(dom.attachEvent){
        
            dom.attachEvent('on'+type,fn);
            
        }else{
        
            dom['on'+type] = fn;
            
        }
    }

分析：
上述写法在每次调用addEventListener函数的时候，引擎都需要重复判断不同的分支。然而在同一浏览器下只需要判断一下分支就可以重复使用了。

为了优化性能，使用惰性模式来优化性能写法，共有两种写法：

### 加载即执行：

JavaScript文件加载时通过闭包执行对方法进行重新定义，在页面加载时会消耗一定的资源。

    var addEventListener = function(dom, type, fn){

        if(dom.addEventListener){
        
            return function(dom, type, fn){
            
                dom.addEventListener(type, fn, false);
                
            }
            
        }else if(dom.attachEvent){
        
            return function(dom, type, fn){
            
                dom.attachEvent('on'+type,fn);
                
            }
            
        }else{
        
            return function(dom, type, fn){
            
                dom['on'+type] = fn;
                
            }
        } 
    }


### 惰性执行：

第一次执行函数时在函数内部对其进行显示重写，最后调用重写后的方法完成第一次方法调用。

    var addEventListener = function(dom, type, fn){

        if(dom.addEventListener){
        
            addEventListener = dom.addEventListener(type, fn, false);
            
        }else if(dom.attachEvent){
        
            addEventListener = dom.attachEvent('on'+type,fn);
            
        }else{
        
            addEventListener = dom['on'+type] = fn;
            
        }
        
    }

至于性能测试，等我改天有空了就会更新文章，同时嵌入性能测试的结果。
最近事情多也抽了一个多小时写文章，一下子也写到凌晨一点半，明天还要上班，性能测试也不知道等到什么时候有空了再补上吧

# 后会有期！！！