<?php
class IndexController{
    public function map(){
        return View::show('map.html',array());
    }

    public function saveImage(){
        $imgstr = 'data:image/gif;base64,R0lGODlhAQABANEAAAAAAAAAAAAAAAAAACH5BAkZAAAALAAAAAABAAEAAAICRAEAOw==';
        $imgdata = substr($imgstr,strpos($imgstr,",") + 1);
        $decodedData = base64_decode($imgdata);
        file_put_contents('11.gif',$decodedData );
    }
}