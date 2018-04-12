<?php
namespace Database;
use ReflectionMethod;

class Database
{
    protected $adapter;

    public function __construct()
    {
    
    }

    public function test(MysqlAdapter $adapter)
    {
       $adapter->test(); 
    }

}

class MysqlAdapter
{

    public function test()
    {
        echo "i am MysqlAdapter test";
    }

}

class app
{
    public static function run($instance, $method)
    {
        if (!method_exists($instance, $method))
            return null; 
       
        $reflector = new ReflectionMethod($instance, $method);

        $parameters = [];

        foreach($reflector->getParameters() as $key => $parameter){

            $class = $parameter->getClass();

            if ($class) {
                array_splice($parameters, $key, 0, [new $class->name()]);
            }
        }
        call_user_func_array([$instance, $method], $parameters);
    }
}

app::run(new Database(), 'test');



die;



/*----------------------------------------------------------------------------------------------------------
依赖的自动注入：你只需要在需要的位置注入你需要的依赖即可，运行时容器将自动解析依赖（存在子依赖也可以自动解析）将对应的实例注入到你需要的位置
依赖的单例注入：某些情况下我们需要保持依赖的全局单例特性，比如web框架中的request依赖，我们需要将整个请求响应周期中的所有注入request依赖的位置同步为在路由阶段解析完请求体的request实例，这样我们在任何位置都可以访问全局的请求体对象
依赖的契约注入：比如我们依赖某storage，目前使用的filestorage来实现，后期发现性能瓶颈，要该用redisstorage来实现，如果代码总大量使用filestorage作为依赖注入，这时候就需要花费精力去改代码了，我们可以使用接口storage作为契约，将具体的实现累filestorage／redisstorage通过容器的绑定机制关联到storage上，依赖注入storage，后期切换搜索存储引擎只需要修改绑定即可
标量参数关联传值：依赖是自动解析注入的，剩余的标量参数则可以通过关联传值，这样比较灵活，没必要把默认值的参数放在函数参数最尾部
public static methods:
   singleton//单例服务绑定
   bind //服务绑定
   run //运行容器
private static methods:
   getParam //获取依赖参数
   getInstance //获取依赖实例

 */

class IOCContainer{

    /**
     * 注册到容器内的依赖--服务 
     */
    public static $dependencyServices = array();

    /**
     * singleton 
     * 单例模式服务注册 
     * @param mixed $service 
     * @param mixed $provider 
     * @static
     * @access public
     * @return void
     */
    public static function singleton($service, $provider)
    {
        static::bind($service, $provider, true); 
    }

    /**
     * build 
     * laravel中的反射 
     * @param mixed $concrete 
     * @param array $parameters 
     * @static
     * @access public
     * @return void
     */
    public static function build($concrete, array $parameters = [])
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }

        $reflector = new ReflectionClass($concrete);
        if (!$reflector->isInstantiable()){
            if (!empty($this->buildStack)){
                $previous = implode(',' ,$this->buildStack); 

                $message = "Target [$conncrete] is not instantiable while building [$previous].";
            }else {
                $message = "Target [$conncrete] is not instantiable."; 
            }
            throw new BindingResolutionException($message);
        }
        $this->buildStack[] = $concrete;
        $constructor = $reflector->getConstructor();

        if (is_null($constructor)){
            array_pop($this->buildStack); 
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $parameters = $this->keyParametersByArgument(
                $dependencies, $parameters 
            );
        $instances = $this->getDependencies(
               $dependencies, $parameters 
           );

        array_pop($this->builddStack);
        return $reflector->newInstanceArgs($instances);

    }

}







