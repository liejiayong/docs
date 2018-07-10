# iframe的一些事
## 简约总结
1、子调用父，父创建方法，子通过parent.XXX()调用(✔)

2、父调用子，子创建方法，父用过获取子iframeObj.contentWindow.XXX()调用

3、子传参父，父创建变量参数，子通过window.parent.XXX = xxx传递

4、子获取父参数（父传参子），父通过iframeObj.contentWindow.XXX， 子window.xxx(✔)

## 约定与定义
+ iframeElement：指的是iframe的DOM元素表示，即用document.getElemenetById()等方法获取 DOM对象
+ iframeId： 指iframe的属性id，如<iframe id=”someid”>
+ iframeName：指iframe的属性name，如<iframe name=”somename”>
+ iframeIndex：从0开始编号的iframe索引，若页面中有N个frame，则其值范围为0~n-1
+ iframeWindow：指的是iframe的window对象
+ 标准浏览器：符合W3C标准的浏览器的统称，如FireFox
+ IE 6/7/8（以下简称IE）和FireFox 5.0(以下简称FF)

## 在父页面中获取iframe的window对象
获得了window对象后，就可以调用iframe页面中定义的方法等。

### IE获取iframe的window对象的6种方法来
+ iframeId
+ window.iframeId
+ window.iframeName
+ window.frames[iframeId]
+ window.frames[iframeName]
+ window.frames[iframeIndex]
+ iframeElement.contentWindow

### FF获取iframe的window对象的3种方法来
+ window.iframeName
+ window.frames[iframeName]
+ iframeElement.contentWindow

### 总结
为了兼容大多数浏览器，应使用iframeElement.contentWindow来获取

    <iframe id="iframe1" name=”iframe1” src="frame1.html"></iframe>     
    <script type="text/javascript">  
        //获取iframe的window对象  
        var iframe = document.getElementById('iframe1').contentWindow;  
    </script>  
    
## 在父页面中获取iframe的document对象

标准浏览器可以通过iframeElement.contentDocument来引用iframe的doument对象；

但是IE浏览器（又是这斯…）不支持，确切说应该是IE 6/7，笔者发现在IE8中已经可以用此方法来获取了；

当然，因为document是window的一个子对象，你也可以先获取iframe的window对象，再通过window.document来引用；

总结两方法来获取

<iframe id="iframe1" src="frame1.html"></iframe>  

        //获取iframe的document对象         
        //方法1  
        const iframe = document.getElementById('iframe1').contentWindow.document;  
      
        //方法2  
        function getIframeDom(iframeId) {  
            return document.getElementById(iframeId).contentDocument || window.frames[iframeId].document;  
        }  
 

## iframe页面获取父页面的window对象

parent：父页面window对象

top：顶层页面window对象

self：始终指向当前页面的window对象（与window等价）

适用于所有浏览器，当拿到了父页面的window对象后，就可以访问父页面定义的全局变量和函数，这在iframe交互里经常用到。

## 父子iframe传参

1.子页面`调用`父页面的`参数`

1.1采用url传值的方式: ？+ &

    // 子 iframe.html
    function getParams(){
        const url = document.location.href;
        const ret = url.substring(url.indexOf("?")+1).split("&");
        ret.push({
            token: +new Date()
        })
        console.log(ret)  // 在本页打印结果
        return ret;      // ret结果返回到父页面
    }
    getParams() // 调用本函数返回
    
1.2定义window变量

    // 父 index.html
    父定义 paramFromParent 传递给子
    const childFrameObj=document.getElementById('childFrame');
    childFrameObj.contentWindow.paramFromParent='userId0007';

    // 子 iframe.html
    子 param 接受父传递的 paramFromParent
    const param = window.paramFromParent;
    const inputObject =  document.getElementsByTagName('input')[0];
    inputObject.value = param;
    

2.子页面`调用`父页面的`方法`

子页面调用父页面方法，parent.方法名（）即可。

    // 父 index.html
    // 2.子页面调用父页面的方法
    // 父定义方法 parentSay() 给 子调用
    function parentSay() {
        const parent = 'parent'
        console.log('子调用父页面\n'+'// 2.子页面调用父页面的方法\n' +
            '    // 父定义方法 parentSay() 给 子调用')
        return parent
    }

    // 子 iframe.html
    // 2.子页面调用父页面的方法
    // 父定义方法 parentSay() 给 子调用
    parent.parentSay()

3.子页面向父页面`传参`

可以理解为在父页面定义了一个变量，子页面调用该变量并且给它赋值。

window.parent.id="123"

    // 父 index.html
    // 3.子页面向父页面`传参`
    // 父页面定义了一个变量parentToken，子页面调用该变量并且给它赋值
    let parentToken = 'parentToken'
    let Obj = {}
    Object.defineProperty(Obj, 'id', {
        enumerable: true,
        configurable: true,
        set: function(val){
            parentToken = val
            console.log('parentToken set:',parentToken)
        },
        get: function(){
            return parentToken
        }
    })
    console.log('parentToken:',parentToken)

    // 子 iframe.html
    // 3.子页面向父页面`传参`
    // 父定义方法 parentSay() 给 子调用
    console.log('parent.parentToken', parent['parentToken'])
    parent.parentToken = 'iframeToken'
    console.log('parent.parentToken',parent.parentToken)

4.父页面`调用`子页面`方法`

    <iframe name="myframe" src="child.html"></iframe>

调用方法：iframeObj.contentWindow.FUNCTION()

    // 父 index.html
    // 4.父页面`调用`子页面`方法`
    // 子定义方法 childrenSay() 给 父调用
    console.log(childFrameObj.contentWindow)
    childFrameObj.contentWindow.childrenSay()

    // 子 iframe.html
    // 4.父页面`调用`子页面`方法`
    // 子定义方法 childrenSay() 给 父调用
    function childrenSay() {
        const childrenSay = 'childrenSay'
        console.log(childrenSay)
        return childrenSay
    }