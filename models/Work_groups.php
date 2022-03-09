<?php
//this file is main parent for model files
namespace app\models;


require_once "Main.php";
use app\models\Main as Main; 



class Work_groups extends Main{


    public $id;
    public $parent_id;
    public $ftype_id;
    public $title;
    public $created_at;

    function __construct() {
        //set model values (tabke keys and table name)
        parent::__construct(array_keys(get_class_vars(get_class($this))),'work_groups');
    }


    function getTable($obj){
        $columns = array(
            'id'     => 'i.id',
            'title'  => 'i.title',
            'person' => 'p.name as person'
        );

        $limit = '';
        $order = '';
        $join = '   inner join work_group_connections wc on wc.work_group_id = i.id and wc.is_main = 1 and wc.ref_type=1
                    inner join persons p on wc.ref_id = p.id and p.status=1 ';
        $where = ' where 1=1 '; 
       
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
            $order = ' order by i.created_at desc ';
        }
        
        if (isset($obj['filter'])){
            //$obj['filter'] = json_decode($obj['filters'],true);
            foreach($obj['filter'] as $f){
                switch($f['key']){
                    case 'free':
                    case 'all':
                        $value = $this->case_converter($f['value'],'uppercase');
                        //set columns   
                        $i = 0;
                        foreach($columns as $k=>$v){
                            if($i!=0) $where.=' or ';
                            switch($k){
                                default:
                                    $where.=' upper(trim(CAST('.$k.' as TEXT))) like'."'%" . $value . "%' ";
                                    if(strpos($value,'I') !== false)$where.=' or upper(trim(CAST('.$k.' as TEXT))) like'."'%" . str_replace('I','İ',$value) . "%' ";
                                    if(strpos($value,'İ') !== false)$where.=' or upper(trim(CAST('.$k.' as TEXT))) like'."'%" . str_replace('İ','I',$value) . "%' ";
                                break;
                            }
                            $i++;
                        }
                       
                    break;
                    default:
                        switch($f['key']){
                            case 'created_at':
                                $where .= " and to_char(i.".$f['key'].", 'DD.MM.YYYY') like '%".$f['value']."%' ";
                                break;
                            default :   
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
                        break;
                }
                
            }
        }     

        //create query    
        $sql = 'select '.implode(",", array_values($columns))." from ".$this->table.' as i '.$join.' ' . $where.$order.$limit;
        $result = $this->conn->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        //count query
        $sql = 'select count(distinct i.id) as row from '.$this->table.' as i '.$join.' '.$where;
        $total_count = $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        return array(
            'data'          => $result,
            'pageCount'     => ceil(intval($total_count['row']) / intval($obj['scale']['limit'])),
            'totalCount'    => $total_count['row'],
            'filteredCount' => count($result),
        );
    }

}