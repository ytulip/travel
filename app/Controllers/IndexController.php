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

    public function savework(){
        $filename = \MM\Kits::getMillisecond() . '.jpg';
        PostImage::save(index_path . '/images/work/' . $filename);
        echo json_encode(array('status'=>true,'path'=>'/images/work/' . $filename,'type'=>IndexController::input('type',1)));
        exit;
    }

    /**
     * 将一个work存储起来
     */
    public function storagework(){
        $object = new EssayModel();
        $insertId = $object->insert(\MM\MArray::arrayOnly($_REQUEST,['essay']));
        echo json_encode(['status'=>true,'data'=>$insertId]);
        exit;
    }

    static public function input($index,$default=''){
        if(isset($_REQUEST[$index]) && ($_REQUEST[$index] !== '')){
            return $_REQUEST[$index];
        }else{
            return $default;
        }
    }
}