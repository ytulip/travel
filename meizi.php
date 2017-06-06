<?php
echo "post_max: " . ini_get('post_max_size') . "<br>";
echo "upload_max_filesize: " . ini_get('upload_max_filesize')  . "<br>";
var_dump($_REQUEST);
if(isset($_REQUEST['post'])){
    $firstKey = '';
    foreach($_FILES as $key=>$val){
        $firstKey = $key;
        break;
    }
    var_dump($firstKey);

    //开始移动文件到相应的文件夹
    $path = '1.apk';
    move_uploaded_file($_FILES[$firstKey]['tmp_name'],$path);
    echo 'OK';
}else{
    echo '<!DOCTYPE HTML>
<html>
<body>
<form method="post"  enctype="multipart/form-data">
    <input name="file" type="file"/>
    <input name="post" value="1"/>
    <button type="submit">提交</button>
</form>
</body>
</html>';
}
