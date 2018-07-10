<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>


<h3 class="border-solid-left">
    #总结在前
</h3>
<div class="padding-default">
    <h3>css引入外部字体</h3>
    <p>@font-face{</p>
    <p>font-family: 'YaHei Consolas Hybrid';</p>
    <p>src : url('../fonts/yaheiconsolashybrid.ttf');</p>
    <p>}</p>

    <p>body{</p>
    <p>font-family: 'YaHei Consolas Hybrid';</p>
    <p>font-size: 16px;</p>
    <p>background: url(../img/bgContent.png) repeat;</p>
    <p>}</p>
    <p>不同浏览器字体的格式有差别，字体后缀和浏览器有关，如下所示</p>

    <ul class="item-list">
        <li>* .TTF或.OTF，适用于Firefox 3.5、Safari、Opera</li>
        <li>* .EOT，适用于Internet Explorer 4.0+</li>
        <li>* .SVG，适用于Chrome、IPhone</li>
    </ul>

    <p>比如:</p>
    <p>@font-face {</p>
    <p>font-family: 'HansHandItalic';</p>
    <p>src: url('fonts/hanshand-webfont.eot');</p>
    <p>src: url('fonts/hanshand-webfont.eot?#iefix') format('embedded-opentype'),</p>
    <p>url('fonts/hanshand-webfont.woff') format('woff'),</p>
    <p>url('fonts/hanshand-webfont.ttf') format('truetype'),</p>
    <p>url('fonts/hanshand-webfont.svg#webfont34M5alKg') format('svg');</p>
    <p>font-weight: normal;</p>
    <p>font-style: normal;</p>
    <p>}</p>
</div>
<h3 class="border-solid-left">#@Font-face目前浏览器的兼容性：</h3>
<dl>
    <dt>Webkit/Safari(3.2+) </dt>
    <dd>TrueType/OpenType TT (.ttf) 、OpenType PS (.otf)；</dd>
    <dt>Opera (10+) </dt>
    <dd>TrueType/OpenType TT (.ttf) 、 OpenType PS (.otf) 、 SVG (.svg)；</dd>
    <dt>Internet Explorer </dt>
    <dd>自ie4开始，支持EOT格式的字体文件；ie9支持WOFF；</dd>
    <dt>Firefox(3.5+) </dt>
    <dd>TrueType/OpenType TT (.ttf)、 OpenType PS (.otf)、 WOFF (since Firefox 3.6)</dd>
    <dt>Google Chrome </dt>
    <dd>TrueType/OpenType TT (.ttf)、OpenType PS (.otf)、WOFF since version 6</dd>
</dl>
<h3>由上面可以得出：.eot + .ttf /.otf + svg + woff = 所有浏览器的完美支持</h3>

<h3 class="border-solid-left">#font-face语法</h3>
<div class="padding-default">
    <p>@font-face {</p>
    <p> [font-family: &lt;family-name>;]?</p>
    <p> [src: [ &lt;uri> [format(&lt;string>#)]? | &lt;font-face-name> ]#;]?</p>
    <p> [unicode-range: &lt;urange>#;]?</p>
    <p> [font-variant: &lt;font-variant>;]?</p>
    <p> [font-feature-settings: normal|&lt;feature-tag-value>#;]?</p>
    <p> [font-stretch: &lt;font-stretch>;]?</p>
    <p> [font-weight: &lt;weight>];</p>
    <p> [font-style: &lt;style>];</p>
    <p> }</p>
</div>
<h3 class="border-solid-left">#font-face 在css中具体的 使用方法</h3>

<div class="padding-default">
    <p>/*声明 WebFont*/</p>
    <p>@font-face {</p>
    <p>font-family: 'pinghei';</p>
    <p>src: url('../font/pinghei.eot');</p>
    <p>src:</p>
    <p>url('../font/pinghei.eot?#iefix') format('embedded-opentype'),</p>
    <p>url('../font/pinghei.woff') format('woff'),</p>
    <p>url('../font/pinghei.ttf') format('truetype'),</p>
    <p>url('../font/pinghei.svg') format('svg');</p>
    <p>font-weight: normal;</p>
    <p>font-style: normal;</p>
    <p>}</p>
    <p>/*使用指定字体*/</p>
    <p>.test {</p>
    <p>font-family: 'pinghei';</p>
    <p>}</p>
</div>


</body>
</html>