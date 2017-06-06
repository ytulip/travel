在图片上传的使用场景中，人们总是期盼能及时看到图片上传的效果，所以说图片预览很有必要。根据图片提交服务器并保存这个时间节点划分，图片预览又可以分为本地图片预览和服务器图片预览。

##一、本地图片预览
###1.使用对象URL
使用Web API 接口中的[URL.createObject](https://developer.mozilla.org/zh-CN/docs/Web/API/URL/createObjectURL)可以创建一个给定的对象的URL。该URL的生命周期和当前的document绑定，所以复制在新窗口中打开无效。
```
<input onchange="preview(this)" type="file"/>
<img id="img_show"/>

<script>
function preview(e){
    var file = e.files[0];
    var url = window.createObjectURL(file);
    document.querySelector('#img_show').setAttribute('src',url);
}
</script>
```
###2.使用数据URI
数据URI则是通过将图片内容装换成base64格式的字符串显示出来。将此串复制黏贴在其它页面能显示出图片，串长度随文件大小的增加而增加。下面是根据File API的FileReader类型获得图片的数据dataURI。
```
<input onchange="preview(this)" type="file"/>
<img id="img_show"/>

<script>
function preview(e){
    var file = e.files[0];
    var reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = function(){
        document.querySelector('#img_show').setAttribute('src',reader.result);
    }
}
</script>

```

##二、服务器图片预览
###1.使用返回的图片地址
在实际应用中，我更加偏爱这种方式。如果一个图片要上传到服务器，那么何不把它的上传时间提前啦。
```
<input onchange="preview(this)" type="file"/>
<img id="img_show"/>

<script>
function preview(e) {
	var formData = new FormData();
	formData.append("file", e.currentTarget.files[0]);
	$.ajax({
		url: '',/*保存图片方法*/
		data: formData,
		type: 'post',
		contentType: false,
		processData: false,
		dataType: 'json',
		success: function(data) {
          //如果成功的话，返回一个{"status":true,"path":"/1.jpg"}这样的json格式字符串
           document.querySelector('#img_show').setAttribute('src',reader.result);
		}
	});
}
</script>
```
最后在整个页面提交的时候，把src存入数据库就行了。当时这样的做法有以下缺点：
*  上传的图片最后可能不会保存数据库，造成服务器空间浪费
*  从选择完图片到预览加载完成这个过程比起本地预览耗时更久