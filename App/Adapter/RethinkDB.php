<?php

namespace App\Adapter;

class RethinkDB {
    public function __construct() {
        $this->conn = \r\connect('localhost');

        $this->conn->useDb('paravo');
    }
}
