<?php

namespace App;

use App\Adapter\ConnectionInterface;

class Model {
  protected $driver;
  protected $table;

  public function __construct(\PDO $driver) {
    $this->driver = $driver;
  }

  public function getTable() {
    return $this->table;
  }

  public function getConnection() {
    return $this->driver;
  }
}
