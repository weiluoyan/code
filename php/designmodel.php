<?php
//观察者模式
//负责处理用户登录的类
//比如以下就是一个很纯碎的登录类，但是如果业务部门想将登录的ip地址记录到日志中，又或者登录失败返回报警邮件，这些是非常容易满足需求的，但是它会破坏我们的设计，那login这个类就会紧紧的嵌入到系统中,这样不如不移除后来这些特殊要求的代码，要把这个类放到新的系统中会非常麻烦，当然这未必很难，但是我们会走上复制粘贴的开发之路，既然系统中有两个相似但又不同的login类，那么我们修改一个类后必须再修改另一个类，这样太麻烦类，那么接下来该怎么拯救这个类呢，观察者模式最适应不过了
class Login{

    const LOGIN_USER_UNKNOW = 1;
    const LOGIN_WRONG_PASS = 2;
    const LOGIN_ACCESS = 3;
    private $status = array();

    function handleLogin($user, $pass, $ip){
        switch (rand(1,3)) {
        case 1:
            $this->setStatus( self::LOGIN_ACCESS, $user, $ip );
            $ret = true;
            break; 
        case 2:
            $this->setStatus(self::LOGIN_WRONG_PASS, $user, $ip);
            $ret = false;
            break;
        case 3:
            $this->setStatus(self::LOGIN_USER_UNKNOWN, $user, $ip);
            $ret = false;
            break;
        }
        //特殊需求一 加ip日志
        Logger:logIP($user, $ip, $this->getStatus());

        //特殊需求二 登录失败发邮件
        if (!$ret) {
               Notifier::mailWarning($user, $ip, $this->getStatus()); 
        }
        return $ret;
    }

    private function setStatus($status, $user, $ip){
        $this->status = array($status, $user, $ip); 
    } 
    function getStatus(){
        return $this->status; 
    }
}


//实现,使用观察者模式优化上面的登录类
//观察者模式的核心就是把客户元素(观察者)从一个中心类中分离出来，当主题知道事件发生时，观察者需求被通知到，同时我们并不希望将主体和观察者之间的关系进行硬编码
//为了达到这个目的，我们允许观察者在主体上注册


//定义Obervable接口
interface Observable{

    function attach (Observer $observer);
    function detach (Observer $observer);
    function notify();

}
//重写Login类
class Login implements Observable{

    private $observers;

    function __construct(){
        $this->observers = array(); 
    }

    function attach(Observer $observer){
        $this->observers[] = $observer; 
    }
    function detach(Observer $observer){
        $newobservers = array();
        foreach($this->observers as $obs){
            if ($obs !== $observer){
                $newobservers[] = $obs; 
            }
        } 
        $this->observers = $newobservers;
    }
    function notify(){
        foreach ($this->observers as $obs){
            $obs->update($this); 
        } 
    }
    function handleLogin($user, $pass, $ip){
        switch (rand(1,3)) {
        case 1:
            $this->setStatus( self::LOGIN_ACCESS, $user, $ip );
            $ret = true;
            break; 
        case 2:
            $this->setStatus(self::LOGIN_WRONG_PASS, $user, $ip);
            $ret = false;
            break;
        case 3:
            $this->setStatus(self::LOGIN_USER_UNKNOWN, $user, $ip);
            $ret = false;
            break;
        }
        $this->notify(); 
        return $ret;
    }
    private function setStatus($status, $user, $ip){
        $this->status = array($status, $user, $ip); 
    } 
    function getStatus(){
        return $this->status; 
    }
}
//定义Observer接口
interface Observer{
     function update( Observable $observable);
}


abstract class LoginObserver implements Observer{

    private $login;
    function __construct(Login $login){
        $this->login = $login;
        $login->attach($this); 
    }
    function update( Observable $observable){
        if ($observable === $this->login){
            $this->doUpdate($observable); 
        }
    }
    abstract function doUpdate(Login $login);
}

//具体的发邮件的实例
class SecurityMonitor extends LoginObserver{
    function doUpdate(Login $login){
        $status = $login->getStatus();
        if ($status[0] == Login::LOGIN_WRONG_PASS){
              //发送邮件给系统管理员
             print __CLASS__." sending mail to sysadmin\n"; 
        }
    }
}
//具体的将数据记录到日志的实例
class GeneralLogger extends LoginObserver{
    function doUpdate(Login $login){
        $status = $login->getStatus();
        //记录登录数据到日志
        print __CLASS__." add login data to log\n"; 
    }

}

$login = new Login();
new SecurityMonitor($login);
new GeneralLogin($login);




/**
 * Observables 
 * 观察者模式
 * 先定义一个被观察者的接口，这个接口要实现注册观察者，删除观察者和通知的功能
 * @package 
 * @version $id$
 * @copyright 1997-2005 The PHP Group
 * @author Tobias Schlitt <toby@php.net> 
 */
