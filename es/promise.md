#总结在前

#前言
下文类似 Promise#then、Promise#resolve 都是Promise的实例对象，

#什么是Promise
Promise是抽象异步处理对象以及对其进行各种操作的组件

##Promise简介
###目前大致有下面三种类型：

>1、Constructor

    var promise = new Promise(function(resolve, reject) {
        // 异步处理
        // 处理结束后、调用resolve 或 reject
    });

>2、Instance Method

    promise.then(resolved, rejected)
            .catch(rejected)
            .finally()

>3、Static Method

    Promise.all()
    Promise.resolve()
    Promise.reject()
    Promise.race()

###对比

    Promise.resolve() 
    等价于
    new Promise(function(resolve, null){})

##Promise的状态

用new Promise 实例化的promise对象有以下三个状态。

| ES6 Promises 规范中定义的术语 | Promises/A+ 中描述状态的术语 |  状态说明  |
| ----------------------------- | ---------------------------- | ---------- |
| has-resolution                | Fulfilled                    | resolve(成功)时。此时会调用 onFulfilled |
| has-rejection                 | Rejected                     | reject(失败)时。此时会调用 onRejected |
| unresolved                    | Pending                      | 既不是resolve也不是reject的状态。也就是promise对象刚被创建后的初始化状态等 |

触发Promise之后只会是 **resolved** 或 **rejected** 两种，而且状态不可逆

