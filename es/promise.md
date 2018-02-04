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


