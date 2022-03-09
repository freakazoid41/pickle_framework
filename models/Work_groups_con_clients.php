<?php
//this file is main parent for model files
namespace app\models;


require_once "Main.php";
use app\models\Main as Main; 



class Work_groups_con_clients extends Main{


    public $id;
    public $cli_id;
    public $wgroup_id;
    public $status;
    public $groupcode;
    public $siteid;
    public $excode1;
    public $excode2;
    public $excode3;
    public $description;
    public $updated_at;
    public $created_at;
    public $sync_w;

    function __construct() {
        //set model values (tabke keys and table name)
        parent::__construct(array_keys(get_class_vars(get_class($this))),'work_groups_con_clients');
    }

    public function get($obj = null){
        $this->keys = array_keys(get_class_vars(get_class($this)));
        $select = [];
        foreach($this->keys as $c){
            if($c != 'table' && $c != 'conn' )array_push($select,'i.'.$c);
        }
        //build sql
        $sql = "select  ".implode(',',$select)."
                    from ".$this->table." as i
                inner join work_groups as wg on wg.id = i.wgroup_id";
        if($obj != null){
            //set every key for where string
            $values = array();
            $like = array();
            foreach($obj as $k=>$z){
                if($k == 'free'){
                    $z = strtolower(trim($z));
                    foreach($this->keys as $c){
                        if($c != 'table' && $c != 'conn' && $c != 'id' ){
                            $c = 'lower('.trim($c).'::TEXT)';
                            array_push($like,'i.'.$c." like '%".$z."%'");
                        }
                    }
                }else{
                    switch(trim($k)){
                        case 'ftype_id':
                            array_push($values,'wg.'.$k." ='".$z."'");
                            break;
                        case 'id':
                            if($z != '-1') array_push($values,'i.'.trim($k)."='".trim($z)."'");
                            break;    
                        default:
                            if($z != '-1') array_push($values,trim($k)."='".trim($z)."'");
                            break;    
                    }
                }
            }
            if(count($values) > 0)$sql .= ' where '.implode(' and ',$values);
            if(count($like) > 0) $sql .= ' and  ('.implode(' or ',$like).') ';
        }

        $query = $this->conn->query($sql); 
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        //return result
        if(count($result)==0){
            return array('data' =>array(),'success' => false);
        }
        return array('data' =>$result,'success' => true);
    }

}