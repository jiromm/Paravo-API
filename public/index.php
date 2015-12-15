<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Config;
use App\Model\Device;
use App\Adapter\Connection;
use App\View\JsonModel;

require '../vendor/autoload.php';

chdir(dirname(__DIR__));

$app = new \Slim\App();
$config = new Config();
$connection = new Connection();
$device = new Device($connection($config));

$app->get('/', function (Request $request, Response $response) use ($app) {
  $result = [
    'status' => 'success',
    'message' => 'welcome',
  ];

  return new JsonModel($result);
});

$app->get('/devices', function (Request $request, Response $response) use ($app, $device) {
  $result = [
    'status' => 'error',
    'message' => 'Runtime Error',
  ];

  try {
    $result = [
      'status' => 'success',
      'data' => $result = $device->fetchAll(),
    ];
  } catch (\Exception $ex) {
    $result['message'] = $ex->getMessage();
  }

  return new JsonModel($result);
});

$app->get('/devices/{deviceId}', function (Request $request, Response $response) use ($device) {
  $result = [
    'status' => 'error',
    'message' => 'Runtime Error',
  ];

  try {
    $deviceData = $device->fetchById(
      $request->getAttribute('deviceId')
    );

    $deviceData['config'] = json_decode($deviceData['config'], true);

    $result = [
      'status' => 'success',
      'data' => $deviceData,
    ];
  } catch (\Exception $ex) {
    $result['message'] = $ex->getMessage();
  }

  return new JsonModel($result);
});

$app->put('/devices/{deviceId}', function (Request $request, Response $response) use ($device) {
  $result = [
    'status' => 'error',
    'message' => 'Runtime Error',
  ];

  try {
    $data = $request->getParsedBody();

    if (is_array($data)) {
      if (isset($data['port']) && isset($data['status'])) {
        $deviceData = $device->fetchById(
          $request->getAttribute('deviceId')
        );

        $config = json_decode($deviceData['config'], true);
        $config['ports'][$data['port']]['status'] = $data['status'];
        $deviceData['config'] = $config;

        $result = [
          'status' => 'success',
          'data' => $deviceData,
        ];

        $device->updateConfigById(
          $request->getAttribute('deviceId'),
          json_encode($config)
        );
      } else {
        $result['message'] = 'Bad Request';
      }
    } else {
      $result['message'] = 'Bad Request';
    }
  } catch (\Exception $ex) {
    $result['message'] = $ex->getMessage();
  }

  return new JsonModel($result);
});

$app->run();
