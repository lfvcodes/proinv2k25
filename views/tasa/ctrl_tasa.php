<?php 
session_start();

if(isset($_POST) && !empty($_POST) && !empty($_POST['action'])){
  require_once 'cls_tasa.php';
  require_once '../util/misc.php';
  $tasa = new Cls_tasa;
  $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

  if($post['action'] === 'addTasa'){
      
    if($tasa->setNewTasa(array($post['tasa'],$_SESSION['pro']['usr']['user'])) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Agregar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Tasa Actualizada Correctamente!');";
      $_SESSION['pro']['tasa'] = $post['tasa'];
      setBitacora('ACTUALIZAR TASA','AGREGAR REGISTRO: '.$post['tasa'],array($post['tasa']),$_SESSION['pro']['usr']['user']);
    }
    header("Location: actualizar");
    
  }

  if($post['action'] === 'removeTasa'){
    if($tasa->deleteTasa(array($post['cod'])) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Error al intentar Borrar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Tasa Borrada Correctamente!');";
      setBitacora('ACTUALIZAR TASA','BORRAR REGISTRO: '.$post['cod'],array($post['cod']),$_SESSION['pro']['usr']['user']);
    }
    header("Location: actualizar");
  }
  
  if($post['action'] === 'updateTasa'){
    $params = array($post['tasa'],$post['cod']);

    if($tasa->updateTasa($params) !== true ){
      $_SESSION['pro_alert'] = "alert('warning','Hubo un error al intentar Modificar');";
    }else{
      $_SESSION['pro_alert'] = "alert('success','Tasa Modificada Correctamente!');";
      setBitacora('ACTUALIZAR TASA','MODIFICAR REGISTRO '.$post['cod'],$params,$_SESSION['pro']['usr']['user']);
    }

    header("Location: actualizar");
  }
  if($post['action'] === 'getListTasa'){
    echo $tasa->getListTasa();
    exit;
  }
}
?>