<?php

/**
 * Created by PhpStorm.
 * User: dj
 * Date: 2016/9/4
 * Time: 12:48
 */
class SQLTool
{
    private $conn;
    private $host="localhost";
    private $user="root";
    private $password="";
    private $db="bookreader";

    function SQLTool()
    {
        $this->conn=mysqli_connect($this->host,$this->user,$this->password);
        if(!$this->conn)
        {
            die("fail".mysqli_error());
        }
        mysqli_select_db($this->conn, $this->db);
        mysqli_query($this->conn, "set names utf8");
    }
    //dql 针对select
    public function execute_dql($sql)
    {
        $sql = mysqli_real_escape_string($this->conn, $sql);
        $res=mysqli_query($this->conn, $sql)or die(mysqli_error($this->conn));
        return $res;
    }

    public function insert($dataArray, $tableName){

        $keys = "";$values = "";

        foreach ($dataArray as $k => $v) {
            $keys = $keys.$k.',';
            //如果是字符型数据需要先转义处理一下
            if(is_string($v)) {
                $value = mysqli_real_escape_string($this->conn, $v);
                $values = $values."\"".$value."\",";
            }
            else{
                $values = $values.$v.",";
            }
        }

        $keys = substr($keys, 0, strlen($keys)-1);//移除最后一个逗号
        $values = substr($values, 0, strlen($values)-1);//移除最后一个逗号

        $sql = sprintf("insert into %s(%s) VALUES (%s)", $tableName, $keys, $values);

        //print_r($sql);

        $b=mysqli_query($this->conn, $sql);
        if(!$b)
        {
            //echo print_r("insert error :%s",mysqli_error($this->conn));
            echo mysqli_error($this->conn);
            echo "<br/>";
            return 0;
        }else {
            if(mysqli_affected_rows($this->conn)>0)
            {
                return 1;
            }else{
                //echo print_r("not inserted :%s",mysqli_error($this->conn));
                echo mysqli_error($this->conn);
                echo "<br/>";
                return 2;
            }
        }
    }

    // dml语句是针对update delete insert 命令，返回值为true false
    public function execute_dml($sql)
    {
        echo $sql;
        $sql = mysqli_real_escape_string($this->conn, $sql);
        $b=mysqli_query($this->conn, $sql);
        if(!$b)
        {
            return 0;
        }else {
            if(mysqli_affected_rows($this->conn)>0)
            {
                return 1;
            }else{
                return 2;
            }
        }
    }
}