![Promise状态解析图](http://liubin.org/promises-book/Ch1_WhatsPromises/img/promise-states.png "Promise状态解析图")


##编写Promise
### 创建Promise对象
   
>1.new Promise(fn) 返回一个promise对象
    
>2.在fn 中 **指定** 异步等处理

    处理结果正常的话，调用resolve(处理结果值)
    处理结果错误的话，调用reject(Error对象)

>3.函数处理 Promise对像
    
    then();  --- 为了避免上述中同时使用同步、异步调用可能引起的混乱问题
    catch(); --- catch在ie8下存在兼容性问题
    finally();

####实战
    
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

##实战 Promise中各种方法

###Promise.resolve()
    返回值是一个promise对象
    
> 静态方法 Promise.resolve() 可以认为是实例 new Promise(function(resolve, undefined){})方法 的快捷方式（语法糖）
    
+ 让promise对象立即进入resolved状态

>将 thenable 对象转换为promise对象

+ 返回值是 thenable


    实例：
    Promise.resolve(value).then(function(value){
        console.log(value);
    })

###Promise.reject()
    返回值是一个promise对象
    
> 静态方法 Promise.resolve() 可以认为是实例 promise.then(undefined, onRejected)方法 的快捷方式（语法糖）
    
+ 让promise对象立即进入rejected状态
+ 使用catch()处理异常


    例如：
    Promise.reject(new Error("BOOM!")).catch(function(error){
        console.error(error);
    });
    

###专栏: Promise只能进行异步操作？
**Promise在规范上规定 Promise只能使用异步调用方式**思考一个问题：文档的加载顺序都是从上到下加载的，那么代码的的位置起何等作用？

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

###Promise#then

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
   
####Promise Chain 中的传参原理

>Promise Chain 之间的传参，只需要在每个事务回调函数中使用 **return** 来返回当前值

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


###Promise#catch

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


####分析

点标记法（dot notation） 要求对象的属性必须是有效的标识符（在ECMAScript 3中则不能使用保留字），

但是使用 中括号标记法（bracket notation）的话，则可以将非合法标识符作为对象的属性名使用。

也就是说，上面的代码如果像下面这样重写的话，就能在IE8及以下版本的浏览器中运行了（当然还需要polyfill）。

####解决ie8一下兼容性问题
     
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
    
    
####科普：一些库有关promise.catch的解决方案

由于 catch 标识符可能会导致问题出现，因此一些类库（Library）也采用了 caught 作为函数名，而函数要完成的工作是一样的。

而且很多压缩工具自带了将 promise.catch 转换为 promise["catch"] 的功能， 所以可能不经意之间也能帮我们解决这个问题。

如果各位读者需要支持IE8及以下版本的浏览器的话，那么一定要将这个 catch 问题牢记在心中。
 
     
###专栏: **每次调用then** 都会 **返回** 一个**新创建的promise对象**

####测试每次调用then的返回值

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


####一个有关then的很有代表性的反模式的例子

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

###Promise和数组
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
    
    
    
###Promise.all
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
    
###Promise.race
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

###then or catch?

>.then 和 .catch 都会创建并返回一个 新的 promise对象。 Promise实际上每次在方法链中增加一次处理的时候所操作的都不是完全相同的promise对象。

![promise原理图](http://liubin.org/promises-book/Ch2_HowToWrite/img/then_catch.png)

####案例：

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


####分析

在上述例子中，Promise.resolve(42)接受了value后返回 一个新的Promise对象A，接着then通过链式接受A对象并分别指定回调函数里面的参数resolve和reject；

对于badMain函数，then方法回调函数参数里面的的resolve传入一个threwError函数E并抛出异常，在回调参数里面的的resolve传入onRejected函数。因为异常函数传入then参数的resolve，reosolve抛出的异常需要在下一个then的参数reject 或 在then后面的catch来接收异常，所以badMain函数没有被调用；

对于goodMain函数，then方法回调函数参数的resolve抛出异常，并有then后面的catch函数接受异常函数E，所以goodMain被调用并正常打印成功。

####总结

这里我们又学习到了如下一些内容。

1.使用promise.then(onFulfilled, onRejected) 的话

+ 在 onFulfilled 中发生异常的话，在 onRejected 中是捕获不到这个异常的。

2.在 promise.then(onFulfilled).catch(onRejected) 的情况下

+ then 中产生的异常能在 .catch 中捕获

3..then 和 .catch 在本质上是没有区别的

+ 需要分场合使用。

我们需要注意如果代码类似 badMain 那样的话，就可能出现程序不会按预期运行的情况，从而不能正确的进行错误处理。


###Promise测试
总结编写Promise 的测试代码

1.使用Mocha测试框架
现在使用 Mocha来对Promise 进行基本的测试

使用Mocha的主要基于下面3点理由：
+ 它是非常著名的测试框架
+ 支持基于Node.js 和浏览器的测试
+ 支持"Promise测试"

####Macha简介
>Mocha可以自由选择BDD、TDD、exports中的任意风格，测试中用到的Assert 方法也同样可以跟任何其他类库组合使用。 也就是说，Mocha本身只提供执行测试时的框架，而其他部分则由使用者自己选择

+ [Mocha官网](https://mochajs.org/)
+ Mocha是Node.js下的测试框架工具

####Macha-回调函数风格的测试

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
    
###Promise进阶(Advanced)

####Promise的实现类库（Library）
>ES6 Promise 里关于promise对象的规定包括在使用 catch 方法，或使用 Promise.all 进行处理的时候不能出现错误。

Promises/A+ 是 ES6 Promises 的前身，Promise的 then 也是来自于此的基于社区的规范。

如果说一个类库兼容 Promises/A+ 的话，那么就是说它除了具有标准的 then 方法之外，很多情况下也说明此类库还支持 Promise.all 和 catch 等功能。

但是 Promises/A+ 实际上只是定义了关于 Promise#then 的规范，所以有些类库可能实现了其它诸如 all 或 catch 等功能，但是可能名字却不一样。

如果我们说一个类库具有 then 兼容性的话，实际上指的是 Thenable ，它通过使用 Promise.resolve 基于ES6 Promise的规定，进行promise对象的变换。

####Polyfill和扩展类库
在这些Promise的实现类库中，我们这里主要对两种类型的类库进行介绍。

>一种是被称为 Polyfill （这是一款英国产品，就是装修刮墙用的腻子，其意义可想而知 — 译者注）的类库，另一种是即具有 Promises/A+兼容性 ，又增加了自己独特功能的类库。

>Promise的实现类库数量非常之多，这里我们只是介绍了其中有限的几个。

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

####Promise.resolve和Thenable
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

####使用reject而不是throw

