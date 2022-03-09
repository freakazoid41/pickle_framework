<?php
//this file is main parent for model files
namespace app\models;


require_once "Main.php";
use app\models\Main as Main; 



class Cash extends Main{


    public $id;
    public $ftype_id;
    public $ctype_id;
    public $limit_check;
    public $cur_id;
    public $linked_account_id;
    public $permission_worker_id;
    public $cash_limit;
    public $p_code;
    public $code;
    public $title;
    public $status;
    public $groupcode;
    public $siteid;
    public $excode1;
    public $excode2;
    public $excode3;
    public $description;
    public $updated_at;
    public $created_at;

    function __construct() {
        //set model values (tabke keys and table name)
        parent::__construct(array_keys(get_class_vars(get_class($this))),'cash');
    }

    function getTable($obj){
        $columns = array(
            'id'          => 'i.id',
            'title'       => 'i.title',
            'code'        => 'i.code',
            'ftype'       => 'op.title as ftype',
            'ftype_id'    => 'i.ftype_id',
            'cur_id'      => 'i.cur_id',
            'cur'         => 'c.code as cur',
            'cash_limit'  => 'i.cash_limit',
            'status'      => 'i.status',
            'description' => 'i.description',
            'created_at'  => 'i.created_at',
        );
        $limit = '';
        $order = '';
        $join = ' inner join sys_options as op on op.id = i.ftype_id 
                  inner join sys_currencies as c on c.id = i.cur_id';
        $where = ' where i.title!=\'\' ';    
        if (isset($obj['scale']['page']) && isset($obj['scale']['limit'])) {
            $start = (intval($obj['scale']['page']) * intval($obj['scale']['limit'])) - intval($obj['scale']['limit']);
            $limit = " offset ".$start.' limit '. $obj['scale']['limit'];
        }else{
            $obj['scale']['limit'] = 1;
        }

        if (isset($obj['order'])){
            $column = explode('as',$columns[$obj['order']['key']])[0];
            $order = ' order by ' .$column. ' ' . $obj['order']['style'].' ';
        }else{
            $order = ' order by id asc';
        }
        
        if (isset($obj['filter'])){
            //$obj['filter'] = json_decode($obj['filters'],true);
            foreach($obj['filter'] as $f){
                switch($f['key']){
                    case 'free':
                    case 'all':
                        $value = mb_strtoupper($f['value']);
                        $where .= ' and (';
                        //set columns   
                        $i = 0;
                        foreach($columns as $k=>$v){
                            if($i!=0) $where.=' or ';
                            $column = explode(' as ',$columns[$k])[0];
                            $where.=' upper(trim(CAST('.$column.' as TEXT))) like'."'%" . $value . "%' ";
                            $i++;
                        }
                        $where .= ' ) ';
                    break;
                    default:
                        $column = explode('as',$columns[$f['key']])[0];
                        if(trim($f['value']) != ''){
                            if($f['type'] != 'like'){
                                $where.=" and ".$column." ='".$f['value']."' ";
                            }else{
                                $where.=" and upper(trim(CAST(".$column." as TEXT))) like '%".$this->case_converter($f['value'],'uppercase')."%' ";
                            }
                        }
                        break;
                }
                
            }
        }     

        //create query    
        $sql = 'select '.implode(",", array_values($columns)).'
                    from '.$this->table.' as i '.$join.' ' . $where.$order.$limit ;
        $result = $this->conn->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
       
        //count query
        $sql = 'select count(*) as row from '.$this->table.' as i '.$join.' '.$where;
        $total_count = $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        return array(
            'data'          => $result,
            'pageCount'     => ceil(intval($total_count['row']) / intval($obj['scale']['limit'])),
            'totalCount'    => $total_count['row'],
            'filteredCount' => count($result),
        );
    }

}