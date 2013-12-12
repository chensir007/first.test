<?php
/**
 * Created by PhpStorm.
 * User: chensir
 * Date: 13-11-19
 * Time: 下午4:13
 */
if(!defined("WEB_ROOT")){
    define("WEB_ROOT", "f:\\phpwork\\test\\");
}
require_once(WEB_ROOT.'Util/LogUtil.php');

class Mysql {
    private $host;
    private $port;
    private $dbname;
    private $user;
    private $pwd;
    private $conn;

    /**
     * construct Mysql
     * @param $host
     * @param $port
     * @param $user
     * @param $pwd
     * @param $dbname
     */
    function __construct($host,$port,$user,$pwd,$dbname){
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->pwd = $pwd;
        $this->dbname = $dbname;
        $this->connect();
    }

    /**
     * get connection
     */
    function connect(){
        try{
            $this->conn = mysql_connect($this->host.':'.$this->port,$this->user,$this->pwd) or die("DB Connnection Error !".mysql_error());
            mysql_select_db($this->dbname,$this->conn) or die('connect db fail !'.mysql_error());
            mysql_query("set names utf8");
        }catch (Exception $e){
            LogUtil::GetLog()->error($e->getMessage());
        }
    }

    /**
     * close conncetion
     */
    function dbClose(){
        mysql_close($this->conn) or die('close db fail !'.mysql_error());
    }

    /**
     * select,update,insert,delete
     * @param $sql
     * @return resource
     */
    function query($sql){
        $ret = mysql_query($sql) or die('query fail !'.mysql_error());
        return $ret;
    }

    /**
     * sql result
     * @param $result
     * @return array
     */
    function myArray($result){
        return mysql_fetch_array($result);
    }

    /**
     * free result
     * @param $result
     */
    function myArrayFee($result){
        mysql_free_result($result) or die('fee_result fail !'.mysql_error());
    }

    /**
     * query count(*)
     * @param $result
     * @return int
     */
    function rows($result){
        if(empty($result)){
            $ret = 0;
        }else{
            $ret = mysql_num_rows($result);
        }
        return $ret;
    }

    /**
     * select *
     * @param $tableName
     * @param $condition
     * @return resource
     */
    function select($tableName,$condition){
        return $this->query("SELECT * FROM $tableName $condition");
    }

    /**
     * select fields
     * @param $tableName
     * @param $fileds
     * @param $condition
     * @return resource
     */
    function selectf($tableName,$fileds,$condition){
        return $this->query("SELECT $fileds FROM $tableName $condition");
    }

    /**
     * insert table
     * @param $tableName
     * @param $fields
     * @param $value
     * @return int
     */
    function insert($tableName,$fields,$value){
        $this->query("INSERT INTO $tableName $fields VALUES$value");
        return mysql_insert_id();
    }

    /**
     * @param $tableName
     * @param $change
     * @param $condition
     * @return int
     */
    function update($tableName,$change,$condition){
        $this->query("UPDATE $tableName SET $change $condition");
        return mysql_affected_rows();
    }

    /**
     * delete
     * @param $tableName
     * @param $condition
     */
    function delete($tableName,$condition){
        $this->query("DELETE FROM $tableName $condition");
        return mysql_affected_rows();
    }
}


