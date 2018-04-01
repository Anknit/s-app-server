<?php
require_once __DIR__.'/require.php';

$version = $_REQUEST['version'];
$controller = $_REQUEST['controller'];
$method = $_REQUEST['method'];

if($version == 'v1') {
  $controller = getController($controller);
  if($controller['status']) {
    $response = callMethod($controller['name'], $method);
    echo json_encode($response);
  } else {
    http_response_code(404);
  }
}

function getController($controller) {
  $status = false;
  $name = '';
  switch ($controller) {
    case 'story':
    $status = true;
    $name = 'StoryController';
    break;
    case 'stories':
    $status = true;
    $name = 'StoriesController';
    break;
    default:
    break;
  }
  return array('status'=>$status, 'name'=>$name);
}

function callMethod($controller, $method) {
  require_once __DIR__.'/controllers/'.$controller.'.php';
  if(method_exists($controller, $method)){
    $scope = new $controller();
    $response = $scope->$method();
    return $response;
  } else {
    http_response_code(404);
  }

}
?>
