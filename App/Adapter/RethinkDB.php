<?php

namespace App\Adapter;

class RethinkDB {
    public function __construct() {
        $this->conn = \r\connect('localhost', 28015, 'paravo', 'superSecure');
    }
}
