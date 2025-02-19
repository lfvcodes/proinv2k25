
<?php

#CODE_BY_LUIZ

#CLASE MANEJADORA DE CONEXION A BD CON PDO::
#if(!isset($_SESSION))session_start();

class cls_tasa{

private $bd;
private $query;

public function __construct(){
    include_once '../util/cls_connection.php';
    $this->bd = new Cls_connection;
    $this->query = array(
        'INSERT_TASA' => 'INSERT INTO pro_4tasa (tasa,log_user) VALUES (?,?)',
        'SELECT_TASA' => 'SELECT tasa FROM pro_4tasa WHERE id_act = ? LIMIT 1',
        'LIST_TASA' => 'SELECT id_act AS id,tasa,fecha_tasa AS fecha, log_user AS log FROM pro_4tasa ORDER BY DATE(fecha_tasa) DESC',
        'UPDATE_TASA' => 'UPDATE pro_4tasa SET tasa = ? WHERE id_act = ?',
        'DELETE_TASA' => 'DELETE FROM pro_4tasa WHERE id_act = ?',
    );
}


public function setNewTasa($params){
    if($this->bd->prepare($this->query['INSERT_TASA'],$params)):
        return true;
    else: return null;
    endif;
}

public function updateTasa($params){
    if($this->bd->prepare($this->query['UPDATE_TASA'],$params)):
        return true;
    else: return null;
    endif;
}

public function getDataTasa($id){
    $rs = $this->bd->prepare($this->query['SELECT_TASA'],array($id));
    return ($rs->rowCount() > 0) ? $rs->fetch() : false;
}

public function deleteTasa($params){
    #VERIFICAR SI TIENE MOVIMIENTOS ASOCIADOS ESTE CONCEPTO PRIMERO
    if($this->bd->prepare($this->query['DELETE_TASA'],$params) ):
        return true;
    else: return null;
    endif;
}

public function getLastTasa(){
    $rs = $this->bd->prepareRS('SELECT tasa FROM pro_4tasa WHERE ? ORDER BY fecha_tasa DESC LIMIT 1',array(1));
    return $rs['tasa'];
}

public function getListTasa(){
    $rs = $this->bd->consultar($this->query['LIST_TASA']);
    return ($rs->rowCount() > 0) ? json_encode($rs->fetchAll(),true) : false;
}

}//#END_CLASS

?>

