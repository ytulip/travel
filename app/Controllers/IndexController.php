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

    public function comment(){
        $comment = new Comment();
        $insertId = $comment->insert([
            'comment_context'=>IndexController::input('comment'),
            'work_id'=>IndexController::input('id'),
            'nickname'=>IndexController::input('nickname'),
            'u_email'=>IndexController::input('email'),
            'created_at'=>date('Y-m-d H:i:s'),
            'comment_level'=>IndexController::input('comment_level'),
            'replay_to'=>IndexController::input('replay_to'),
            'parent_id'=>IndexController::input('parent_id')
        ]);
        $object = new Comment($insertId);
        $item = $object->_model_object;
        $item->created_at = \MM\Kits::timeDescribe($item->created_at);
        $item->replay_info = json_encode(['parent_id'=>$item->parent_id?$item->parent_id:$item->id,'replay_to'=>$item->nickname]);
        $item->avatar = \MM\Kits::getAvatar($item->u_email);
        $item->child = [];
        echo json_encode(['status'=>true,'data'=>$item]);
        exit;
    }

    public function commentlist(){
        $lists = DB::select('select * from comment where work_id = ' . IndexController::input('id'));
        foreach($lists as $key=>$val){
            $lists[$key]->created_at = \MM\Kits::timeDescribe($val->created_at);
            $lists[$key]->replay_info = json_encode(['parent_id'=>$val->parent_id?$val->parent_id:$val->id,'replay_to'=>$val->nickname]);
            $lists[$key]->avatar = \MM\Kits::getAvatar($val->u_email);
        }
        $commentList = [];
        foreach ($lists as $item){
            if($item->comment_level == '1'){
                $item->child = [];
                $commentList[] = $item;
            }
        }



        foreach($lists as $item){
            if($item->comment_level == '1'){
                continue;
            }

            $ind = \MM\Kits::secondaryKeyIndex($commentList,'id',$item->parent_id);
            if($ind != -1){
                $commentList[$ind]->child[] = $item;
            }
        }

        echo json_encode(['status'=>true,'data'=>$commentList]);
        exit;
    }

    public function pronounce()
    {
        return View::show('pronounce.html',[]);
    }

    /**
     *
     */
    public function wp()
    {
        // Creating the new document...
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        /* Note: any element you append to a document must reside inside of a Section. */

// Adding an empty Section to the document...
        $section = $phpWord->addSection();
// Adding Text element to the Section having font styled by default...
        $section->addText(
            '"Learn from yesterday, live for today, hope for tomorrow. '
            . 'The important thing is not to stop questioning." '
            . '(Albert Einstein)'
        );

        /*
         * Note: it's possible to customize font style of the Text element you add in three ways:
         * - inline;
         * - using named font style (new font style object will be implicitly created);
         * - using explicitly created font style object.
         */

// Adding Text element with font customized inline...
        $section->addText(
            '"Great achievement is usually born of great sacrifice, '
            . 'and is never the result of selfishness." '
            . '(Napoleon Hill)',
            array('name' => 'Tahoma', 'size' => 10)
        );

// Adding Text element with font customized using named font style...
        $fontStyleName = 'oneUserDefinedStyle';
        $phpWord->addFontStyle(
            $fontStyleName,
            array('name' => 'Tahoma', 'size' => 10, 'color' => '1B2232', 'bold' => true)
        );
        $section->addText(
            '"The greatest accomplishment is not in never falling, '
            . 'but in rising again after you fall." '
            . '(Vince Lombardi)',
            $fontStyleName
        );

// Adding Text element with font customized using explicitly created font style object...
        $fontStyle = new \PhpOffice\PhpWord\Style\Font();
        $fontStyle->setBold(true);
        $fontStyle->setName('Tahoma');
        $fontStyle->setSize(13);
        $myTextElement = $section->addText('"Believe you can and you\'re halfway there." (Theodor Roosevelt)');
        $myTextElement->setFontStyle($fontStyle);

// Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('helloWorld.docx');

// Saving the document as ODF file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'ODText');
        $objWriter->save('helloWorld.odt');

// Saving the document as HTML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        $objWriter->save('helloWorld.html');

        /* Note: we skip RTF, because it's not XML-based and requires a different example. */
        /* Note: we skip PDF, because "HTML-to-PDF" approach is used to create PDF documents. */
    }

    public function wps()
    {
        $word2007 = new \PhpOffice\PhpWord\Reader\Word2007();
//        var_dump($word2007->isReadDataOnly());
        $phpWord = $word2007->load('helloWorld.docx');
//        $phpWord->getWriterPart('Body')->write();
//        $phpWord->
//        $word2007->setReadDataOnly(false);
//        var_dump($word2007->getReadDataOnly());


//        $a = new \PhpOffice\PhpWord\Writer\Word2007\Element\Text();
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
        echo $objWriter->getWriterPart('Body')->write();
 //       $objWriter->save('helloWorld2.html');
    }

    public function math()
    {
        return View::show('math.html',[]);
    }

    public function redis()
    {
//        REDIS_HOST=172.16.2.30
//REDIS_PASSWORD=s3w8eUHC2A7gjtC4yMabHAqtBvrlRm
//REDIS_DATABASE=4
//REDIS_DATABASE_SESSION=1
//REDIS_DATABASE_CACHE=2

        //配置连接的IP、端口、以及相应的数据库
        $server  =  array (
            'host'      =>  '172.16.2.30' ,
            'port'      =>  6379 ,
            'database'  =>  15
        ) ;
        $server2 = [
            'scheme' => 'tcp',
            'host'   => '172.16.2.30',
            'port'   => 6379,
            'database' => 4,
            'password'=>'s3w8eUHC2A7gjtC4yMabHAqtBvrlRm'
        ];
        $client  =  new \Predis\Client($server2);
        $client->set('foo', 'bar');
        $value = $client->get('foo');
        var_dump($value);
    }



    static public function input($index,$default=''){
        if(isset($_REQUEST[$index]) && ($_REQUEST[$index] !== '')){
            return $_REQUEST[$index];
        }else{
            return $default;
        }
    }
}