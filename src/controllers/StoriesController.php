<?php
/**
 *
 */
class StoriesController
{

  function __construct()
  {
    # code...
  }

  public function storylist(){
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $status = false;
    $response = array();
    $data = array();
    $error = '';

    if($requestMethod === 'GET'){
      $readStories = DB_Read(array(
        'Table' => 'stories',
        'Fields'=> '*',
      ),'ASSOC','');
      if($readStories) {
        $data['stories'] = $readStories;
        $status = true;
      }
    }
    return array('status' => $status, 'data' => $data, 'error' => $error);
  }
}

?>
