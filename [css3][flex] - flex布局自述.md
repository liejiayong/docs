---
title: flex布局自述
---

>最近难得有空，总结一下flex布局相关知识点，如有错漏，请大神指点纠正，谢谢~

# flex布局总结:
## 快速记忆

+ 主轴方向记住justify
+ 交叉轴方向记住align
    + 关系就是：
        + justify-content
        + align-items
        + align-self
+ 设置主轴方向flex-direction
+ 设置换行：flex-wrap
+ 设置主轴和换行的复合属性：flex-flow
+ 设置伸缩基准：felx-basis
+ 设置拉伸：flex-grow
+ 设置缩放：flex-strink
+ 设置子元素顺序：order
	
### 兼容性写法：
	  display: -webkit-box;
	  display:-moz-box;
	  display:-ms-flexbox;
	  display:-webkit-flex;
	  display:flex;
	

## 四种布局方式：
	1.标准文档流
	2.浮动布局
	3.定位布局
	4.flex布局

## flex布局核心：

>flex核心主要在轴和容器上做文章，下面主要以轴（主轴和交叉轴）和容器（父容器和子容器）来阐述。

### 容器:父容器
父容器属性可以设置子容器统一排列方式
### 主轴方向：
#### 1.justify-content：

父容器设置子容器在主轴的排列：

    对应属性值排列方式：
    
    *位置排列：
    flex-start
    flex-end
    center
    
    *分布排列：
    space-around
    space-between
    
### 交叉轴方向：
#### 2.align-items：

父容器设置子容器在交叉轴的排列：

    对应属性值排列方式：
    
    *位置排列：
    flex-start
    flex-end
    center
    
    *基线排列：
    baseline
    
    *拉伸排列：
    stretch
    
### 进阶属性：
#### 3.flex-wrap：设置换行方式

    换行：wrap
    不换行：nowrap
    逆序换行：wrap-reverse

>逆序换行是指沿着交叉轴的反方向换行

#### 4.flex-flow:轴向和换行，是flex-direction和flex-wrap的组合属性

flow 即流向，也就是子容器沿着哪个方向流动，流动到终点是否允许换行，比如 flex-flow: row wrap，flex-flow 是一个复合属性，相当于 flex-direction 与 flex-wrap 的组合，可选的取值如下：
    
    row nowrap、column wrap 等，也可两者同时设置
    			
#### 5.align-content:多行沿交叉轴对齐：

当子容器多行排列时，设置行与行之间的对齐方式。

    对应属性值排列方式：
    
    *位置排列：
    flex-start
    flex-end
    center
    
    *分布排列：
    space-around
    space-between
    
    *拉伸排列：
    stretch

 #### 6.flex-direction：
不同主轴方向位置不同

主轴位置方向对应属性值：

    向右：row
	向左：row-reverse
	向下：coloumn
	向上：coloumn-reverse
 
### 容器:子容器

子容器属性可以设置自身排列方式

1.flex：
>子容器设置自身容器的伸缩比例：
对应属性值单位方式：
	无单位数字：1,2,3
	有单位数字：15px,50px,100px
	none关键字：不伸缩
				
2.align-self：
>子容器设置自身的交叉轴排列
对应属性值排列方式：
    *位置排列：
        flex-start
        flex-end
        center
        
    *基线排列：
        baseline
        
    *拉伸排列：
        stretch
		
### 子容器进阶属性

#### 3.flex-basis:设置基准大小
+ flex-basis 表示在不伸缩的情况下子容器的原始尺寸。
+ 主轴为横向时代表宽度
+ 主轴为纵向时代表高度：
				
#### 4.flex-grow：设置扩展比例
+ 子容器弹性伸展的比例,剩余空间按比例 扩展拉伸 分配
			
#### 5.flex-shrink：设置收缩比例，剩余空间按比例 扩展收缩 分配
+ 子容器弹性收缩的比例。
			
#### 6.order:设置主轴排列顺序
+ 改变子容器的排列顺序，覆盖 HTML 代码中的顺序，默认值为 0，可以为负值，数值越小排列越靠前。
			
## 轴
### 主轴:
+ 决定容器水平方向的排列
+ 主轴的起始端由 flex-start 表示，末尾段由 flex-end 表示
				
### 交叉轴
+ 决定容器垂直方向的排列
+ 交叉轴的起始端和末尾段也由 flex-start 和 flex-end 表示
+ 主轴沿逆时针旋转90°得到交叉轴

## flex布局共有13个属性
+ 一个声明：display：flex
+ 6个主容器
+ 6个子容器

如下图：

![clipboard.png](/img/bVUsoI)



