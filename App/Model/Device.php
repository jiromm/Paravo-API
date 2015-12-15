<?php

namespace App\Model;

use App\Library\Constant;
use App\Model;

class Device extends Model {
  protected $table = Constant::DB_DEVICE;

  public function fetchById($deviceId) {
    $connection = $this->getConnection();

    $stmt = $connection->prepare("select * from {$this->getTable()} where status = 1 and id = :id");
    $stmt->execute([':id' => $deviceId]);

    return $stmt->fetch(\PDO::FETCH_ASSOC);
  }

  public function fetchAll() {
    $connection = $this->getConnection();

    $stmt = $connection->prepare("select * from {$this->getTable()} where status = 1");
    $stmt->execute();

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function updateConfigById($deviceId, $config) {
    $connection = $this->getConnection();

    $stmt = $connection->prepare("
      update {$this->getTable()} set
        config = :config
      where id = :id
    ");
    $stmt->execute([
      ':id' => $deviceId,
      ':config' => $config,
    ]);
  }
}
