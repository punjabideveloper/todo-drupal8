<?php

namespace Drupal\todo\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {
    
    $myText = 'This is not just a default text!1111';
    $myNumber = 1;
    $myArray[] = [1, 2, 3];
    $tasklist = [];

    //$sql = "SELECT * FROM {task} WHERE vid = :vid";
    $sql = "SELECT * FROM {task}";
    //$result = db_query($sql, array(':vid' => $vid));
    $result = db_query($sql);
    if ($result) {
      while ($row = $result->fetchAssoc()) {
        $tasklist[] = $row;
      }
    }
    return array(
      '#theme' => 'todo_theme_hook',      
      '#variable2' => $myNumber,
      '#tasklist' => $tasklist,
    );
  }

  public function tasklist() {
    
    $tasklist = [];
    $sql = "SELECT * FROM {task}";
    $result = db_query($sql);
    if ($result) {
      while ($row = $result->fetchAssoc()) {
        $tasklist[] = $row;
      }
    }    
    return new JsonResponse( $tasklist );
    exit;
  }

  public function taskremove(Request $request) {
    
    $id = $request->request->get('id');
    $sql = "DELETE FROM {task} WHERE id=".$id;
    db_query($sql);    
    return new JsonResponse( array('success' => true,'id' => $id ));
    exit;
  }

  public function taskadd(Request $request) {
    
    $name = trim($request->request->get('name'));
    $sql = "INSERT INTO task (name,status) values ('$name',0)";
    db_query($sql);    
    $tasklist = [];
    $sql = "SELECT * FROM {task}";
    $result = db_query($sql);
    if ($result) {
      while ($row = $result->fetchAssoc()) {
        $tasklist[] = $row;
      }
    }    
    return new JsonResponse( $tasklist );
    exit;
  }

  public function taskcomplete(Request $request) {
    $id = trim($request->request->get('id'));
    $status = trim($request->request->get('status'));
    $sql = "UPDATE task SET status=$status WHERE id=".$id;
    db_query($sql);    
    return new JsonResponse(array('id' => $id,'status' => $status));
    exit;
  }

  public function taskupdate(Request $request) {
    $id = trim($request->request->get('id'));
    $name = trim($request->request->get('name'));

    $sql = "UPDATE task SET name='$name' WHERE id=".$id;
    db_query($sql);    
    return new JsonResponse(array('id' => $id,'name' => $name));
    exit;
  }

  public function destroycompleted(Request $request) {
    $taskIds = substr(trim($request->request->get('taskIds')),0,-1);
    $action = trim($request->request->get('action'));
    if($action == 'removecompleted')
    {
      $sql = "DELETE from task  WHERE id IN (".$taskIds.")";
      db_query($sql);
      return new JsonResponse(array('taskIds' => $taskIds));
      exit;
    } 
    else
    {
      return new JsonResponse(array('msg' => 'There is an error'));
      exit;
    } 
    
  }  

  

  
  

}
