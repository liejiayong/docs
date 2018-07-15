---
title: git命令笔记总汇
---

# 总结在前
[学习git推荐网站](https://www.liaoxuefeng.com/wiki/0013739516305929606dd18361248578c67b8067c8c017b000/001374829472990293f16b45df14f35b94b3e8a026220c5000)
[本人博客-兼乎](http://www.twicetech.top)


    >Unix哲学：“没有消息就是好消息”
    Git，一个代码管理库
# git汇总

## git仓库 的 新建、添加、提交

    git init    ---创建创库

    git add xxx  ---添加 xxx 到 git仓库

    git commit -m "wrote a readme file"  ---提交readme到创库
                                            -m为提交说明：wrote a readme file
									
    add 和 commit 提交区别：
    >add是每次提交一个文件，多次提交多个文件
    >commit是一次提交很多文件

## git仓库文件 的 删除、重命名

    git rm -r file.js   ---删除已跟踪的文件清单中的文件

    git mv oldFile newFile   ---对已跟踪的文件进行重命名操作

    git mv file folder ---移动file到folder
    git mv file file1 file2… folder --- 移动file、file1、file2…到folder

## git仓库 的 状态、版本、信息、回溯

    git status  ---查看仓库状态

    git diff  ---查看仓库文档修改状态，显示修改字段

    git log  ---查看历史记录

    git reflog  ---记录每一次命令的commit_id

    Git config --list   --- 查看git配置

git reset --hard commit_id ---回退到上一个版本，HEADID^为每个commit_id
	*git reflog
	*git reset -hard HEAD^
    
    实战：
    git add readme.txt
    git status    此时  ：q 为退出状态
    git commit -m "add distributed"
    git status 
	
## 分支

### 分支总结
    切换branch，实际是切换HEAD指针的指向

Git中保存着一个名为 **HEAD** 的特别指针，这个指针指向了当前的工作分支。

    创建分支：git branch 分支名字
    切换分支：git checkout 分支名字
    查看分支：git branch
    合并分支：git merge 要合并的分支
    删除分支：git branch –d 要删除分支

### 分支初始化

git branch   ---查看分支状态

git branch branchName   ---创建分支。创建 以branchName命名的分支

git checkout  branchName ---切换分支、比较少用的返回版本。切换为branchName分支

### 合并分支

    具体步骤，首先返回master分支，其次运行合并命令
    
    git merge 被合并的分支
 
git checkout master

git merge branchName

### 删除分支

git branch –d 要删除的分支

### 修改远程仓库

方法有三种：

1.修改命令
+ git remote set-url origin [url]
2.先删后加
+ git remote rm origin
+ git remote add origin [url]
3.直接修改config文件

## 代码合并
代码合并。pull=fetch+merge

    有时候代码不能同步的时候，我们先合并本地和服务器的代码，才能git commit本地代码到服务器上面
 
git pull origin master ---下载代码
git pull --rebase origin master ---合并代码
	
    Github demo     
    …or create a new repository on the command line
    
    echo "# somethingElse" >> README.md
    git init
    git add README.md
    git commit -m "first commit"
    git remote add origin https://github.com/liejiayong/somethingElse.git
    git push -u origin master
    
    …or push an existing repository from the command line

# github的一些问题指令

## 同步合并代码
git remote add origin https://github.com/liejiayong/xxx.git	添加分支

git push -u origin master	将本地 文件  推到 github 服务器

git pull --rebase origin master	将本地和服务器代码合并

    github
    …or create a new repository on the command line
    echo "# Program-Coding-Specfication" >> README.md
    git init
    git add README.md
    git commit -m "first commit"
    git remote add origin https://github.com/liejiayong/Program-Coding-Specfication.git
    git push -u origin master
    …or push an existing repository from the command line
    git remote add origin https://github.com/liejiayong/Program-Coding-Specfication.git
    git push -u origin master

    *   From <https://github.com/liejiayong/Program-Coding-Specfication> 

##
	
	


	



