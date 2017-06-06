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

    public function essay(){
        $object = new EssayModel(IndexController::input('id'));
        echo json_encode(['status'=>true,'data'=>json_decode($object->essay),'title'=>$object->essay_title,'cover'=>$object->cover]);
        exit;
    }

    /**
     *
     */
    public function essayList(){
        if($type = intval(IndexController::input('type'))){
            $list = DB::select('select * from essays where essay_type = ' . $type);
        }else{
            $list = DB::select('select * from essays');
        }
        //$list = DB::select('select * from essays');
        for($i=0 ; $i < count($list); $i++){
            $essay = json_decode($list[$i]->essay);
            $segmentFirst = $essay[0];
            if($segmentFirst->data->type == 1) {
                $list[$i]->segment = mb_substr($segmentFirst->data->val, 0, 40);
            }else{
                $list[$i]->segment = "";
            }
        }
        echo json_encode(array('status'=>true,'data'=>$list));
        exit;
    }

    /**
     * 将一个work存储起来
     */
    public function storagework(){
        if(IndexController::input('id')){
            $object = new EssayModel(IndexController::input('id'));
            $object->update(\MM\MArray::arrayOnly($_REQUEST, ['essay','essay_title','cover']));
            echo json_encode(['status' => true]);
            exit;
        }else {
            $object = new EssayModel();
            $insertId = $object->insert(\MM\MArray::arrayOnly($_REQUEST, ['essay','essay_title','cover']));
            echo json_encode(['status' => true, 'data' => $insertId]);
            exit;
        }
    }

    public function markdown()
    {

        $mdStr = \Michelf\MarkdownExtra::defaultTransform(file_get_contents(storage_path . '/md/demo.md'));
        echo '<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 101 Template</title>
    <link href="/css/markdown.css" rel="stylesheet"></link> 
  </head>
  <body>'.$mdStr.'
  </body>
</html>';
        exit;
    }

    public function blogs(){
        return View::show('home.html',[]);
    }

    public function post()
    {
        $id = IndexController::input('id');
        $mdStr = \Michelf\MarkdownExtra::defaultTransform(file_get_contents(storage_path . '/md/demo.md'));
        return View::show('blog.html',array(
            'article'=>$mdStr
        ));
    }

    public function blog()
    {
        $id = IndexController::input('id');
        $mdStr = \Michelf\MarkdownExtra::defaultTransform(file_get_contents(storage_path . '/md/demo.md'));
        return View::show('blog.html',array(
            'article'=>$mdStr
        ));
    }

    static public function input($index,$default=''){
        if(isset($_REQUEST[$index]) && ($_REQUEST[$index] !== '')){
            return $_REQUEST[$index];
        }else{
            return $default;
        }
    }
}