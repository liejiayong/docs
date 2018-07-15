---
title: promise总结
---

# 总结在前

# 前言
下文类似 Promise#then、Promise#resolve 都是Promise的实例对象，

# 什么是Promise
Promise是抽象异步处理对象以及对其进行各种操作的组件

## Promise简介
### 目前大致有下面三种类型：

+ 1、Constructor

    var promise = new Promise(function(resolve, reject) {
        // 异步处理
        // 处理结束后、调用resolve 或 reject
    });

+ 2、Instance Method

    promise.then(resolved, rejected)
            .catch(rejected)
            .finally()

+ 3、Static Method

    Promise.all()
    Promise.resolve()
    Promise.reject()
    Promise.race()

### 对比

    Promise.resolve() 
    等价于
    new Promise(function(resolve, null){})

## Promise的状态

用new Promise 实例化的promise对象有以下三个状态。

| ES6 Promises 规范中定义的术语 | Promises/A+ 中描述状态的术语 |  状态说明  |
| ----------------------------- | ---------------------------- | ---------- |
| has-resolution                | Fulfilled                    | resolve(成功)时。此时会调用 onFulfilled |
| has-rejection                 | Rejected                     | reject(失败)时。此时会调用 onRejected |
| unresolved                    | Pending                      | 既不是resolve也不是reject的状态。也就是promise对象刚被创建后的初始化状态等 |

触发Promise之后只会是 **resolved** 或 **rejected** 两种，而且状态不可逆

![Promise状态解析图](http://liubin.org/promises-book/Ch1_WhatsPromises/img/promise-states.png "Promise状态解析图")


## 编写Promise
### 创建Promise对象
   
+ 1.new Promise(fn) 返回一个promise对象
    
+ 2.在fn 中 **指定** 异步等处理

    处理结果正常的话，调用resolve(处理结果值)
    处理结果错误的话，调用reject(Error对象)

+ 3.函数处理 Promise对像
    
    then();  --- 为了避免上述中同时使用同步、异步调用可能引起的混乱问题
    catch(); --- catch在ie8下存在兼容性问题
    finally();

#### 实战
    
    function getURL (URL) {
        return new Promise(function(resolve, reject){
            const req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(req.statusText)
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            }
            req.send();
        })
    }

## 实战 Promise中各种方法

### Promise.resolve()
    返回值是一个promise对象
    
静态方法 Promise.resolve() 可以认为是实例 new Promise(function(resolve, undefined){})方法 的快捷方式（语法糖）
    
+ 让promise对象立即进入resolved状态

将 thenable 对象转换为promise对象

+ 返回值是 thenable


    实例：
    Promise.resolve(value).then(function(value){
        console.log(value);
    })

###Promise.reject()
    返回值是一个promise对象
    
静态方法 Promise.resolve() 可以认为是实例 promise.then(undefined, onRejected)方法 的快捷方式（语法糖）
    
+ 让promise对象立即进入rejected状态
+ 使用catch()处理异常


    例如：
    Promise.reject(new Error("BOOM!")).catch(function(error){
        console.error(error);
    });
    

### 专栏: Promise只能进行异步操作？

**Promise在规范上规定 Promise只能使用异步调用方式**思考一个问题：文档的加载顺序都是从上到下加载的，

那么代码的的位置起何等作用？

先看看Promise的执行
    
    var promise = new Promise(function (resolve){
        console.log("inner promise"); // 1
        resolve(42);
    });
    promise.then(function(value){
        console.log(value); // 3
    });
    console.log("outer promise"); // 2
    
执行顺序: 1 > 2 > 3

>同步调用和异步调用同时存在导致的混乱

1.正常代码同时存在 同步 和 异步 的情况:

    function onReady (fn) {
        const readyState = document.readyState;
        if (readyState === 'interactive' || readyState === 'complete') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }
    
    onReady(function(){
       console.log('loaded!') 
    });
    console.log('==starting==');

分析：

**如果在调用onReady之前DOM已经载入的话**

对回调函数进行**同步调用**

**如果在调用onReady之前DOM还没有载入的话**

通过注册 DOMContentLoaded 事件监听器来对回调函数进行**异步调用**

因此

如果这段代码在**源文件**中出现的**位置**不同，在控制台上打印的log消息顺序也会不同。


