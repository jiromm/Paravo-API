<?php

namespace App;

use App\Adapter\RethinkDB;

class Model {
    protected $driver;
    protected $table;

    public function __construct(RethinkDB $driver) {
        $this->driver = $driver;
    }

    public function getTable() {
        return $this->table;
    }

    public function getDriver() {
        return $this->driver;
    }

    protected function normalizeData($data) {
        if (is_array($data) || $data instanceof \ArrayObject) {
            $data = json_encode($data);
            $data = json_decode($data, true);
        }

        return $data;
    }
}
