<?php

namespace App\Adapter\Connection;

class SimplePDO extends \PDO {
  public static function exceptionHandler(\Exception $exception) {
    die("Uncaught exception: {$exception->getMessage()}");
  }

  public function __construct($host, $db, $user, $pass) {
    set_exception_handler(array(__CLASS__, 'exceptionHandler'));

    try {
      parent::__construct("mysql:host={$host};dbname={$db}", $user, $pass);

      $this->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
      $this->exec("set names utf8");
    } catch (\PDOException $e) {
      die("Error!: {$e->getMessage()}");
    }

    restore_exception_handler();
  }
}
