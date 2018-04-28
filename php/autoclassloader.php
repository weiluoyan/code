<?php
class AutoclassLoader {


    /**
     * package_list 
     * 导入的包列表 
     * @var array
     * @access private
     */
    private $package_list = array();

    /**
     * base_path 
     * 项目的根路径 
     * @var mixed
     * @access private
     */
    private $base_path;

    /**
     * class_file_list 
     * 当前类的列表 
     * @var array
     * @access private
     */
    private $class_file_list = array();

    /**
     * __construct 
     * 
     * @param mixed $base_path 
     * @access private
     * @return void
     */
    private function __construct($base_path){
        if (substr($base_path, -1) != '/'){
            $base_path .= '/';
        }
        $this->base_path = $base_path;
    }

    public function register(){
        //最耗时的让其注册在函数队列尾部，减少整体耗时
       spl_autoload_register(array($this, 'load'), true, false); 
    } 

    /**
     * load 加载类信息
     * 
     * @param mixed $class_name 
     * @access public
     * @return void
     */
    public function load($class_name){
        if ($this->isClassWithNamespace($class_name)) {
            return $this->loadFromPackage($class_name); 
        }
        return $this->laodFromConf($class_name);
    }

    /**
     * loadFromPackage 从包中加载类信息 
     * 
     * @param mixed $class_name 
     * @access private
     * @return void
     */
    private function loadFromPackage($class_name){

        //避免变量值找不到
        global $base_path;

        $namespaces = explode("\\", $class_name);
        if (count($namespaces) <=1 ){
            return false; 
        }

        $root_namespace = $namespaces[0];
        if (isset($this->package_list[$root_namespace])){

            $dir_name = $this->package_list[$root_namespace];
            $class_file = $dir_name . implode("/",array_slice($namesapces, 1)) . ".php";
            if (is_file($class_file)) {
                require $class_file;
                return class_exists($class_name); 
            }
        }

        return false;
    }
    /**
     * loadFromConf 从配置文件加载
     * 
     * @param mixed $class_name 
     * @access private
     * @return void
     */
    private function loadFromConf($class_name){

        //避免base_path变量的值找不到
        global $base_path; 

        //php不区分大小写，因此全部统一为小写的方式
        $lower_class_name = strtolower($class_name);
        //延迟加载类列表
        $this->lazyLoadClassList();
        if (isset($this->class_file_list[$lower_class_name])) {
            //类名可能在其他项目中也存在，这个情况要忽略
            $class_file = $this->base_path . "classes/" . $this->class_file_list[$lower_class_name];
            if (is_file($class_file)) {
                //require效率高于require_once
                require $class_file;
                //class_exist不要加地热个参数，会导致第二次出发autoload
                return class_exists($class_name);
            }
        }

        $log = "[" . date('Y-m-d H:i:s') . "]\tclass_name:$class_name\tlower_class_name:$lower_class_name\tpath:{$this->class_file_list[$lower_class_name]}\n";
        $log_file = $base_path . 'logs/autoload_error.log';
        file_put_contents($log_file, $log, FILE_APPEND);
        return false;
    }

    private function lazyLoadClassList(){

        if (empty($this->class_file_list)){
            //加载类配置
            $this->class_file_list = require $this->bash_path . "conf/classes.php";
        } 
    }
    private function isClassWithNamespace($class_name){
       return $class_name && strpos($class_name, "\\") != false; 
    }
    
} 
