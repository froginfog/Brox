<?php
namespace Core;
/**
 * Class DB 用这个类操作数据库必须配合try catch
 */
class DB {
    private $dbh = null;
    private static $instance = null;
    private $stmt;

    public static function getInstance(){
        if(is_null(self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone(){}

    private function __construct(){
        $dsn = DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
        try{
            $this->dbh = new \PDO($dsn, DB_USER, DB_PWD);
        }catch (\PDOException $e){
            die($e->getMessage());
        }
        $this->dbh->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        //$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
        $this->dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
        $this->exec('set names '.DB_CHARSET);
    }

    public function lastInsertId(){
        return $this->dbh->lastInsertId();
    }


    private function getErr(){
        $msg = $this->dbh->errorInfo()[2];
        if(!is_null($msg)){
            throw new \PDOException('database error:'.$msg);
        }
    }

    /**
     * select查询
     * @param string $sql
     * @return $this
     */
    public function query($sql){
        $this->stmt = $this->dbh->query($sql);
        $this->getErr();
        return $this;
    }

    public function getOne(){
        return $this->stmt->fetch();
    }

    public function getAll(){
        return $this->stmt->fetchAll();
    }

    /**
     * 除select之外其他操作
     * @param string $sql
     * @return string
     */
    public function exec($sql){
        $res = $this->dbh->exec($sql);
        $this->getErr();
        return $res;
    }

    /**
     * 准备sql语句，采用问号占位符的sql
     * select * from user where id=? and username=? and email=?
     * @param string $sql
     * @return $this
     */
    public function prepare($sql){
        $this->stmt = $this->dbh->prepare($sql);
        $this->getErr();
        return $this;
    }

    /**
     * 传入的数组形如：
     * array(
     *   array('value'=>1, 'type'=>PDO::PARAM_INT),
     *   array('value'=>'shit', 'type'=>PDO::PARAM_STR),
     *   array('value'=>'shit@qq.com', 'type'=>PDO::PARAM_STR)
     *  );
     * @param array $arr
     * @return $this
     */
    public function bindValue($arr){
        foreach ($arr as $k=>$v){
            $this->stmt->bindValue($k+1, $v['value'], $v['type']);
        }
        return $this;
    }

    public function execute(){
        $this->stmt->execute();
        $this->getErr();
        return $this;
    }

    public function rowCount(){
        return $this->stmt->rowCount();
    }

    public function beginTransaction(){
        $this->dbh->setAttribute(\PDO::ATTR_AUTOCOMMIT,false);
        return $this->dbh->beginTransaction();
    }

    public function commit(){
        return $this->dbh->commit();
    }

    public function rollBack(){
        return $this->dbh->rollBack();
    }

    /**
     * 如果事务结束后还要继续操作数据库请调用
     */
    public function endTransaction(){
        $this->dbh->setAttribute(\PDO::ATTR_AUTOCOMMIT, true);
        //$this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
    }
    public function getAttrivbute($attr){
        echo $this->dbh->getAttribute($attr);
    }

    public function count(){
        return $this->stmt->fetchColumn();
    }
}