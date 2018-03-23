<?php
/**
 *
 */
class StoryController
{

  function __construct()
  {
    # code...
  }

  public function title(){
    $requestMethod = $_SERVER['REQUEST_METHOD'];
    $status = false;
    $response = array();
    $data = array();
    $error = '';

    if($requestMethod === 'POST'){
      $request_body = file_get_contents('php://input');
      $reqData = json_decode($request_body, true);
      $insert = DB_Insert(array(
        'Table' => 'stories',
        'Fields'=> array(
          'story_title' => $reqData['title']
        )
      ));
      if($insert) {
        $data['story_id'] = $insert;
        $data['story_title'] = $reqData['title'];
        $status = true;
      }
    } else if($requestMethod === 'GET'){

    }
    return array('status' => $status, 'data' => $data, 'error' => $error);
  }
}

?>
