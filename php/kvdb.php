<?php

namespace Chaos\Cache\KvStorage;

use Chaos\Cache\ICache;
use Chaos\Database\IConnector;
use Chaos\Database\Commander;
use Chaos\Database\DataType;
use Chaos\Database\ServerType;


final class KvDb implements ICache{

    /**
     * commander 数据库命令对象
     *  
     * @var mixed
     * @access private
     */
    private $commander = null;

    /**
     * cluster 集群表id
     * 
     * @var float
     * @access private
     */
    private $cluster = 1;

    /**
     * table_name  数据库表名
     *  
     * @var mixed
     * @access private
     */
    private $table_name;

    public function __construct(IConnector $connector, $cluster = 1){
        $this->cluster = intval($cluster);
        $this->table_name = sprintf('KvDb%d', $this->cluster);

        $this->commander = new Commander($connector);
    }

    /**
     * exists 判断key是否存在 
     * 
     * @param mixed $key 
     * @access public
     * @return void
     */
    public function exists($key){
        if (empty($key)) {
            return false; 
        }
        $ret = $this->commander->getRowByStmt(
            "select v from {$this->table_name} where k = ? limit 1",
            array(DataType::VarChar),
            array($key),
            ServerType::SLAVE
        );
        return $ret ? true : false; 
    }
    /**
     * get 获取键所对应的内容 
     * 
     * @param mixed $key 
     * @access public
     * @return void
     */
    public function get($key){
        if (empty($key)){
            return false;
        }
        $ret = $this->commander->getRowByStmt(
            "select v from {$this->table_name} where k= ? limit 1",
            array(DataType::VarChar),
            array($key),
            ServerType::SLAVE
        );

        return $ret ? unserialize($ret['v']) : false;
    }

    /**
     * set 在当前键中写入内容 键不存在时写入新键 存在时更新内容
     * 
     * @param mixed $key 键
     * @param mixed $val 要写入的内容（可以是各种类型 但不能是false) 
     * @param mixed $expire  
     * @access public
     * @return bool 是否写入成功 
     */
    public function set($key, $val, $expire = null){
        if (empty($key) || $val === false){
            return false; 
        }

        $ret = $this->commander->executeByStmt(
         array(DataType::VarChar, DataType::VarChar),
         array($key, serialize($val)),
         ServerType::MASTER 
     );

        return $ret ? true : false;
    }

    /**
     * increment 对当前健所对应的值进行自增 
     * 
     * @param mixed $key 键
     * @param int $val 自增的数量
     * @access public
     * @return void 成功返回更新后的结果 失败返回false
     */
    public function  increment($key, $val = 1){
        if (empty($key) || empty($val)){
           return false; 
        }

        $val = intval($val);
        //开启事务
        $this->commander->begin();
        $row = $this->commander->getRowByStmt(
            "select v from {$this->table_name} where k = ? for update",
            array(DataType::VarChar),
            array($key),
            ServerType::TRANSACTION
        );

        if (empty($row)){
            $this->commander->rollback();
            return false;
        }

        $v = max(0, intval(unserialize($row['v'])));
        $new_val = max(0, $v + $val);

        $ret = $this->commander->executeByStmt(

            "update {$this->table_name} set v = ? where k = ?",
            array(DataType::VarChar, DataType::VarChar),
            array(serialize($new_val), $key),
            ServerType::TRANSACTION
        );

        if ($ret) {
            $this->commander->commit(); 
        } else {
            $this->commander->rollback(); 
        }
        return $ret ? $new_val : false;
    }    


}