2.使用 setTimeout() 控制代码 统一异步输出的情况：

        function onReady (fn) {
            const readyState = document.readyState;
            if (readyState === 'interactive' || readyState === 'complete') {
               setTimeout(fn, 0);
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }
        
        onReady(function(){
           console.log('loaded!') 
        });
        console.log('==starting==');
        
关于同步 | 异步这个问题，在 Effective JavaScript 的 第67项 不要对异步回调函数进行同步调用 中也有详细介绍

+ 绝对不能对异步回调函数（即使在数据已经就绪）进行同步调用
+ 如果对异步回调函数进行同步调用的话，处理顺序可能会与预期不符，可能带来意料之外的后果
+ 对异步回调函数进行同步调用，还可能导致栈溢出或异常处理错乱等问题
+ 如果想在将来某时刻调用异步回调函数的话，可以使用setTimeout等异步API

Effective JavaScript
---David Herman

前面我们看到的 promise.then 也属于此类，为了避免上述中同时使用同步、异步调用可能引起的混乱问题，Promise在规范上规定 Promise只能使用异步调用方式 

3.使用Promise重写onReady()

    function onReadyPromise () {
        return new Promise(function(resolve, reject){
            const readyState = document.readtState;
            if (readyState === 'interactive' || readyState === 'complete') {
                resolve();
            } else {
                window.addEventListener('DOMContentLoaded', resolve);
            }
        });
    }
    onReadyPromise().then(function(){
        console.log('loaded!);
    });
    console.log('==starting==');
    
由于Promise保证了每次调用都是以异步方式进行的，所以我们在实际编码中不需要调用 setTimeout 来自己实现异步调用。

### Promise#then

Promise方法链（Promise Chain）。Promise可以将任意方法写在一起作为一个方法链,所以适合编写异步处理较多的应用.(越短越好)

    如：
    function taskA() {
        console.log("Task A");
    }
    function taskB() {
        console.log("Task B");
    }
    function onRejected(error) {
        console.log("Catch Error: A or B", error);
    }
    function finalTask() {
        console.log("Final Task");
    }
    
    var promise = Promise.resolve();
    promise
        .then(taskA)
        .then(taskB)
        .catch(onRejected)
        .then(finalTask);

无异常依次输出：Task A > Task B > Final Task
有异常依次输出： error > Final Task
       
![流程图](http://liubin.org/promises-book/Ch2_HowToWrite/img/promise-then-catch-flow.png)
   
#### Promise Chain 中的传参原理

Promise Chain 之间的传参，只需要在每个事务回调函数中使用 **return** 来返回当前值

    如：
    function doubleUp(value) {
        return value * 2;
    }
    function increment(value) {
        value + 1;
    }
    function output(value) {
        console.log(value);// => (1 + 1) * 2
    }
    
    var promise = Promise.resolve(1);
    promise
        .then(increment)
        .then(doubleUp)
        .then(output)
        .catch(function(error){
            // promise chain中出现异常的时候会被调用
            console.error(error);
        });
        
![流程图](http://liubin.org/promises-book/Ch2_HowToWrite/img/promise-then-passing-value.png)


特点：
+ 每个方法中 return 的值不仅只局限于字符串或者数值类型，也可以是对象或者promise对象等复杂类型。
+ return的值会由 Promise.resolve(return的返回值); 进行相应的包装处理，因此不管回调函数中会返回一个什么样的值，最终 then 的结果都是 **返回** 一个 **新创建的promise对象**。  
+ 也就是说， Promise#then 不仅仅是注册一个回调函数那么简单，它还会将回调函数的返回值进行变换，创建并返回一个promise对象。


### Promise#catch

#### IE8兼容性
![Promise 浏览器兼容性](http://liubin.org/promises-book/Ch2_HowToWrite/img/promise-catch-error.png)

    这段代码在ie8一下出现 identifier not found 的语法错误：
    var promise = Promise.reject(new Error("message"));
    promise.catch(function (error) {
        console.error(error);
    });


这是怎么回事呢？ 实际上这和 catch 是ECMAScript的 保留字 (Reserved Word)有关。

在ECMAScript 3中保留字是不能作为对象的属性名使用的。 而IE8及以下版本都是基于ECMAScript 3实现的，因此不能将 catch 作为属性来使用，也就不能编写类似 promise.catch() 的代码，因此就出现了 identifier not found 这种语法错误了。

而现在的浏览器都是基于ECMAScript 5的，而在ECMAScript 5中保留字都属于 IdentifierName ，也可以作为属性名使用了。

>在ECMAScript5中保留字也不能作为 Identifier 即变量名或方法名使用。 如果我们定义了一个名为 for 的变量的话，那么就不能和循环语句的 for 区分了。 而作为属性名的话，我们还是很容易区分 object.for 和 for 的，仔细想想我们就应该能接受将保留字作为属性名来使用了。


#### 分析

点标记法（dot notation） 要求对象的属性必须是有效的标识符（在ECMAScript 3中则不能使用保留字），

但是使用 中括号标记法（bracket notation）的话，则可以将非合法标识符作为对象的属性名使用。

也就是说，上面的代码如果像下面这样重写的话，就能在IE8及以下版本的浏览器中运行了（当然还需要polyfill）。

#### 解决ie8一下兼容性问题
     
 1.使用中括号标记法（bracket notation）：
 
     解决Promise#catch标识符冲突问题
     var promise = Promise.reject(new Error("message"));
     promise["catch"](function (error) {
         console.error(error);
     });
 
 2.使用Promise#then 替代Promise#catch
    
    var promise = Promise.reject(new Error("message"));
    promise.then(undefined, function (error) {
        console.error(error);
    });
    
    
#### 科普：一些库有关promise.catch的解决方案

由于 catch 标识符可能会导致问题出现，因此一些类库（Library）也采用了 caught 作为函数名，而函数要完成的工作是一样的。

而且很多压缩工具自带了将 promise.catch 转换为 promise["catch"] 的功能， 所以可能不经意之间也能帮我们解决这个问题。

如果各位读者需要支持IE8及以下版本的浏览器的话，那么一定要将这个 catch 问题牢记在心中。
 
     
### 专栏: **每次调用then** 都会 **返回** 一个**新创建的promise对象**

#### 测试每次调用then的返回值

    var aPromise = new Promise(function (resolve) {
        resolve(100);
    });
    var thenPromise = aPromise.then(function (value) {
        console.log(value);
    });
    var catchPromise = thenPromise.catch(function (error) {
        console.error(error);
    });
    console.log(aPromise !== thenPromise); // => true
    console.log(thenPromise !== catchPromise);// => true
    
因此每次都会返回不同Promise对象

>所以要留心没使用Promise时，若要扩展方法时需要返回新的Promise对象进行处理

    实战
    // 1: 对同一个promise对象同时调用 `then` 方法
    var aPromise = new Promise(function (resolve) {
        resolve(100);
    });
    aPromise.then(function (value) {
        return value * 2;
    });
    aPromise.then(function (value) {
        return value * 2;
    });
    aPromise.then(function (value) {
        console.log("1: " + value); // => 100
    })
    
    // vs
    
    // 2: 对 `then` 进行 promise chain 方式进行调用
    var bPromise = new Promise(function (resolve) {
        resolve(100);
    });
    bPromise.then(function (value) {
        return value * 2;
    }).then(function (value) {
        return value * 2;
    }).then(function (value) {
        console.log("2: " + value); // => 100 * 2 * 2
    });     

对比可看出 

+ 不写成链式情况
promise.then每次接受的参数都为第一次new时所传入的数，

因此，多次promise.then这成立一个异步事务流

+ 写成链式情况
写成链式之后，由于每次promise.then都会返回一个新的promise对象，

则每个then可以通过链式调用来return当前then返回的数据或对象

因此，链式调用是比较推荐的写法，能避免很多不必要的错误


#### 一个有关then的很有代表性的反模式的例子

    错误做法xxx
    function badAsyncCall() {
        const promise = Promise.resolve(value);
        promise.then(function(value){
            return value;
        });
        return promise;
    }
    
    
    正确做法✔✔✔
        function goodAsyncCall() {
            const promise = Promise.resolve(value);
            return promise.then(function(value){
                return value;
            });
        }
        
这种函数的行为贯穿在Promise整体之中， 包括我们后面要进行说明的 Promise.all 和 Promise.race ，他们都会接收一个promise对象为参数，并返回一个和接收参数不同的、新的promise对象。

### Promise和数组
因为技术不够，暂时总结不出来，先贴代码

需要事先说明的是 Promise.all 比较适合这种应用场景的需求，因此我们故意采用了大量 .then 的晦涩的写法。

使用了.then 的话，也并不是说能和回调风格完全一致，大概重写后代码如下所示。

    function getURL(URL) {
        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(new Error(req.statusText));
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            };
            req.send();
        });
    }
    var request = {
            comment: function getComment() {
                return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
            },
            people: function getPeople() {
                return getURL('http://azu.github.io/promises-book/json/people.json').then(JSON.parse);
            }
        };
    function main() {
        function recordValue(results, value) {
            results.push(value);
            return results;
        }
        // [] 用来保存初始化的值
        var pushValue = recordValue.bind(null, []);
        return request.comment().then(pushValue).then(request.people).then(pushValue);
    }
    // 运行的例子
    main().then(function (value) {
        console.log(value);
    }).catch(function(error){
        console.error(error);
    });
    
    
    
### Promise.all

**接收一个 promise对象的数组** 作为参数，当这个数组里的**所有promise对象**全部变为resolve或reject状态的时候，它才会去调用 .then 方法。

    实战
    function getURL (URL) {
        return new Promise(function(resolve, reject){
            const req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function(){
              if (req.status === 200) {
                resolve(req.statusText);
              }  else {
                reject(req.statusText);
              }
            };
            req.onerror = function() {
                reject(req.statusText);
            }
        });
    }
    
    const request = {
        comment: function getComment (){
            return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
        },
        people: function getPeople () {
            return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
        }
    };
    
    function main () {
        return Promise.all([request.comment(), request.people()]);
    }
    
    main().then(function(value){
        console.log(value);
    }).catch(function(value){
        console.log(value);
    });
    
### Promise.race
使用方法和Promise.all一样，接收一个promise对象数组为参数。

Promise.all 在接收到的所有的对象promise都变为 FulFilled 或者 Rejected 状态之后才会继续进行后面的处理， 

与之相对的是 Promise.race **只要有一个promise对象**进入 FulFilled 或者 Rejected 状态的话，就会继续进行后面的处理。

    实例：
    var winnerPromise = new Promise(function (resolve) {
            setTimeout(function () {
                console.log('this is winner');
                resolve('this is winner');
            }, 4);
        });
    var loserPromise = new Promise(function (resolve) {
            setTimeout(function () {
                console.log('this is loser');
                resolve('this is loser');
            }, 1000);
        });
    // 第一个promise变为resolve后程序停止
    Promise.race([winnerPromise, loserPromise]).then(function (value) {
        console.log(value);    // => 'this is winner'
    });
    
陆续输出： this is winner -> this is loser

也就是说， Promise.race 在第一个promise对象变为Fulfilled之后，并不会取消其他promise对象的执行。

>在 ES6 Promises 规范中，也没有取消（中断）promise对象执行的概念，我们必须要确保promise最终进入resolve or reject状态之一。也就是说Promise并不适用于 状态 可能会固定不变的处理。也有一些类库提供了对promise进行取消的操作。

### then or catch?

>.then 和 .catch 都会创建并返回一个 新的 promise对象。 Promise实际上每次在方法链中增加一次处理的时候所操作的都不是完全相同的promise对象。

![promise原理图](http://liubin.org/promises-book/Ch2_HowToWrite/img/then_catch.png)

#### 案例：

    function throwError(value) {
        // 抛出异常
        throw new Error(value);
    }
    // <1> onRejected不会被调用
    function badMain(onRejected) {
        return Promise.resolve(42).then(throwError, onRejected);
    }
    // <2> 有异常发生时onRejected会被调用
    function goodMain(onRejected) {
        return Promise.resolve(42).then(throwError).catch(onRejected);
    }
    // 运行示例
    badMain(function(){
        console.log("BAD");
    });
    goodMain(function(){
        console.log("GOOD");
    });
    
输出结果：“GOOD”，
而且badMain没有运行


#### 分析

在上述例子中，Promise.resolve(42)接受了value后返回 一个新的Promise对象A，接着then通过链式接受A对象并分别指定回调函数里面的参数resolve和reject；

对于badMain函数，then方法回调函数参数里面的的resolve传入一个threwError函数E并抛出异常，在回调参数里面的的resolve传入onRejected函数。因为异常函数传入then参数的resolve，reosolve抛出的异常需要在下一个then的参数reject 或 在then后面的catch来接收异常，所以badMain函数没有被调用；

对于goodMain函数，then方法回调函数参数的resolve抛出异常，并有then后面的catch函数接受异常函数E，所以goodMain被调用并正常打印成功。

#### 总结

这里我们又学习到了如下一些内容。

1.使用promise.then(onFulfilled, onRejected) 的话

+ 在 onFulfilled 中发生异常的话，在 onRejected 中是捕获不到这个异常的。

2.在 promise.then(onFulfilled).catch(onRejected) 的情况下

+ then 中产生的异常能在 .catch 中捕获

3..then 和 .catch 在本质上是没有区别的

+ 需要分场合使用。

我们需要注意如果代码类似 badMain 那样的话，就可能出现程序不会按预期运行的情况，从而不能正确的进行错误处理。


### Promise测试
总结编写Promise 的测试代码

1.使用Mocha测试框架
现在使用 Mocha来对Promise 进行基本的测试

使用Mocha的主要基于下面3点理由：
+ 它是非常著名的测试框架
+ 支持基于Node.js 和浏览器的测试
+ 支持"Promise测试"

#### Macha简介
>Mocha可以自由选择BDD、TDD、exports中的任意风格，测试中用到的Assert 方法也同样可以跟任何其他类库组合使用。 也就是说，Mocha本身只提供执行测试时的框架，而其他部分则由使用者自己选择

+ [Mocha官网](https://mochajs.org/)
+ Mocha是Node.js下的测试框架工具

#### Macha-回调函数风格的测试

    //basic-test.js
    var assert = require('power-assert');
    describe('Basic Test', function () {
        context('When Callback(high-order function)', function () {
            it('should use `done` for test', function (done) {
                setTimeout(function () {
                    assert(true);
                    done();
                }, 0);
            });
        });
        context('When promise object', function () {
            it('should use `done` for test?', function (done) {
                var promise = Promise.resolve(1);
                // このテストコードはある欠陥があります
                promise.then(function (value) {
                    assert(value === 1);
                    done();
                });
            });
        });
    });
    
### Promise进阶(Advanced)

#### Promise的实现类库（Library）
ES6 Promise 里关于promise对象的规定包括在使用 catch 方法，或使用 Promise.all 进行处理的时候不能出现错误。

Promises/A+ 是 ES6 Promises 的前身，Promise的 then 也是来自于此的基于社区的规范。

如果说一个类库兼容 Promises/A+ 的话，那么就是说它除了具有标准的 then 方法之外，很多情况下也说明此类库还支持 Promise.all 和 catch 等功能。

但是 Promises/A+ 实际上只是定义了关于 Promise#then 的规范，所以有些类库可能实现了其它诸如 all 或 catch 等功能，但是可能名字却不一样。

如果我们说一个类库具有 then 兼容性的话，实际上指的是 Thenable ，它通过使用 Promise.resolve 基于ES6 Promise的规定，进行promise对象的变换。

#### Polyfill和扩展类库
在这些Promise的实现类库中，我们这里主要对两种类型的类库进行介绍。

+ 一种是被称为 Polyfill （这是一款英国产品，就是装修刮墙用的腻子，其意义可想而知 — 译者注）的类库，另一种是即具有 Promises/A+兼容性 ，又增加了自己独特功能的类库。

+ Promise的实现类库数量非常之多，这里我们只是介绍了其中有限的几个。

1.Polyfill

只需要在浏览器中加载Polyfill类库，就能使用IE10等或者还没有提供对Promise支持的浏览器中使用Promise里规定的方法。

也就是说如果加载了Polyfill类库，就能在还不支持Promise的环境中，运行本文中的各种示例代码。

+ [jakearchibald/es6-promise](https://github.com/jakearchibald/es6-promise)
一个兼容 ES6 Promises 的Polyfill类库。 它基于 RSVP.js 这个兼容 Promises/A+ 的类库， 它只是 RSVP.js 的一个子集，只实现了Promises 规定的 API。
+ [yahoo/ypromise](https://github.com/yahoo/ypromise)
 这是一个独立版本的 YUI 的 Promise Polyfill，具有和 ES6 Promises 的兼容性。 本书的示例代码也都是基于这个 ypromise 的 Polyfill 来在线运行的。
+ [getify/native-promise-only](https://github.com/getify/native-promise-only/)
以作为ES6 Promises的polyfill为目的的类库 它严格按照ES6 Promises的规范设计，没有添加在规范中没有定义的功能。 如果运行环境有原生的Promise支持的话，则优先使用原生的Promise支持。


2.Promise扩展类库

Promise扩展类库除了实现了Promise中定义的规范之外，还增加了自己独自定义的功能。

Promise扩展类库数量非常的多，我们只介绍其中两个比较有名的类库。

>Q 和 Bluebird 这两个类库除了都能在浏览器里运行之外，充实的API reference也是其特征。

+ [kriskowal/q](https://github.com/kriskowal/q)
类库 Q 实现了 Promises 和 Deferreds 等规范。 它自2009年开始开发，还提供了面向Node.js的文件IO API Q-IO 等， 是一个在很多场景下都能用得到的类库。
+ [API Reference · kriskowal/q Wiki](https://github.com/kriskowal/q/wiki/API-Reference)
Q等文档里详细介绍了Q的Deferred和jQuery里的Deferred有哪些异同，以及要怎么进行迁移 Coming from jQuery 等都进行了详细的说明。

+ [petkaantonov/bluebird](https://github.com/petkaantonov/bluebird)
这个类库除了兼容 Promise 规范之外，还扩展了取消promise对象的运行，取得promise的运行进度，以及错误处理的扩展检测等非常丰富的功能，此外它在实现上还在性能问题下了很大的功夫。 
+ [bluebird/API.md at master · petkaantonov/bluebird](https://github.com/petkaantonov/bluebird/blob/master/API.md)
Bluebird的文档除了提供了使用Promise丰富的实现方式之外，还涉及到了在出现错误时的对应方法以及 Promise中的反模式 等内容。

这两个类库的文档写得都很友好，即使我们不使用这两个类库，阅读一下它们的文档也具有一定的参考价值。

#### Promise.resolve和Thenable
 Promise.resolve 的最大特征之一就是可以将thenable的对象转换为promise对象。现在总结一下利用将thenable对象转换为promise对象这个功能都能具体做些什么事情。下面是案例：
 
 1.将Web Notifications转换为thenable对象
 
 [桌面通知 API Web Notifications ](https://developer.mozilla.org/ja/docs/Web/API/notification)
 
    使用方法：
    new Notification("Hi!");
    
Notification API 的使用步骤：
>用户在这个是否允许Notification的对话框选择后的结果，会通过 Notification.permission 传给我们的程序，它的值可能是允许("granted")或拒绝("denied")这二者之一。

+ 显示是否允许通知的对话框，并异步处理用户选择结果。resolve(成功)时 == 用户允许("granted")

    + 如果用户允许的话，则通过 new Notification 显示通知消息。这又分两种情况

    + 用户之前已经允许过

+ 当场弹出是否允许桌面通知对话框。reject(失败)时 == 用户拒绝("denied")

    + 当用户不允许的时候，不执行任何操作
 
实战：
1.以回调函数编写为例

    notification-callback.js
    funtion notifyMessage(msg, opt, callback) {
        if(Notification && Notification.permission === 'granted'){
            const notification = new Notification(msg, opt);
            callback(null, notification);
        } else if (Notification.requestPermission) {
            Notification.requestPermission(function (status) {
                if (Notification.permission !== status) {
                    Notification.permission = status;
                }
                if (status === 'granted') {
                   const notification = new Notification(msg, opt);
                   callback(null, notification); 
                } else {
                    callback(new Error('user denied'));
                }
            });
        } else {
            callback(new Error('doesn\'t support Notification API'));
        }
    }    
    // 运行实例
    // 第二个参数是传给 `Notification` 的option对象
    notifyMessage("Hi!", {}, function (error, notification) {
        if(error){
            return console.error(error);
        }
        console.log(notification);// 通知对象
    });

2.对比使用回调函数，下面以promise重写
    
    notification-as-promise.js
    function notifyMessage(msg, opt, callback) {
        if (Notification && Notification.permission === 'granted') {
            const notification = new Notification(msg, opt);
            callback(null, notification);
        } else if (Notification.requestPermission) {
            Notification.requestPermission(function(status){
                if (Notification.permission !== status) {
                    Notification.permission = status;
                }
                if (status === 'granted') {
                    const notification = new Notification(msg, opt);
                    callback(null, notification);
                } else {
                    callback(new Error('user denied'));
                }            
            });
        } else {
            callback(new Error('doesn\'t support Notification API'));
        }
    }
    
    function notifyMessagePromise(msg opt) {
        return new Promise(function(resolve, reject){
            notifyMessage(msg, opt, function(error, notification){
                if (error) {
                    reject(error);
                } else {
                    resolve(notification);
                }
            });
        });
    }
    
    notifyMessagePromise("Hi!").then(function(notification){
        console.log(notification);
    }).catch(function(error){
        console.log(error);
    });

3.Web Notifications As Thenable。thenable就是一个具有 .then方法的一个对象

    notification-thenable.js
    function notifyMessage(message, options, callback) {
        if (Notification && Notification.permission === 'granted') {
            var notification = new Notification(message, options);
            callback(null, notification);
        } else if (Notification.requestPermission) {
            Notification.requestPermission(function (status) {
                if (Notification.permission !== status) {
                    Notification.permission = status;
                }
                if (status === 'granted') {
                    var notification = new Notification(message, options);
                    callback(null, notification);
                } else {
                    callback(new Error('user denied'));
                }
            });
        } else {
            callback(new Error('doesn\'t support Notification API'));
        }
    }
    // 返回 `thenable`
    function notifyMessageAsThenable(msg, opt) {
        return {
            'then': function(resolve, reject){
                notifyMessage(msg, opt, function(error, notification) {
                    if (error) {
                        reject(error);
                    } else {
                        resolve(notification);
                    }
                });
            }
        };
    }
    // 运行实例
    Promise.resolve(notifyMessageAsThenable('Hi!', opt)).then(function(notification){
        console.log(notification);
    }).catch(function(error){
        consoe.log(error);
    });
    

`notification-thenable.js`里增加了一个 `notifyMessageAsThenable`方法。这个方法返回的对象具备一个`then方法`。

`then`方法的参数和 `new Promise(function (resolve, reject){})` 一样，在确定时执行 `resolve` 方法，拒绝时调用 `reject` 方法。

`then` 方法和 `notification-as-promise.js` 中的 `notifyMessageAsPromise` 方法完成了同样的工作。

我们可以看出， `Promise.resolve(thenable)` 通过使用了  `thenable` 这个promise对象，就能利用Promise功能了。

    Promise.resolve(notifyMessageAsThenable("message")).then(function (notification) {
        console.log(notification);// 通知对象
    }).catch(function(error){
        console.error(error);
    });
    
使用了Thenable的notification-thenable.js 和依赖于Promise的 notification-as-promise.js ，实际上都是非常相似的使用方法。

notification-thenable.js 和 notification-as-promise.js比起来，有以下的不同点:
+ 类库侧没有提供 Promise 的实现
    + 用户通过 Promise.resolve(thenable) 来自己实现了 Promise
+ 作为Promise使用的时候，需要和 Promise.resolve(thenable) 一起配合使用

通过使用Thenable对象，我们可以实现类似已有的回调式风格和Promise风格中间的一种实现风格。

4.`总结`
在本小节我们主要学习了什么是Thenable，以及如何通过`Promise.resolve(thenable)` 使用Thenable，将其作为promise对象来使用。

>Callback — Thenable — Promise

Thenable风格表现为位于回调和Promise风格中间的一种状态，作为类库的公开API有点不太成熟，所以并不常见。

Thenable本身并不依赖于`Promise`功能，但是Promise之外也没有使用Thenable的方式，所以可以认为Thenable间接依赖于Promise。

另外，用户需要对 `Promise.resolve(thenable)` 有所理解才能使用好Thenable，因此作为类库的公开API有一部分会比较难。和公开API相比，更多情况下是在内部使用Thenable。

>在编写异步处理的类库的时候，推荐采用先编写回调风格的函数，然后再转换为公开API这种方式。

>貌似Node.js的Core module就采用了这种方式，除了类库提供的基本回调风格的函数之外，用户也可以通过Promise或者Generator等自己擅长的方式进行实现。

>最初就是以能被Promise使用为目的的类库，或者其本身依赖于Promise等情况下，我想将返回promise对象的函数作为公开API应该也没什么问题。

`什么时候该使用Thenable？`

那么，又是在什么情况下应该使用Thenable呢？

恐怕最可能被使用的是在 Promise类库 之间进行相互转换了。

比如，类库Q的Promise实例为Q promise对象，提供了 ES6 Promises 的promise对象不具备的方法。Q promise对象提供了 `promise.finally(callback)` 和 `promise.nodeify(callback)` 等方法。

如果你想将ES6 Promises的promise对象转换为Q promise的对象，轮到Thenable大显身手的时候就到了。

使用thenable将promise对象转换为Q promise对象
   
    var Q = require("Q");
    // 这是一个ES6的promise对象
    var promise = new Promise(function(resolve){
        resolve(1);
    });
    // 变换为Q promise对象
    Q(promise).then(function(value){
        console.log(value);
    }).finally(function(){ 
        console.log("finally");
    });

因为是Q promise对象所以可以使用 `finally` 方法

上面代码中最开始被创建的promise对象具备`then`方法，因此是一个Thenable对象。我们可以通过`Q(thenable)`方法，将这个Thenable对象转换为Q promise对象。

可以说它的机制和 `Promise.resolve(thenable)` 一样，当然反过来也一样。

像这样，Promise类库虽然都有自己类型的promise对象，但是它们之间可以通过Thenable这个共通概念，在类库之间（当然也包括native Promise）进行promise对象的相互转换。

我们看到，就像上面那样，Thenable多在类库内部实现中使用，所以从外部来说不会经常看到Thenable的使用。但是我们必须`牢记Thenable是Promise中一个非常重要的概念。`


#### 使用reject而不是throw

Promise的构造函数，以及被 then 调用执行的函数基本上都可以认为是在 try...catch 代码块中执行的，所以在这些代码中即使使用 throw ，程序本身也不会因为异常而终止。

那么使用reject还是throw？先看看代码对比

    // throw实例
    const promise = new Promise(function(resolve, reject){
        throw new Error('error');
    });
    promise.catch(function(error){
        console.log(error);
    });

如果在Promise中使用 throw 语句的话，会被 try...catch 住，最终promise对象也变为Rejected状态

    // reject实例
    const promise = new Promise(function(resolve, reject){
       reject(new Error("message"));
    });
    promise.catch(function(error){
       console.error(error);// => "message"
    })
    
对比上面案例，相对于throw，使用reject更加直观、合理


##### `使用reject有什么优点？`

1.首先是因为我们很难区分 throw 是我们主动抛出来的，还是因为真正的其它 异常 导致的。
  
+ 比如在使用Chrome浏览器的时候，Chrome的开发者工具提供了在程序发生异常的时候自动在调试器中break的功能。
  
~![Pause On Caught Exceptions](http://liubin.org/promises-book/Ch4_AdvancedPromises/img/chrome_on_caught_exception.png)

当我们开启这个功能的时候，在执行到下面代码中的 throw 时就会触发调试器的break行为。

    var promise = new Promise(function(resolve, reject){
        throw new Error("message");
    });
    
本来这是和调试没有关系的地方，也因为在Promise中的 throw 语句被break了，这也严重的影响了浏览器提供的此功能的正常使用。

2.在then中进行reject
>关于使用Promise进行超时处理的具体实现方法可以参考 使用Promise.race和delay取消XHR请求 中的详细说明。

现在用then来处理超时

     const promise = Promise.resolve()l
     promise.then(function(){
        const retPromise = new Promise(function(resolve, reject){
            setTimeout(function(){
                // 经过一段时间后还没处理完的话就进行reject 
            });
            // 比较耗时的处理 - 1
            somethingHardWork();
        });
        return retPromise;
     }).then(onFulfilled, onRejected);

原理分析：因为`then`在注册回调函数的时候可以通过`return`一个值或对象来传给后面的`then`或`catch`中的回调函数处理，因此可以使用then中`return`的特性来解决。提倡` 使用Promise.race和delay取消XHR请求 `

3.总结
+ 使用 reject 会比使用 throw 安全
+ 在 then 中使用reject的方法

##### Deferred和Promise
这里的`Deferred`指具有js延迟的一类库（Library）

1.Deferred和Promise的关系
+ Deferred 拥有 Promise
+ Deferred 具备对 Promise的状态进行操作的特权方法（图中的"特権メソッド"）

![Deferred and Promise](http://liubin.org/promises-book/Ch4_AdvancedPromises/img/deferred-and-promise.png)

我想各位看到此图应该就很容易理解了，Deferred和Promise并不是处于竞争的关系，而是Deferred内涵了Promise。

>这是jQuery.Deferred结构的简化版。当然也有的Deferred实现并没有内涵Promise。

>	Deferred最初是在Python的 Twisted 框架中被提出来的概念。 在JavaScript领域可以认为它是由 MochiKit.Async 、 dojo/Deferred 等Library引入的。



2.实例Deferred top on Promise

基于Promise实现的deferred

    // deferred.js
    function Deferred(){
        this.promise = new Promise(function(resolve, reject){
           this._resolve = resolve;
           this._reject = reject; 
        }).bind(this);
    }
    Deferred.prototype.resolve = function(value){
        this._resolve.call(this.promise, value);
    }
    Deferred.prototype.reject = function(reason){
        this._reject.call.call(this.promise, reason);
    }
    
使用Promise实现的 getURL 用Deferred改写一下

    // xhr-deferred.js
    function Deferred(){
        this.promise = new Promise(function(resolve, reject){
           this._resolve = resolve;
           this._reject = reject; 
        }).bind(this);
    }
    Deferred.prototype.resolve = function(value){
        this._resolve.call(this.promise, value);
    }
    Deferred.prototype.reject = function(reason){
        this._reject.call.call(this.promise, reason);
    }
    function getURL(URL) {
        const deferred = new Deferred();
        const req = new XMLHttpRequest();
        req.open('GET', URL, true);
        req.onload = function(){
            if (req.status === 200) {
                deferred.resolve(req.reponseText);
            } else {
                deferred.reject(req.reponseText);
            }
        };
        req.onerror = function() {
            deferred.reject(new Error(req.statusText));
        };
        req.send();
        return deferred.promise;
    }
    // 运行示例
    var URL = "http://httpbin.org/get";
    getURL(URL).then(function onFulfilled(value){
        console.log(value);
    }).catch(console.error.bind(console));
    
>所谓的能对Promise状态进行操作的`特权方法`，指的就是能对promise对象的状态进行resolve、reject等调用的方法，而通常的Promise的话只能在通过构造函数传递的方法之内对promise对象的状态进行操作。

我们来看看Deferred和Promise相比在实现上有什么异同。

    xhr-promise.js
    function getURL(URL) {
        return new Promise(function(resolve, reject){
            const req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function(){
                if (req.status === 200) {
                    resolve(req.reponseText);
                } else {
                   reject(req.reponseText);
                }
            }
            req.onerror = function() {
                reject(new Error(req.statusText));
            };
            req.send();
        });
    }
    // 运行示例
    var URL = "http://httpbin.org/get";
    getURL(URL).then(function onFulfilled(value){
        console.log(value);
    }).catch(console.error.bind(console));

对比上述两个版本的 `getURL` ，我们发现它们有如下不同。

+ Deferred 的话不需要将代码用Promise括起来
    + 由于没有被嵌套在函数中，可以减少一层缩进
    + 反过来没有Promise里的错误处理逻辑

在以下方面，它们则完成了同样的工作。
+ 整体处理流程
    + 调用 resolve、reject 的时机
+ 函数都返回了promise对象

由于Deferred包含了Promise，所以大体的流程还是差不多的，不过Deferred有用对Promise进行操作的特权方法，以及高度自由的对流程控制进行自由定制。

比如在`Promise`一般都会在构造函数中编写主要处理逻辑，对 resolve、reject 方法的调用时机也基本是很确定的。

    new Promise(function (resolve, reject){
        // 在这里进行promise对象的状态确定
    });
    
而使用`Deferred`的话，并不需要将处理逻辑写成一大块代码，只需要先创建deferred对象，可以在任何时机对 resolve、reject 方法进行调用。

    var deferred = new Deferred();
    // 可以在随意的时机对 `resolve`、`reject` 方法进行调用
    
上面我们只是简单的实现了一个 **Deferred** ，我想你已经看到了它和 **Promise** 之间的**差异**了吧。

>如果说`Promise`是用来`对值`进行`抽象`的话，`Deferred`则是**`对`处理还没有结束的`状态`或操作进行抽象化的`对象`**，我们也可以从这一层的区别来理解一下这两者之间的差异。

>换句话说，Promise代表了一个对象，这个对象的状态现在还不确定，但是未来一个时间点它的状态要么变为正常值（FulFilled），要么变为异常值（Rejected）；而Deferred对象表示了一个处理还没有结束的这种事实，在它的处理结束的时候，可以通过Promise来取得处理结果。

#### 使用Promise.race和delay取消XHR请求

>当然XHR有一个 timeout 属性，使用该属性也可以简单实现超时功能，但是为了能支持多个XHR同时超时或者其他功能，我们采用了容易理解的异步方式在XHR中通过超时来实现取消正在进行中的操作。

如何使用Promise.race来实现超时机制？

1.让Promise等待指定时间

首先我们来串讲一个单纯的在Promise中调用  setTimeout 的函数。

    delayPromise.js
    function delayPromise(ms) {
        return new Promise(function (resolve) {
            setTimeout(resolve, ms);
        });
    }
    
delayPromise(ms) 返回一个在经过了参数指定的毫秒数后进行onFulfilled操作的promise对象，这和直接使用 setTimeout 函数比较起来只是编码上略有不同，如下所示。

    setTimeout(function () {
        alert("已经过了100ms！");
    }, 100);
    // == 几乎同样的操作
    delayPromise(100).then(function () {
        alert("已经过了100ms！");
    });
    
在这里 promise对象 这个概念非常重要，请切记。

2.Promise.race中的超时

>它的作用是在任何一个promise对象进入到确定（解决）状态后就继续进行后续处理，如下面的例子所示

    var winnerPromise = new Promise(function (resolve) {
            setTimeout(function () {
                console.log('this is winner');
                resolve('this is winner');
            }, 4);
        });
    var loserPromise = new Promise(function (resolve) {
            setTimeout(function () {
                console.log('this is loser');
                resolve('this is loser');
            }, 1000);
        });
    // 第一个promise变为resolve后程序停止
    Promise.race([winnerPromise, loserPromise]).then(function (value) {
        console.log(value);    // => 'this is winner'
    });
    
我们可以将刚才的 delayPromise 和其它promise对象一起放到 `Promise.race` 中来是实现简单的超时机制。

    simple-timeout-promise.js
    function delayPromise(ms) {
        return new Promise(function (resolve) {
            setTimeout(resolve, ms);
        });
    }
    function timeoutPromise(promise, ms) {
        var timeout = delayPromise(ms).then(function () {
                throw new Error('Operation timed out after ' + ms + ' ms');
            });
        return Promise.race([promise, timeout]);
    }
    
函数 `timeoutPromise(比较对象promise, ms)` 接收两个参数，第一个是需要使用超时机制的promise对象，第二个参数是超时时间，它返回一个由 `Promise.race` 创建的相互竞争的promise对象。

之后我们就可以使用 `timeoutPromise` 编写下面这样的具有超时机制的代码了。

    function delayPromise(ms) {
        return new Promise(function (resolve) {
            setTimeout(resolve, ms);
        });
    }
    function timeoutPromise(promise, ms) {
        var timeout = delayPromise(ms).then(function () {
                throw new Error('Operation timed out after ' + ms + ' ms');
            });
        return Promise.race([promise, timeout]);
    }
    // 运行示例
    var taskPromise = new Promise(function(resolve){
        // 随便一些什么处理
        var delay = Math.random() * 2000;
        setTimeout(function(){
            resolve(delay + "ms");
        }, delay);
    });
    timeoutPromise(taskPromise, 1000).then(function(value){
        console.log("taskPromise在规定时间内结束 : " + value);
    }).catch(function(error){
        console.log("发生超时", error);
    });
    
虽然在发生超时的时候抛出了异常，但是这样的话我们就不能区分这个异常到底是普通的错误还是超时错误了。

为了能区分这个 `Error` 对象的类型，我们再来定义一个`Error` 对象的子类 `TimeoutError`。  

3.定制Error对象
`Error` 对象是ECMAScript的内建（build in）对象。

但是由于stack trace等原因我们不能完美的创建一个继承自 `Error` 的类，不过在这里我们的目的只是为了和Error有所区别，我们将创建一个 `TimeoutError` 类来实现我们的目的。

    在ECMAScript6中可以使用 class 语法来定义类之间的继承关系。
    
    class MyError extends Error{
        // 继承了Error类的对象
    }
    
为了让我们的 `TimeoutError` 能支持类似 `error instanceof TimeoutError` 的使用方法，我们还需要进行如下工作。

    TimeoutError.js
    function copyOwnFrom(target, source) {
        Object.getOwnPropertyNames(source).forEach(function (propName) {
            Object.defineProperty(target, propName, Object.getOwnPropertyDescriptor(source, propName));
        });
        return target;
    }
    function TimeoutError() {
        var superInstance = Error.apply(null, arguments);
        copyOwnFrom(this, superInstance);
    }
    TimeoutError.prototype = Object.create(Error.prototype);
    TimeoutError.prototype.constructor = TimeoutError;
    
有了这个 `TimeoutError` 对象，我们就能很容易区分捕获的到底是因为超时而导致的错误，还是其他原因导致的Error对象了

4.通过超时取消XHR操作

到这里，我想各位读者都已经对如何使用Promise来取消一个XHR请求都有一些思路了吧。

取消XHR操作本身的话并不难，只需要调用  `XMLHttpRequest` 对象的 `abort()` 方法就可以了。

为了能在外部调用 `abort()` 方法，我们先对之前本节出现的 `getURL` 进行简单的扩展，`cancelableXHR` 方法除了返回一个包装了XHR的promise对象之外，还返回了一个用于取消该XHR请求的abort方法。

    delay-race-cancel.js
    function cancelableXHR(URL) {
        var req = new XMLHttpRequest();
        var promise = new Promise(function (resolve, reject) {
                req.open('GET', URL, true);
                req.onload = function () {
                    if (req.status === 200) {
                        resolve(req.responseText);
                    } else {
                        reject(new Error(req.statusText));
                    }
                };
                req.onerror = function () {
                    reject(new Error(req.statusText));
                };
                req.onabort = function () {
                    reject(new Error('abort this request'));
                };
                req.send();
            });
        var abort = function () {
            // 如果request还没有结束的话就执行abort
            // https://developer.mozilla.org/en/docs/Web/API/XMLHttpRequest/Using_XMLHttpRequest
            if (req.readyState !== XMLHttpRequest.UNSENT) {
                req.abort();
            }
        };
        return {
            promise: promise,
            abort: abort
        };
    }

在这些问题都明了之后，剩下只需要进行Promise处理的流程进行编码即可。大体的流程就像下面这样:

1.通过 `cancelableXHR` 方法取得包装了XHR的promise对象和取消该XHR请求的方法

2.在 `timeoutPromise` 方法中通过 `Promise.race` 让XHR的包装promise和超时用promise进行竞争。

+ XHR在超时前返回结果的话

    a.和正常的promise一样，通过 `then` 返回请求结果

+ 发生超时的时候

    a.抛出 `throw TimeoutError` 异常并被 `catch`

    b.catch的错误对象如果是 `TimeoutError` 类型的话，则调用 `abort` 方法取消XHR请求

将上面的步骤总结一下的话，代码如下所示

    delay-race-cancel-play.js
    function copyOwnFrom(target, source) {
        Object.getOwnPropertyNames(source).forEach(function(propName){
            Object.defineProperty(target, propName, Object.getOwnPropertyDescriptor(source, propName));
        });
        return target;
    }
    
    function TimeoutError() {
        const superInstance = Error.apply(null, arguments);
        copyOwnFrom(this, superInstance);
    }
    TimeoutError.prototype = Object.create(Error,prototype);
    TimeoutError.prototype.constructor = TimeourError;
    
    function delayPromise(ms){
        return new Promise(function(resolve){
            setTimeout(function(resolve, ms));
        });
    }
    
    function timeoutPromise(promise, ms){
        let timeout = delayPromise(ms).then(function(){
            return Promise.reject(new TimeoutError('Operation timed out after' + ms + 'ms'));
        });
        return Promise.race([promise, timeout]);
    }
    
    function cancelableXHR(URL) {
        const req = new XMLHttpRequest();
        const promise = new Promise(function(resolve, reject){
            req.open('GET', URL, true);
            req.onload = function(){
                if (req.status === 200) {
                    resolve(req.statusText);
                } else {
                    reject(req.statusText);
                }
            };
            req.onerror = function() {
                reject(new Error(req.statusText));
            };
            req.abort = function() {
                reject(new Error('abort this request'));
            };
            
        });
        const abort = function() {
            if (req.readyState !== XMLHttpRequest.UNSENT) {
                req.abort();
            }
        };
        return {
            promise: promise,
            abort: abort
        };
    }

    var object = cancelableXHR('http://httpbin.org/get');
    
    timeoutPromise(object.promise, 1000).then(function(contents){
        console.log('Contents', contents);
    }).catch(function(error){
        if (error instanceof TimeoutError) {
            object.abort();
            return console.log(error);
        }
        console.log('XHR Error', error);
    });
    
上面的代码就通过在一定的时间内变为解决状态的promise对象实现了超时处理。
    
>通常进行开发的情况下，由于这些逻辑会频繁使用，因此将这些代码分割保存在不同的文件应该是一个不错的选择。

5. promise和操作方法

在前面的 `cancelableXHR` 中，promise对象及其操作方法都是在一个对象中返回的，看起来稍微有些不太好理解。

从代码组织的角度来说一个函数只返回一个值（promise对象）是一个非常好的习惯，但是由于在外面不能访问 cancelableXHR 方法中创建的 req 变量，所以我们需要编写一个专门的函数（上面的例子中的abort）来对这些内部对象进行处理。

当然也可以考虑到对返回的promise对象进行扩展，使其支持abort方法，但是由于promise对象是对值进行抽象化的对象，如果不加限制的增加操作用的方法的话，会使整体变得非常复杂。

大家都知道一个函数做太多的工作都不认为是一个好的习惯，因此我们不会让一个函数完成所有功能，也许像下面这样对函数进行分割是一个不错的选择。

返回包含XHR的promise对象

接收promise对象作为参数并取消该对象中的XHR请求

将这些处理整理为一个模块的话，以后扩展起来也方便，一个函数所做的工作也会比较精炼，代码也会更容易阅读和维护。

我们有很多方法来创建一个模块（AMD,CommonJS,ES6 module etc..），在这里，我们将会把前面的 cancelableXHR 整理为一个Node.js的模块使用。

    cancelableXHR.js
    "use strict";
    var requestMap = {};
    function createXHRPromise(URL) {
        var req = new XMLHttpRequest();
        var promise = new Promise(function (resolve, reject) {
            req.open('GET', URL, true);
            req.onreadystatechange = function () {
                if (req.readyState === XMLHttpRequest.DONE) {
                    delete requestMap[URL];
                }
            };
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(new Error(req.statusText));
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            };
            req.onabort = function () {
                reject(new Error('abort this req'));
            };
            req.send();
        });
        requestMap[URL] = {
            promise: promise,
            request: req
        };
        return promise;
    }
    
    function abortPromise(promise) {
        if (typeof promise === "undefined") {
            return;
        }
        var request;
        Object.keys(requestMap).some(function (URL) {
            if (requestMap[URL].promise === promise) {
                request = requestMap[URL].request;
                return true;
            }
        });
        if (request != null && request.readyState !== XMLHttpRequest.UNSENT) {
            request.abort();
        }
    }
    module.exports.createXHRPromise = createXHRPromise;
    module.exports.abortPromise = abortPromise;
    
使用方法也非常简单，我们通过 `createXHRPromise` 方法得到XHR的promise对象，当想对这个XHR进行abort操作的时候，将这个promise对象传递给  `abortPromise(promise)` 方法就可以了。

    // 使用
    var cancelableXHR = require("./cancelableXHR");
    
    var xhrPromise = cancelableXHR.createXHRPromise('http://httpbin.org/get');
    xhrPromise.catch(function (error) {
        // 调用 abort 抛出的错误
    });
    cancelableXHR.abortPromise(xhrPromise);
    
6. 总结
在这里我们学到了如下内容。

+ 经过一定时间后变为解决状态的delayPromise

+ 基于delayPromise和Promise.race的超时实现方式

+ 取消XHR promise请求

+ 通过模块化实现promise对象和操作的分离

Promise能非常灵活的进行处理流程的控制，为了充分发挥它的能力，我们需要注意不要将一个函数写的过于庞大冗长，而是应该将其分割成更小更简单的处理，并对之前JavaScript中提到的机制进行更深入的了解。

#### 什么是 Promise.prototype.done ？

>then和done的异常区别

+ then交给catch捕获处理
+ done直接抛出去

如果你使用过其他的Promise实现类库的话，可能见过用`done`代替`then`的例子。

这些类库都提供了 `Promise.prototype.done` 方法，使用起来也和 `then` 一样，但是这个方法并不会返回promise对象。

虽然 ES6 Promises和Promises/A+等在设计上并没有对Promise.prototype.done 做出任何规定，但是很多实现类库都提供了该方法的实现。

在本小节中，我们将会学习什么是 `Promise.prototype.done` ，以及为什么很多类库都提供了对该方法的支持。

1. 使用done的代码示例

        
    promise-done-example.js
    if (typeof Promise.prototype.done === 'undefined') {
        Promise.prototype.done = function (onFulfilled, onRejected) {
            this.then(onFulfilled, onRejected).catch(function (error) {
                setTimeout(function () {
                    throw error;
                }, 0);
            });
        };
    }
    var promise = Promise.resolve();
    promise.done(function () {
        JSON.parse('this is not json');    // => SyntaxError: JSON.parse
    });
    // => 请打开浏览器的开发者工具中的控制台窗口看一下
    
在前面我们已经说过，promise设计规格并没有对 Promise.prototype.done做出任何规定，因此在使用的时候，你可以使用已有类库提供的实现，也可以自己去实现。

我们会在后面讲述如何去自己实现，首先我们这里先对使用 then 和使用 done这两种方式进行一下比较。

    使用then的场景
    var promise = Promise.resolve();
    promise.then(function () {
        JSON.parse("this is not json");
    }).catch(function (error) {
        console.error(error);// => "SyntaxError: JSON.parse"
    });

从上面我们可以看出，两者之间有以下不同点。

+ done 并不返回promise对象
    + 也就是说，在done之后不能使用 catch 等方法组成方法链

+ done 中发生的异常会被直接抛给外面
    + 也就是说，不会进行Promise的错误处理（Error Handling）

由于done 不会返回promise对象，所以我们不难理解它只能出现在一个方法链的最后。

此外，我们已经介绍过了Promise具有强大的错误处理机制，而done则会在函数中跳过错误处理，直接抛出异常。

为什么很多类库都提供了这个和Promise功能相矛盾的函数呢？看一下下面Promise处理失败的例子，也许我们多少就能理解其中原因了吧。

2. 消失的错误
Promise虽然具备了强大的错误处理机制，但是（调试工具不能顺利运行的时候）这个功能会导致人为错误（human error）更加复杂，这也是它的一个缺点。

也许你还记得，我们在 then or catch? 中也看到了类似的内容。

像下面那样，我们看一个能返回promise对象的函数。

    json-promise.js
    function JSONPromise(value) {
        return new Promise(function (resolve) {
            resolve(JSON.parse(value));
        });
    }
    
这个函数将接收到的参数传递给 `JSON.parse` ，并返回一个基于JSON.parse的promise对象。

我们可以像下面那样使用这个Promise函数，由于 JSON.parse 会解析失败并抛出一个异常，该异常会被 catch 捕获。

    function JSONPromise(value) {
        return new Promise(function (resolve) {
            resolve(JSON.parse(value));
        });
    }
    // 运行示例
    var string = "非合法json编码字符串";
    JSONPromise(string).then(function (object) {
        console.log(object);
    }).catch(function(error){
        // => JSON.parse抛出异常时
        console.error(error);
    });
    
如果这个解析失败的异常被正常捕获的话则没什么问题，但是如果编码时忘记了处理该异常，一旦出现异常，那么查找异常发生的源头将会变得非常棘手，这就是使用promise需要注意的一面。

忘记了使用catch进行异常处理的的例子

    var string = "非合法json编码字符串";
    JSONPromise(string).then(function (object) {
        console.log(object);
    }); 

虽然抛出了异常，但是没有对该异常进行处理

如果是JSON.parse 这样比较好找的例子还算好说，如果是拼写错误的话，那么发生了Syntax Error错误的话将会非常麻烦。
    
    typo错误
    var string = "{}";
    JSONPromise(string).then(function (object) {
        conosle.log(object);
    });

存在conosle这个拼写错误

这这个例子里，我们错把 console 拼成了 conosle ，因此会发生如下错误。

ReferenceError: conosle is not defined

但是，由于Promise的try-catch机制，这个问题可能会被内部消化掉。 如果在调用的时候每次都无遗漏的进行 catch 处理的话当然最好了，但是如果在实现的过程中出现了这个例子中的错误的话，那么进行错误排除的工作也会变得困难。

这种错误被内部消化的问题也被称为 unhandled rejection ，从字面上看就是在Rejected时没有找到相应处理的意思。

这种unhandled rejection错误到底有多难检查，也依赖于Promise的实现。 比如 ypromise 在检测到 unhandled rejection 错误的时候，会在控制台上提示相应的信息。

Promise rejected but no error handlers were registered to it

另外， Bluebird 在比较明显的人为错误，即ReferenceError等错误的时候，会直接显示到控制台上。

"Possibly unhandled ReferenceError. conosle is not defined

原生（Native）的 Promise实现为了应对同样问题，提供了GC-based unhandled rejection tracking功能。

该功能是在promise对象被垃圾回收器回收的时候，如果是unhandled rejection的话，则进行错误显示的一种机制。

Firefox 或 Chrome 的原生Promise都进行了部分实现。

4.6.3. done的实现
作为方法论，在Promise中 done 是怎么解决上面提到的错误被忽略呢？ 其实它的方法很简单直接，那就是必须要进行错误处理。

由于可以在 Promise上实现 done 方法，因此我们看看如何对 Promise.prototype.done 这个Promise的prototype进行扩展。
    
    promise-prototype-done.js
    "use strict";
    if (typeof Promise.prototype.done === "undefined") {
        Promise.prototype.done = function (onFulfilled, onRejected) {
            this.then(onFulfilled, onRejected).catch(function (error) {
                setTimeout(function () {
                    throw error;
                }, 0);
            });
        };
    }

那么它是如何将异常抛到Promise的外面的呢？其实这里我们利用的是在setTimeout中使用throw方法，直接将异常抛给了外部。

    setTimeout的回调函数中抛出异常
    try{
        setTimeout(function callback() {
            throw new Error("error");
        }, 0);
    }catch(error){
        console.error(error);
    }
    
这个例外不会被捕获

关于为什么异步的callback中抛出的异常不会被捕获的原因，可以参考下面内容。

JavaScript和异步错误处理 - Yahoo! JAPAN Tech Blog（日语博客）

仔细看一下 Promise.prototype.done的代码，我们会发现这个函数什么也没 return 。 也就是说， done按照「Promise chain在这里将会中断，如果出现了异常，直接抛到promise外面即可」的原则进行了处理。

如果实现和运行环境实现的比较完美的话，就可以进行 unhandled rejection 检测，done也不一定是必须的了。 另外像本小节中的 Promise.prototype.done一样，done也可以在既有的Promise之上进行实现，也可以说它没有进入到 ES6 Promises的设计规范之中。


4. 总结
在本小节中，我们学习了 Q 、 Bluebird 和 prfun 等Promise类库提供的 done 的基础和实现细节，以及done方法和 then 方法有什么区别等内容。

我们也学到了 done 有以下两个特点。

done 中出现的错误会被作为异常抛出

终结 Promise chain

和 then or catch? 中说到的一样，由Promise内部消化掉的错误，随着调试工具或者类库的改进，大多数情况下也许已经不是特别大的问题了。

此外，由于 done 不会有返回值，因此不能在它之后进行方法链的创建，为了实现Promise方法风格上的统一，我们也可以使用done方法。

ES6 Promises 本身提供的功能并不是特别多。 因此，我想很多时候可能需要我们自己进行扩展或者使用第三方类库。

我们好不容易将异步处理统一采用Promise进行统一处理，但是如果做过头了，也会将系统变得特别复杂，因此，保持风格的统一是Promise作为抽象对象非常重要的部分。

#### Promise和方法链（method chain）

在Promise中你可以将 then 和 catch 等方法连在一起写。这非常像DOM或者jQuery中的方法链。

一般的方法链都通过返回 this 将多个方法串联起来。

另一方面，由于Promise 每次都会返回一个新的promise对象 ，所以从表面上看和一般的方法链几乎一模一样。

在本小节里，我们会在不改变已有采用方法链编写的代码的外部接口的前提下，学习如何在内部使用Promise进行重写。

1. fs中的方法链
我们下面将会以 Node.js中的fs 为例进行说明。

此外，这里的例子我们更重视代码的易理解性，因此从实际上来说这个例子可能并不算太实用。

    fs-method-chain.js
    "use strict";
    var fs = require("fs");
    function File() {
        this.lastValue = null;
    }
    // Static method for File.prototype.read
    File.read = function FileRead(filePath) {
        var file = new File();
        return file.read(filePath);
    };
    File.prototype.read = function (filePath) {
        this.lastValue = fs.readFileSync(filePath, "utf-8");
        return this;
    };
    File.prototype.transform = function (fn) {
        this.lastValue = fn.call(this, this.lastValue);
        return this;
    };
    File.prototype.write = function (filePath) {
        this.lastValue = fs.writeFileSync(filePath, this.lastValue);
        return this;
    };
    module.exports = File;
    
这个模块可以将类似下面的 read → transform → write 这一系列处理，通过组成一个方法链来实现。

    var File = require("./fs-method-chain");
    var inputFilePath = "input.txt",
        outputFilePath = "output.txt";
    File.read(inputFilePath)
        .transform(function (content) {
            return ">>" + content;
        })
        .write(outputFilePath);
        
transform 接收一个方法作为参数，该方法对其输入参数进行处理。在这个例子里，我们对通过read读取的数据在前面加上了 >> 字符串。

2. 基于Promise的fs方法链

下面我们就在不改变刚才的方法链对外接口的前提下，采用Promise对内部实现进行重写。
    
    fs-promise-chain.js
    "use strict";
    var fs = require("fs");
    function File() {
        this.promise = Promise.resolve();
    }
    // Static method for File.prototype.read
    File.read = function (filePath) {
        var file = new File();
        return file.read(filePath);
    };
    
    File.prototype.then = function (onFulfilled, onRejected) {
        this.promise = this.promise.then(onFulfilled, onRejected);
        return this;
    };
    File.prototype["catch"] = function (onRejected) {
        this.promise = this.promise.catch(onRejected);
        return this;
    };
    File.prototype.read = function (filePath) {
        return this.then(function () {
            return fs.readFileSync(filePath, "utf-8");
        });
    };
    File.prototype.transform = function (fn) {
        return this.then(fn);
    };
    File.prototype.write = function (filePath) {
        return this.then(function (data) {
            return fs.writeFileSync(filePath, data)
        });
    };
    module.exports = File;
    
新增加的then 和catch都可以看做是指向内部保存的promise对象的别名，而其它部分从对外接口的角度来说都没有改变，使用方法也和原来一样。

因此，在使用这个模块的时候我们只需要修改 require 的模块名即可。

    var File = require("./fs-promise-chain");
    var inputFilePath = "input.txt",
        outputFilePath = "output.txt";
    File.read(inputFilePath)
        .transform(function (content) {
            return ">>" + content;
        })
        .write(outputFilePath);
    
File.prototype.then 方法会调用 this.promise.then 方法，并将返回的promise对象赋值给了 this.promise 变量这个内部promise对象。

这究竟有什么奥妙么？通过以下的伪代码，我们可以更容易理解这背后发生的事情。

    var File = require("./fs-promise-chain");
    File.read(inputFilePath)
        .transform(function (content) {
            return ">>" + content;
        })
        .write(outputFilePath);
    // => 处理流程类似以下的伪代码
    promise.then(function read(){
            return fs.readFileSync(filePath, "utf-8");
        }).then(function transform(content) {
             return ">>" + content;
        }).then(function write(){
            return fs.writeFileSync(filePath, data);
        });
        
看到 promise = promise.then(...) 这种写法，会让人以为promise的值会被覆盖，也许你会想是不是promise的chain被截断了。

你可以想象为类似 promise = addPromiseChain(promise, fn); 这样的感觉，我们为promise对象增加了新的处理，并返回了这个对象，因此即使自己不实现顺序处理的话也不会带来什么问题。

3. 两者的区别

同步和异步

要说fs-method-chain.js和Promise版两者之间的差别，最大的不同那就要算是同步和异步了。

如果在类似 fs-method-chain.js 的方法链中加入队列等处理的话，就可以实现几乎和异步方法链同样的功能，但是实现将会变得非常复杂，所以我们选择了简单的同步方法链。

Promise版的话如同在 专栏: Promise只能进行异步处理？里介绍过的一样，只会进行异步操作，因此使用了promise的方法链也是异步的。

错误处理

虽然fs-method-chain.js里面并不包含错误处理的逻辑， 但是由于是同步操作，因此可以将整段代码用 try-catch 包起来。

在 Promise版 提供了指向内部promise对象的then 和 catch 别名，所以我们可以像其它promise对象一样使用catch来进行错误处理。

    fs-promise-chain中的错误处理
    var File = require("./fs-promise-chain");
    File.read(inputFilePath)
        .transform(function (content) {
            return ">>" + content;
        })
        .write(outputFilePath)
        .catch(function(error){
            console.error(error);
        });
    
如果你想在fs-method-chain.js中自己实现异步处理的话，错误处理可能会成为比较大的问题；可以说在进行异步处理的时候，还是使用Promise实现起来比较简单。

4. Promise之外的异步处理

如果你很熟悉Node.js的話，那么看到方法链的话，你是不是会想起来 Stream 呢。

如果使用 Stream 的话，就可以免去了保存 this.lastValue 的麻烦，还能改善处理大文件时候的性能。 另外，使用Stream的话可能会比使用Promise在处理速度上会快些。

使用Stream进行read→transform→write

    readableStream.pipe(transformStream).pipe(writableStream);

因此，在异步处理的时候并不是说Promise永远都是最好的选择，要根据自己的目的和实际情况选择合适的实现方式。

Node.js的Stream是一种基于Event的技术

关于Node.js中Stream的详细信息可以参考以下网页。

利用Node.js Stream API对数据进行流式处理 - Block Rockin’ Codes

Stream2基础

关于Node-v0.12新功能

5. Promise wrapper
再回到 fs-method-chain.js 和 Promise版，这两种方法相比较内部实现也非常相近，让人觉得是不是同步版本的代码可以直接就当做异步方式来使用呢？

由于JavaScript可以向对象动态添加方法，所以从理论上来说应该可以从非Promise版自动生成Promise版的代码。（当然静态定义的实现方式容易处理）

尽管 ES6 Promises 并没有提供此功能，但是著名的第三方Promise实现类库 bluebird 等提供了被称为 Promisification 的功能。

如果使用类似这样的类库，那么就可以动态给对象增加promise版的方法。

    var fs = Promise.promisifyAll(require("fs"));
    
    fs.readFileAsync("myfile.js", "utf8").then(function(contents){
        console.log(contents);
    }).catch(function(e){
        console.error(e.stack);
    });
    
Array的Promise wrapper
前面的 Promisification 具体都干了些什么光凭想象恐怕不太容易理解，我们可以通过给原生的 Array 增加Promise版的方法为例来进行说明。

在JavaScript中原生DOM或String等也提供了很多创建方法链的功能。 Array 中就有诸如 map 和 filter 等方法，这些方法会返回一个数组类型，可以用这些方法方便的组建方法链。
    
    array-promise-chain.js
    "use strict";
    function ArrayAsPromise(array) {
        this.array = array;
        this.promise = Promise.resolve();
    }
    ArrayAsPromise.prototype.then = function (onFulfilled, onRejected) {
        this.promise = this.promise.then(onFulfilled, onRejected);
        return this;
    };
    ArrayAsPromise.prototype["catch"] = function (onRejected) {
        this.promise = this.promise.catch(onRejected);
        return this;
    };
    Object.getOwnPropertyNames(Array.prototype).forEach(function (methodName) {
        // Don't overwrite
        if (typeof ArrayAsPromise[methodName] !== "undefined") {
            return;
        }
        var arrayMethod = Array.prototype[methodName];
        if (typeof  arrayMethod !== "function") {
            return;
        }
        ArrayAsPromise.prototype[methodName] = function () {
            var that = this;
            var args = arguments;
            this.promise = this.promise.then(function () {
                that.array = Array.prototype[methodName].apply(that.array, args);
                return that.array;
            });
            return this;
        };
    });
    
    module.exports = ArrayAsPromise;
    module.exports.array = function newArrayAsPromise(array) {
        return new ArrayAsPromise(array);
    };
    
原生的 Array 和 ArrayAsPromise 在使用时有什么差异呢？我们可以通过对 上面的代码 进行测试来了解它们之间的不同点。
    
    array-promise-chain-test.js
    "use strict";
    var assert = require("power-assert");
    var ArrayAsPromise = require("../src/promise-chain/array-promise-chain");
    describe("array-promise-chain", function () {
        function isEven(value) {
            return value % 2 === 0;
        }

    function double(value) {
        return value * 2;
    }

    beforeEach(function () {
        this.array = [1, 2, 3, 4, 5];
    });
    describe("Native array", function () {
        it("can method chain", function () {
            var result = this.array.filter(isEven).map(double);
            assert.deepEqual(result, [4, 8]);
        });
    });
    describe("ArrayAsPromise", function () {
        it("can promise chain", function (done) {
            var array = new ArrayAsPromise(this.array);
            array.filter(isEven).map(double).then(function (value) {
                assert.deepEqual(value, [4, 8]);
            }).then(done, done);
        });
    });
});
我们看到，在 ArrayAsPromise 中也能使用 Array的方法。而且也和前面的例子类似，原生的Array是同步处理，而 ArrayAsPromise 则是异步处理，这也是它们的不同之处。

仔细看一下 ArrayAsPromise 的实现，也许你已经注意到了， Array.prototype 的所有方法都被实现了。 但是，Array.prototype 中也存在着类似array.indexOf 等并不会返回数组类型数据的方法，这些方法如果也要支持方法链的话就有些不自然了。

在这里非常重要的一点是，我们可以通过这种方式，为具有接收相同类型数据接口的API动态的创建Promise版的API。 如果我们能意识到这种API的规则性的话，那么就可能发现一些新的使用方法。

前面我们看到的 Promisification 方法，借鉴了了 Node.js的Core模块中在进行异步处理时将 function(error,result){} 方法的第一个参数设为 error 这一规则，自动的创建由Promise包装好的方法。

6. 总结

在本小节我们主要学习了下面的这些内容。

Promise版的方法链实现

Promise并不是总是异步编程的最佳选择

Promisification

统一接口的重用

ES6 Promises只提供了一些Core级别的功能。 因此，我们也许需要对现有的方法用Promise方式重新包装一下。

但是，类似Event等调用次数没有限制的回调函数等在并不适合使用Promise，Promise也不能说什么时候都是最好的选择。

至于什么情况下应该使用Promise，什么时候不该使用Promise，并不是本书要讨论的目的， 我们需要牢记的是不要什么都用Promise去实现，我想最好根据自己的具体目的和情况，来考虑是应该使用Promise还是其它方法。

#### 使用Promise进行顺序（sequence）处理
在第2章 Promise.all 中，我们已经学习了如何让多个promise对象同时开始执行的方法。

但是 Promise.all 方法会同时运行多个promise对象，如果想进行在A处理完成之后再开始B的处理，对于这种顺序执行的话 Promise.all就无能为力了。

此外，在同一章的Promise和数组 中，我们也介绍了一种效率不是特别高的，使用了 重复使用多个then的方法 来实现如何按顺序进行处理。

在本小节中，我们将对如何在Promise中进行顺序处理进行介绍。


1. 循环和顺序处理

在 重复使用多个then的方法 中的实现方法如下。

    function getURL(URL) {
        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(new Error(req.statusText));
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            };
            req.send();
        });
    }
    var request = {
            comment: function getComment() {
                return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
            },
            people: function getPeople() {
                return getURL('http://azu.github.io/promises-book/json/people.json').then(JSON.parse);
            }
        };
    function main() {
        function recordValue(results, value) {
            results.push(value);
            return results;
        }
        // [] 用来保存初始化的值
        var pushValue = recordValue.bind(null, []);
        return request.comment().then(pushValue).then(request.people).then(pushValue);
    }
    // 运行示例
    main().then(function (value) {
        console.log(value);
    }).catch(function(error){
        console.error(error);
    });
    
使用这种写法的话那么随着 request 中元素数量的增加，我们也需要不断增加对 then 方法的调用

因此，如果我们将处理内容统一放到数组里，再配合for循环进行处理的话，那么处理内容的增加将不会再带来什么问题。首先我们就使用for循环来完成和前面同样的处理。

    promise-foreach-xhr.js
    function getURL(URL) {
        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(new Error(req.statusText));
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            };
            req.send();
        });
    }
    var request = {
            comment: function getComment() {
                return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
            },
            people: function getPeople() {
                return getURL('http://azu.github.io/promises-book/json/people.json').then(JSON.parse);
            }
        };
    function main() {
        function recordValue(results, value) {
            results.push(value);
            return results;
        }
        // [] 用来保存初始化值
        var pushValue = recordValue.bind(null, []);
        // 返回promise对象的函数的数组
        var tasks = [request.comment, request.people];
        var promise = Promise.resolve();
        // 开始的地方
        for (var i = 0; i < tasks.length; i++) {
            var task = tasks[i];
            promise = promise.then(task).then(pushValue);
        }
        return promise;
    }
    // 运行示例
    main().then(function (value) {
        console.log(value);
    }).catch(function(error){
        console.error(error);
    });
    
使用for循环的时候，如同我们在 专栏: 每次调用then都会返回一个新创建的promise对象 以及 Promise和方法链 中学到的那样，每次调用 Promise#then 方法都会返回一个新的promise对象。

因此类似 promise = promise.then(task).then(pushValue); 的代码就是通过不断对promise进行处理，不断的覆盖 promise 变量的值，以达到对promise对象的累积处理效果。

但是这种方法需要 promise 这个临时变量，从代码质量上来说显得不那么简洁。

如果将这种循环写法改用 Array.prototype.reduce 的话，那么代码就会变得聪明多了。

2. Promise chain和reduce

如果将上面的代码用 Array.prototype.reduce 重写的话，会像下面一样。

    promise-reduce-xhr.js
    function getURL(URL) {
        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(new Error(req.statusText));
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            };
            req.send();
        });
    }
    var request = {
            comment: function getComment() {
                return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
            },
            people: function getPeople() {
                return getURL('http://azu.github.io/promises-book/json/people.json').then(JSON.parse);
            }
        };
    function main() {
        function recordValue(results, value) {
            results.push(value);
            return results;
        }
        var pushValue = recordValue.bind(null, []);
        var tasks = [request.comment, request.people];
        return tasks.reduce(function (promise, task) {
            return promise.then(task).then(pushValue);
        }, Promise.resolve());
    }
    // 运行示例
    main().then(function (value) {
        console.log(value);
    }).catch(function(error){
        console.error(error);
    });

这段代码中除了 main 函数之外的其他处理都和使用for循环的时候相同。

Array.prototype.reduce 的第二个参数用来设置盛放计算结果的初始值。在这个例子中， Promise.resolve() 会赋值给 promise ，此时的 task 为 request.comment 。

在reduce中第一个参数中被 return 的值，则会被赋值为下次循环时的 promise 。也就是说，通过返回由 then 创建的新的promise对象，就实现了和for循环类似的 Promise chain 了。

下面是关于 Array.prototype.reduce 的详细说明。

Array.prototype.reduce() - JavaScript | MDN

azu / Array.prototype.reduce Dance - Glide

使用reduce和for循环不同的地方是reduce不再需要临时变量 promise 了，因此也不用编写 promise = promise.then(task).then(pushValue); 这样冗长的代码了，这是非常大的进步。

虽然 Array.prototype.reduce 非常适合用来在Promise中进行顺序处理，但是上面的代码有可能让人难以理解它是如何工作的。

因此我们再来编写一个名为 sequenceTasks 的函数，它接收一个数组作为参数，数组里面存放的是要进行的处理Task。

从下面的调用代码中我们可以非常容易的从其函数名想到，该函数的功能是对 tasks 中的处理进行顺序执行了。

    var tasks = [request.comment, request.people];
    sequenceTasks(tasks);
    
3. 定义进行顺序处理的函数

基本上我们只需要基于 使用reduce的方法 重构出一个函数。

    promise-sequence.js
    function sequenceTasks(tasks) {
        function recordValue(results, value) {
            results.push(value);
            return results;
        }
        var pushValue = recordValue.bind(null, []);
        return tasks.reduce(function (promise, task) {
            return promise.then(task).then(pushValue);
        }, Promise.resolve());
    }

需要注意的一点是，和 Promise.all 等不同，这个函数接收的参数是一个函数的数组。

为什么传给这个函数的不是一个promise对象的数组呢？这是因为promise对象创建的时候，XHR已经开始执行了，因此再对这些promise对象进行顺序处理的话就不能正常工作了。

因此 sequenceTasks 将函数(该函数返回一个promise对象)的数组作为参数。

最后，使用 sequenceTasks 重写最开始的例子的话，如下所示。

    promise-sequence-xhr.js
    function sequenceTasks(tasks) {
        function recordValue(results, value) {
            results.push(value);
            return results;
        }
        var pushValue = recordValue.bind(null, []);
        return tasks.reduce(function (promise, task) {
            return promise.then(task).then(pushValue);
        }, Promise.resolve());
    }
    function getURL(URL) {
        return new Promise(function (resolve, reject) {
            var req = new XMLHttpRequest();
            req.open('GET', URL, true);
            req.onload = function () {
                if (req.status === 200) {
                    resolve(req.responseText);
                } else {
                    reject(new Error(req.statusText));
                }
            };
            req.onerror = function () {
                reject(new Error(req.statusText));
            };
            req.send();
        });
    }
    var request = {
            comment: function getComment() {
                return getURL('http://azu.github.io/promises-book/json/comment.json').then(JSON.parse);
            },
            people: function getPeople() {
                return getURL('http://azu.github.io/promises-book/json/people.json').then(JSON.parse);
            }
        };
    function main() {
        return sequenceTasks([request.comment, request.people]);
    }
    // 运行示例
    main().then(function (value) {
        console.log(value);
    }).catch(function(error){
        console.error(error);
    });

怎样， main() 中的流程是不是更清晰易懂了。

如上所述，在Promise中，我们可以选择多种方法来实现处理的按顺序执行。

+ 循环使用then调用的方法

+ 使用for循环的方法

+ 使用reduce的方法

+ 分离出顺序处理函数的方法

但是，这些方法都是基于JavaScript中对数组及进行操作的for循环或 forEach 等，本质上并无大区别。 因此从一定程度上来说，在处理Promise的时候，将大块的处理分成小函数来实现是一个非常好的实践。

4. 总结

在本小节中，我们对如何在Promise中进行和 Promise.all 相反，按顺序让promise一个个进行处理的实现方式进行了介绍。

为了实现顺序处理，我们也对从过程风格的编码方式到自定义顺序处理函数的方式等实现方式进行了介绍，也再次强调了在Promise领域我们应遵循将处理按照函数进行划分的基本原则。

在Promise中如果还使用了Promise chain将多个处理连接起来的话，那么还可能使源代码中的一条语句变得很长。

这时候如果我们回想一下这些编程的基本原则进行函数拆分的话，代码整体结构会变得非常清晰。

此外,Promise的构造函数以及 then 都是高阶函数，如果将处理分割为函数的话，还能得到对函数进行灵活组合使用的副作用，意识到这一点对我们也会有一些帮助的。

高阶函数指的是一个函数可以接受其参数为函数对象的实例