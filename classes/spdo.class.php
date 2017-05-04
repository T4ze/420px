<?php

require_once(dirname(__FILE__).'./../config/database.php');

class SPDO
{
    private $PDOInstance = null;
    private static $instance = null;

    private function __construct() {
        $this->PDOInstance = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);    
    }

    public static function getInstance() {  
        if (is_null(self::$instance)) {
            self::$instance = new SPDO();
        }
        return self::$instance;
    }

    public function prepare($query) {
        return $this->PDOInstance->prepare($query);
    }

    public function query($query) {
        return $this->PDOInstance->query($query);
    }

    public function lastInsertId() {
        return $this->PDOInstance->lastInsertId();
    }

    public function all($request, $a = []) {
        $p = $this->prepare($request);
        $q = $p->execute($a);
        return $p->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allFromInt($request, $v) {
        $p = $this->prepare($request);
        $p->bindParam(0, (int) $v, PDO::PARAM_INT);
        $q = $p->execute();
        return $p->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first($request, $a = []) {
        $p = $this->prepare($request);
        $q = $p->execute($a);
        return $p->fetch(PDO::FETCH_ASSOC);
    }

    public function rowCount($request, $a = []) {
        $p = $this->prepare($request);
        $q = $p->execute($a);
        return $p->rowCount();
    }
}