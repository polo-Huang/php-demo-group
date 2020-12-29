<?php
/*
 * @Author: polo
 * @Date: 2020-12-29 11:23:39
 * @LastEditTime: 2020-12-29 11:23:59
 * @LastEditors: Please set LastEditors
 * @Description: 数据库操作类
 * @FilePath: \php-demo-group\utils\database\db.php
 */


if( !defined('CORE') ) exit('Request Error!');

class db
{
    private static $conn;

    private static function connect()
    {
        $db_config = $GLOBALS['config']['db'];
        $dsn = "{$db_config['type']}:host={$db_config['host']};dbname={$db_config['name']};charset={$db_config['charset']}";
        $user = $db_config['user'];
        $pass = $db_config['pass'];
        try {
            self::$conn = new PDO($dsn, $user, $pass);
            self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        catch(PDOException $e)
        {
            echo "链接失败：".$e->getMessage()."\n";
            die();
        }
    }
    
    private static function close()
    {
        self::$conn = null;
    }

    // 查询语句
    public static function select($sql, $params = [])
    {
        // 打开链接
        self::connect();

        // if (substr_count($sql,'?') != count($params)) {

        // }
        $stmt = self::$conn->prepare($sql);
        foreach ($params as $key => $param) {
            if (is_numeric($key)) {
                $stmt->bindValue($key+1, $param);
            } else
                $stmt->bindParam(":{$key}", $param);
        }
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC); 
        $result = $stmt->fetchAll();

        // 关闭链接
        self::close();
        return $result;
    }

    // 插入语句
    public static function insert($sql, $params = [])
    {
        // 打开链接
        self::connect();

        // if (substr_count($sql,'?') != count($params)) {

        // }
        $stmt = self::$conn->prepare($sql);
        foreach ($params as $key => $param) {
            if (is_numeric($key)) {
                $stmt->bindValue($key+1, $param);
            } else
                $stmt->bindParam(":{$key}", $param);
        }
        $stmt->execute();
        $insert_id = self::$conn->lastInsertId();

        $result = [
            'insert_id' => $insert_id
        ];
        // 关闭链接
        self::close();
        return $result;
    }

    // 更新语句
    public static function update($sql, $params = [])
    {
        // 打开链接
        self::connect();

        // if (substr_count($sql,'?') != count($params)) {

        // }
        $stmt = self::$conn->prepare($sql);
        foreach ($params as $key => $param) {
            if (is_numeric($key)) {
                $stmt->bindValue($key+1, $param);
            } else
                $stmt->bindParam(":{$key}", $param);
        }
        $stmt->execute();
        $row_count = $stmt->rowCount();

        $result = [
            'row_count' => $row_count
        ];
        // 关闭链接
        self::close();
        return $result;
    }
}

