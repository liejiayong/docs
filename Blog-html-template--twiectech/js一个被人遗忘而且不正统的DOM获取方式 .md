<h3 class="border-solid-left">
说在前面的话
</h3>
世间万物真奇妙，有无意中的灵感，有突然碰见的bug，有让你内心波涛汹涌的客户。而奇妙的偶然或必然往往是用心对待的结果，需要自己去把握。

最近我比较喜欢一个词“格物致知”，大意是探索事物，总结知识。不管在哪一个领域，你都可以随意随意、畅所欲言，不过前提是你需要有足够的专业度。本人喜欢探索事物，我相信这会对我的人生产生有很大的影响，而在遇到不懂的东西时，请上网找手册找文案，上网找手册找文案，上网找手册找文案，将不懂的弄得一清二楚，重要的事情说三遍。

而现在我想总结一个不靠边的技巧，技巧并不是正统的、标准的技术或许或会让你的代码产生异响不到的不好效果，这是技术的一个缩影。下面纯属技术娱乐，你懂得。
<h3 class="border-solid-left">
标准获取DOM的方式
</h3>
当代获取DOM元素的<strong>标准方法</strong>要有
document.querySelector
document.getElementById
等。
如标准代码应该是：
let box = document.querySelector('#box')
let btn= document.querySelector('#btn')

btn.addEventListener('click',() => {
box.style.background = 'blue';
})
<div class="height-js">
<script async src="//jsfiddle.net/CHOVitaminL/hw6hjqsf/embed/"></script>
</div>
<h3 class="border-solid-left">
不正统的缩阴式获取DOM
</h3>
很简单，就是直接将DOM上的ID属性和name属性作为一个js DOM变量
代码如下：
box.style.background = 'lightblue';
btn.style.background = 'lightblue';
这是一些旧浏览器的方法，只有小数浏览器兼容，不是现代浏览器标准，不建议使用。
end.

