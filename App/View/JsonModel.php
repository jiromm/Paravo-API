<?php

namespace App\View;

class JsonModel {
  public function __construct(array $data) {
    header('Content-type: application/json');

    echo json_encode($data);
    exit;
  }
}