interface Observables
{
    public function attach(observer $ob);//注册类
    public function detach(observer $ob);//删除类
    public function notify();//通知
}
//定义一个观察者的接口，这个接口要有一个在被通知的时候都要实现的方法
interface Observer
{
    public function doActor(Observables $obv);
}

//被观察者继承被观察者的接口
class Saler implements Observables
{
    protected $obs = array();//把观察者保存在这里
    protected $range = 0;

    public function attach(Observer $ob)
    {
        $this->obs[] = $ob; 
    }
    public function detach(Observer $ob)
    {
        foreach($this->obs as $o)
        {
            if ($o != $ob) 
               $this->obs[] = $o; 
        }
    }
    public function notify()
    {
        foreach($this->obs as $o)
        {
            $o->doActor($this); 
        } 
    }
    public function increPrice($range)
    {
        $this->range = $range; 
    }
    public function getAddRange()
    {
        return $this->range; 
    }
}
//定义一个买家
abstract class Buyer implements Observer{}
class PoorBuyer extends Buyer
{
    public function doActor(observables $obv)
    {
        if ($obv->getAddRange() > 10)
            echo '不买了';
        else
            echo '还行，买一点吧';
    }
}
class RichBuyer extends Buyer
{
    public function doActor(observables $obv)
    {
        echo '爱涨涨'; 
    }
}
$saler = new Saler();//小贩（被观察者）
$saler->attach(new PoorBuyer());//注册一个低收入的消费者（观察者）
$saler->attach(new RichBuyer());//注册一个高收入的消费者（观察者）
$saler->increPrice(20);//涨价
$saler->notify();//通知

//观察者模式解除了主体和具体观察者的藕合，让藕合的双方都依赖于抽象，而不是依赖具体，从而使得各自的变化都不会影响另一边的变化

//在phpspl中已经提供了splsubject和splOberver接口
//实例
class Subject implements SplSubject{

    private $_observers = [];

    /**
     * attach 
     * 实现添加观察者方法 
     * @param SplObserver $observer 
     * @access public
     * @return void
     */
    public function attach(SplObserver $observer)
    {
        if (!in_array($observer, $this->_observers)) {
            $this->_observers[] = $observer; 
        }
    }
    /**
     * detach 
     * 实现移除观察者方法 
     * @param SplObserver $observer 
     * @access public
     * @return void
     */
    public function detach(SplObserver $observer)
    {
        if (false !== ($index = array_search($observer, $this->_observers))){
            unset($this->_observers[$index]); 
        } 
    }

    public function notify()
    {
        foreach($this->_observers as $observer){
            $observer->update($this); 
        } 
    }
    public function setCount($count)
    {
       echo "数据量加" . $count;
    }
    public function setIntegral($integral)
    {
        echo "积分量加" . $integral;
    }

}

/**
 * Observer1 
 * 观察者一
 * @uses SplObserver
 * @package 
 */
class Observer1 implements SplObserver
{
    public function update(SplSubject $subject)
    {
        $subject->setCount(10); 
    }
}
/**
 * Observer2 
 * 观察者二
 * @uses SplObserver
 * @package 
 */

class Observer2 implements SplObserver
{
    public function update(SplSubject $subject)
    {
        $subject->setIntegral(10); 
    }
}
/**
 *客户端调用
 */
class Client
{
    public static function test()
    {
       $subject = new Subject();
       $observer1 = new Observer1();
       $observer2 = new Observer2();
       $subject->attach($observer1);
       $subject->attach($observer2);
       $subject->notify();
       $subject->detach($observer1); 
       $subject->notify();
    }
}

Client::test();
//单例模式
class Singleton{
    //存放实例
    private static $_instance = null;
    //私有化构造方法
    private function __construct(){
         echo "单例模式的实例被构造了"; 
    }
    private function __clone(){
        trigger_error('clone is not allow', E_USER_ERROR); 
    }
    //公有化获取实例方法
    public static function getInstance(){


        if (!isset(self::$_instance)){
            self::$_instance = new Singleton(); 
        }
        return self::$_instance;
    }
}
$singlenton = Singlenton::getInstance();


//解释器模式

abstract class Expression{

    private static $keycount = 0;
    private $key;
    abstract function interpret( InterpreterContext $context);
    function getKey(){
        if (!isset($this->key)){
            self::$keycount++;
            $this->key = self::$keycount; 
        }
       return $this->key; 
    }
}

class LiteralExpression extends Expression{

    private $value;

    function __construct($value){

       $this->value = $value; 
    }
    function interpret(INterpreterContext $context){

       $context->replace($this, $this->value); 
    }
}

class InterpreterContext{

    private $expressionstore = array();

    function replace(Expression $exp, $value){
        $this->expressionstore[$exp->getKey()] = $value;
    
    }
    function lookup(Expression $exp){
        return $this->expressionstore[$exp->getKey()]; 
    }
}

$context = new InterpreterContext();
$literal = new LiteralExpression('four');
$literal->interpret($context);
print $context->lookup($literal) . "\n";
