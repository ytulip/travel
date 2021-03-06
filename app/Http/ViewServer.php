<?php
/**
 * 简单的路由类，单列模式
 * Class SampleRoute
 */
class ViewServer{
    //保存例实例在此属性中
    private static $_instance;
    private $_route;
    private $_twig;

    //构造函数声明为private,防止直接创建对象
    private function __construct()
    {
        $loader = new Twig_Loader_Filesystem(resource_path . '/views');

        $function1 = new Twig_SimpleFunction('versionfile', function ($val) {
            $res = pathinfo($val);
            $result = $val;
            switch($res['extension']){
                case 'css':
                    $result = '<link rel="stylesheet" type="text/css" href="'.$val.'?version='.env('version',1).'">';
                    break;
                case 'js':
                    $result = '<script src="'.$val.'?version='.env('version',1).'"></script>';
                    break;
                default:
                    break;
            }
            return $result;
        });


        $function = new Twig_SimpleFunction('xtemplate', function ($val,$id) {
            if(!file_exists(resource_path . '/views' . $val)){
                return '<script></script>';
            }

            return '<script type="text/x-template" id="'.$id.'">'.file_get_contents(resource_path . '/views' . $val).'</script>';
        });
        $this->_twig = new Twig_Environment($loader, array(
            'cache' => storage_path . '/views',
            'debug' => true,
        ));
        $this->_twig->addFunction($function);
        $this->_twig->addFunction($function1);
    }
    //单例方法
    public static function singleton()
    {
        if(!isset(self::$_instance))
        {
            $c=__CLASS__;
            self::$_instance=new ViewServer();
            self::$_instance->init();
        }
        return self::$_instance;
    }
    /**
     * 初始化函数
     */
    public function init(){
    }

    /**
     * get路由
     * @param $route
     * @param callable $fun
     */
    public function get($route,Closure $fun){
        if($route == $this->_route){
            $fun();
            exit;
        }
        return;
    }

    public function show($tmpPath,$data){
        echo $this->_twig->render($tmpPath, $data);
        exit;
    }

    //阻止用户复制对象实例
    public function __clone()
    {
        trigger_error('Clone is not allow' ,E_USER_ERROR);
    }

    public function exec(){
        include_once app_path . '/routes.php';
    }
}