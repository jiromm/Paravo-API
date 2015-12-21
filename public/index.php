<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

use App\Config;
use App\Model\Device;
use App\Adapter\Connection;
use App\View\JsonModel;
use App\View\ViewModel;

require '../vendor/autoload.php';

chdir(dirname(__DIR__));

$app = new \Slim\App();
$container = $app->getContainer();
$container['renderer'] = new ViewModel("./templates");


$config = new Config();
$connection = new Connection();
$device = new Device($connection($config));

$app->get('/', function (Request $request, Response $response) use ($app) {
    return $this->renderer->render($response, "/layout.html");
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

    $deviceData['ports'] = json_decode($deviceData['ports'], true);

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

        $ports = json_decode($deviceData['ports'], true);
        $ports[$data['port']]['status'] = $data['status'];
        $deviceData['ports'] = $ports;

        $result = [
          'status' => 'success',
          'data' => $deviceData,
        ];

        $device->updateConfigById(
          $request->getAttribute('deviceId'),
          json_encode($ports)
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
