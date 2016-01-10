<?php

namespace App\Model;

use App\Library\Constant;
use App\Model;

class Device extends Model {
    protected $table = Constant::DB_DEVICE;

    public function fetchById($deviceId) {
        $driver = $this->getDriver();

        $result = \r\table("devices")->get($deviceId)->run($driver->conn);

        return $this->normalizeData($result);
    }

    public function fetchAll() {
        $driver = $this->getDriver();

        $result = \r\table("devices")->getAll('raspberry_pi_b_plus', [
            'index' => 'name',
        ])->run($driver->conn);

        return $this->normalizeData($result->toArray());
    }

    public function updateByPortId($deviceId, $portId, $status) {
        $driver = $this->getDriver();

        \r\table("devices")->get($deviceId)->update([
            'ports' => [
                $portId => ['status' => $status],
            ],
        ])->run($driver->conn);
    }
}
