---
title: File API 系统整理
---

# File API 系统整理

## File API 关联的一些构造器

### [Blob对象](https://developer.mozilla.org/zh-CN/docs/Web/API/Blob)

> 优势：允许我们可以通过JS直接操作二进制数据

+ 表示一个不可变、原始数据的类文件对象。
+ 表示的不一定是JavaScript原生格式的数据。
+ `文件 接口 基于 Blob`，继承了 blob 的功能并将其扩展使其支持用户系统上的文件

#### 构造函数
    Blob(blobParts[, options])
    返回一个新创建的 Blob 对象，其内容由参数中给定的数组串联组成

#### 属性
    Blob.size 只读
    Blob 对象中所包含数据的大小（字节）。
    Blob.type 只读
    一个字符串，表明该Blob对象所包含数据的MIME类型。如果类型未知，则该值为空字符串。
    
#### 方法Edit
    Blob.slice([start,[ end ,[contentType]]])
    返回一个新的 Blob对象，包含了源 Blob对象中指定范围内的数据。
    
#### Events
+ loadstart
+ abort
+ error
+ load
+ loadend

###[File对象](https://developer.mozilla.org/zh-CN/docs/Web/API/File)

> 文件(File) 接口提供有关文件的信息，并允许网页中的 JavaScript 访问其内容。

+ File 对象是特殊类型的 Blob，且可以用在任意的 Blob 类型的 context 中

####构造函数File(file)
####属性
File 接口也继承了  Blob 接口的属性：

+ File.lastModified： `（只读）`。 返回当前 File 对象所引用文件最后修改时间， 自 1970年1月1日0:00 以来的毫秒数。
+ File.lastModifiedDate： `（只读）`。   返回当前 File 对象所引用文件最后修改时间的 Date 对象。
+ File.name： `（只读）`。 返回当前 File 对象所引用文件的名字。
+ File.size： `（只读）`。 返回文件的大小。
+ File.webkitRelativePath： `（只读）`。  返回 File 相关的 path 或 URL。
+ File.type： `（只读）`。返回文件的 多用途互联网邮件扩展类型

###[FileList](https://developer.mozilla.org/zh-CN/docs/Web/API/FileList)

来源途径：
+ HTML input元素获取的files属性
+ 拖放操作的DataTransfer对象

####方法
    File item(index);
    File item(
       index
     );

###[FileReader](https://developer.mozilla.org/zh-CN/docs/Web/API/FileReader)

####属性
+ FileReader.error： `（只读）`。一个DOMException，表示在读取文件时发生的错误 。
+ FileReader.readyState： `（只读）`。表示FileReader状态的数字。取值如下：

|常量名    	|值	    |描述               |
| --------- |-------|-------------------|
| EMPTY     | 0     |还没有加载任何数据 |
| LOADING	| 1	    |数据正在被加载.    |
| DONE	    | 2	    |已完成全部的读取请求|

    
+ FileReader.result： `（只读）`。
文件的内容。该属性仅在读取操作完成后才有效，数据的格式取决于使用哪个方法来启动读取操作。

#### 方法
>一下四个方法的共同之处：开始读取指定的 Blob中的内容, 一旦完成，result包含对应的信息

+ ArrayBuffer readAsArrayBuffer(Blob blob)：读取文件并将一个包含文件内容的`ArrayBuffer对象`保存在result属性中。
+ DOMString readAsBinaryString(Blob blob)：读取文件并将`一个字符串`保存在result属性中，字符串中的每个字符表示一个字节。
+ DOMString readAsText(Blob blob, optional DOMString encoding)：以纯文本的方式读取文件，result属性中将包含 `一个字符串`以表示所读取的文件内容。
+ DOMString readAsDataURL(Blob blob)：result属性中将包含一个data: `URL格式的字符串`以表示所读取文件的内容。

### [FileReaderAync](https://developer.mozilla.org/zh-CN/docs/Web/API/FileReaderSync)
FileReaderSync接口允许以`同步`的方式读取File或Blob对象中的内容.

>该接口`只在workers`里可用,因为在主线程里进行`同步I/O操作`可能会阻塞用户界面.

使用方法同readerReader