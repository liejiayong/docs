<h2>jQuery之$.extend({},{},...)与$.fn.extend({})</h2>
<section>
jQuery为开发插件提拱了两个方法，分别是：
<ul>
<li>jQuery.fn.extend();</li>
<li>jQuery.extend();
</li>
<li>jQuery.fn
</li>
<li>jQuery.fn=jQuery.prototype={
init:function(selector,context){//….
//……
};
</li>
</ul>
</section>
<section>
<h3>深入透析：</h3>
<p>jQuery.fn=jQuery.prototype.对prototype肯定不会陌生啦。虽然javascript　没有明确的类的概念，但是用类来理解它，会更方便。jQuery便是一个封装得非常好的类，比如我们用
语句　$(“#btn1″)会生成一个jQuery类的实例。
</p>
</section>
<section>
<strong>jQuery.extend(object)</strong>
<p>为jQuery类添加类方法，可以理解为添加静态方法。如：
jQuery.extend({
min:function(a,b){returna<b?a:b;},
max:function(a,b){returna>b?a:b;}
});
jQuery.min(2,3);// 2
jQuery.max(4,5);// 5
</p>
<strong>ObjectjQuery.extend(target,object1,[objectN])</strong>
<p>用一个或多个其他对象来扩展一个对象，返回被扩展的对象
varsettings={validate:false,limit:5,name:"foo"};
varoptions={validate:true,name:"bar"};
jQuery.extend(settings,options);
结果：settings=={validate:true,limit:5,name:"bar"}
<strong>建议不要之间用settings、options对象扩展，防止对象结构造破坏</strong>
<p>所以我们可以这样子：
varsettings={validate:false,limit:5,name:"foo"};
varoptions={validate:true,name:"bar"};
varresults=$.extend({},settings,options);
结果：results和P{}返回同上
</p>
</p>
jQuery.fn.extend(object);
对jQuery.prototype进得扩展，就是为jQuery类添加“成员函数”。jQuery类的实例可以使用这个“成员函数”。
比如我们要开发一个插件，做一个特殊的编辑框，当它被点击时，便alert当前编辑框里的内容。可以这么做：
$.fn.extend({          
    alertWhileClick:function(){            
          $(this).click(function(){                 
                 alert($(this).val());           
           });           
     }       
});       
$("#input1").alertWhileClick(); //页面上为：    
$("#input1")　为一个jQuery实例，当它调用成员方法alertWhileClick后，便实现了扩展，每次被点击时它会先弹出目前编辑里的内容。
jQuery.extend()的调用并不会把方法扩展到对象的实例上，引用它的方法也需要通过jQuery类来实现，如jQuery.init()，而
jQuery.fn.extend()的调用把方法扩展到了对象的prototype上，所以实例化一个jQuery对象的时候，它就具有了这些方法，这是很重要的，在jQuery.js中到处体现这一点
jQuery.fn.extend=jQuery.prototype.extend
你可以拓展一个对象到jQuery的prototype里去，这样的话就是插件机制了。
(function($){
$.fn.tooltip=function(options){
};
//等价于
vartooltip={
function(options){
}
};
$.fn.extend(tooltip)=$.prototype.extend(tooltip)=$.fn.tooltip
})(jQuery);

</section>
