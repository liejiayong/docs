---
title: doc命令操作适用总结
---

## 查看所有文件（包括文件夹）

    dir /s

## 查看指定格式文件

    dir *.doc /s

    dir c:\baidu /s 命令，查看当前目录所有子目录的文件和文件夹

    dir c:\baidu /w 命令，以紧凑方式显示文件和文件夹

    dir c:/baidu /p 命令，以分页方式显示


## 操作：start 文件绝对路径
例如：

    start C:\1.log

### 还可以尝试以下的用法:

    浏览：type a.txt
    编辑：edit a.txt


## 创建文本

    echo>abc.txt
    echo>abc.doc

## 修改文本

    copy con abc.txt

## 新建文件夹

    md filename

## 实战

cp [-adfilprsu] source(原文件) destination(目标文件)
记录一些常用的：

  -f:强制的意思，若有重复或其他疑问时，不会询问用户，而强制复制。
  -i:若目标文件已经存在，在覆盖时先询问是否真的操作。
  -r:递归持续复制，用于目录的复制操作。
  -u:若目标文件比源文件旧，则更新目标文件。

rm [-fir] 文件和目录
-f:就是force的意思，强制删除
-i:交互模式，在删除前会询问用户是否操作
-r:递归删除，常用在目录的删除
删除命令相当于DOS下的del命令，注意：Linux系统中，为了怕文件被误删，很多版本默认有-i参数，-i是每个文件被删除之前会让用户确认一次，以防止误删。

mv [-fiu] source destination
-f:强制直接移动不询问
-i:若目标文件已经存在，就会询问是否覆盖
-u:若目标文件已经存在，且源文件比较新，才会更新
mv命令还有重命名的作用：

mv demo1 demo2
移动多个文件或目录则最后一个目标文件一定是“目录”。

mv demo1 demo2 index.html demo3
把demo1,demo2和index.html移动到demo3中

