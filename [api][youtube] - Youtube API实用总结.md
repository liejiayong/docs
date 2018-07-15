---
title: Youtube API 实用总结
---

# [Youtbube API](https://developers.google.com/youtube/player_parameters)

## 概述

本文档介绍了如何在应用中嵌入YouTube播放器，并定义了YouTube嵌入式播放器可以使用的参数。

通过将参数附加到iframe网址，您可以自行设置应用中的播放体验。例如，您可以使用autoplay参数自动播放视频，也可以使用loop参数重复播放某个视频。您还可以使用enablejsapi参数为播放器启用JavaScript API。

## 嵌入YouTube播放器

>iframe嵌入embed

    以下<iframe>代码将会加载一个可用来播放YouTube视频M7lc1UVf-VE的640x360像素的播放器。由于网址会将autoplay参数设为1，因此视频会在播放器加载完后自动播放。
    
    <iframe id="youtube-player" type="text/html" width="100%" height="55vw"　src="https://www.youtube.com/embed/oTQ5K3P7Jcg?&autoplay=1&controls=0&disablekb=1&loop=1&playlist=oTQ5K3P7Jcg&playsinline=1&origin=https://www.wuumy.com&iv_load_policy=3&modestbranding=1&showinfo=0&fs=1&rel=0&iv_load_policy=3"　 frameborder="0" style="display:block"></iframe>
    
## 常用Parameters

|       名字          |           说明          |
|---------------------|-------------------------|
|   autoplay          | 0|1 0=不自动            |
|   controls          | 0|1 0不显示操作栏       |
|   disablekb         | 0|1 0不显示             |                 

     