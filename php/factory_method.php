<?php

class Button{}
class WinButton extends Button{}
class MacButton extends Button{}

interface ButtonFactory{
    public function createButton($type);
}

class MyButtonFactory implements ButtonFactory{
    //实现工厂方法
    public function createButton($type){
        switch($type){
        case 'Mac':
            return new MacButton();
        case 'Win':
            return new WinButton();
        }
    
    }

}

$button_obj = new MyButtonFactory();
$result_mac = $button_obj->createButton('Mac');
$reuslt_win = $button_obj->createButton('Win');

//简单工厂模式
class Operation
{
    protected $parameter_one = 0;
    protected $parameter_tow = 0;

    public function setParameterOne($parameter_one)
    {
        $this->parameter_one = $parameter_one;
    }
    public function setParameterTwo($parameter_two)
    {
       $this->parameter_two = $parameter_two; 
    }
    public function getResult()
    {
        $result = 0;
        return $result;
    }
}
class OperationAdd extends Operation
{
    public function getResult()
    {
        return $this->parameter_one + $this->parameter_two;
    }
}
class OperationMul extends Operation
{
    public function getResult()
    {
       return $this->parameter_one * $this->parameter_two; 
    }
}
class OperationDiv extends Operation
{
    public function getResult()
    {
        return $this->parameter_one/$this->parameter_two; 
    }
}

class OperationFactory
{
    public static function createOperation($operation)
    {
        switch($operation){
        case '+':
            $oper = new OperationAdd();
            break;
        case '-':
            $oper = new OperationSub();
            break;
        case '/':
            $oper = new OperationDiv();
            break;
        case '*':
            $oper = new OperationMul();
            break;
        }
        return $oper;
    }
}


//客户端代码
$operation = OperationFactory::createOperation('+');
$operation->setParameterOne(1);
$operation->setParameterTwo(2);
echo $operation->getResult();


/**
//工厂方法模式
interface IFactory{

    public function CreateOperation();
}

class AddFactory implements IFactory{

    public function CreateOperation(){

       return new OperationAdd(); 
    }
}
class SubFactory implements IFactory{

    public function CreateOperation(){

       return new OperationSub(); 
    }
}
class MulFactory implements IFactory{

    public function CreateOperation(){

       return new OperationMul(); 
    }
}
class DivFactory implements IFactory{

    public function CreateOperation(){

       return new OperationDiv(); 
    }
}

//客户端代码
$operationFactory = new AddFactory();
$operation = $operationFactory->CreateOperation();
$operation->setParameterA(10);
$operation->setParameterB(10);
echo $operation->getResult();
**/

//抽象工厂模式，提供了一个创建一系列相关或互相依赖对象的接口，而无需指定他们的具体类
//工厂方法模式是定义类一个用于创建对象的接口，让子类决定实例化哪一个类，抽象工厂模式的好处便是易于交换产品系列，由于具体工厂类在一个应用总只需要在初始化的时候出现一次，这就使得改变一个应用的具体工厂变得非常容易，它只是需要改变具体工厂即可使用不同的产品配置它让具体的创建实例过程与客户端分离，客户端是通过他们的抽象接口操作实例，产品的具体类名也被具体工厂的实现分离，不会出现在客户端代码中，
//


//数据库中的user表
class User{
    private $id = null;
    public function setId($id){
        $this->id = $id;
    }
    public function getId($id){
        return $this->id; 
    }

    private $name = null;
    public function setName($name){
        $this->name = $name; 
    }
    public function getName($name){
        return $this->name; 
    }
}
//数据库中的department表
class Department{
    private $id = null;
    public function setId($id){
       $this->id = $id; 
    }
    public function getId($id){
        return $this->id; 
    }

    private $name = null;
    public function setName($name){
        $this->name = $name; 
    }
    public function getName($name){
        return $this->name; 
    }
}

interface IUser
{
    public function insert(User $user);
    public function getUser($id);
}

//sqlserver数据库继承接口
class SqlserverUser implements IUser{

    public function insert(User $user){
        echo "往sql server中的user表添加一条记录";
    }
    public function getUser($id){
        echo "根据id获得sql server中的user表一条记录"; 
    }
}
//accessserver接口继承接口
class AcessUser implements IUser
{
    public function insert(User $user){

       echo "往acess server中的user表中添加一条记录";
    }
    public function getUser($id){
        echo "根据id得到acess server中user表一条记录"; 
    }

}
//简单工厂替换抽象工厂
class DataBase{
    const DB = 'sqlserver';
    public static function CreateUser(){

        $class = static::DB.'User'; 
        return new $class();
    }
    public static function CreateDepartment(){

        $class = static::DB.'Department'; 
        return new $class();
    }

}

interface IDepartment{

    public function insert(Department $user);
    public function getDepartment($id);
}

class SqlserverDepartment implements IDepartment{

    public function insert(Department $department){

       echo "sql server 中department表添加记录\n"; 
    }
    public function getDepartment($id){

       echo "根据id得到sql server中department表一条记录\n"; 
    }

}

class AcessDepartment implements IDepartment{
    public function insert(Department $department){

       echo "sql server 中department表添加记录\n"; 
    }
    public function getDepartment($id){

       echo "根据id得到sql server中department表一条记录\n"; 
    }
}

//客户端代码
$user = new User();
$iu = DataBase::CreateUser();
$iu->insert($user);
$iu->getUser(1);

$department = new Department();
$id = DataBase::CreateDepartment();
$id->insert($department);
$id->getDepartment(1);















