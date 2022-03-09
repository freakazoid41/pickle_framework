<?php
//this file is main parent for model files
namespace app\models;


require_once "Main.php";
use app\models\Main as Main; 

class Banks extends Main{


    public $id;
    public $ftype_id;
    public $title;
    public $limit_check;
    public $p_code;
    public $c_code;
    public $b_code;
    public $bank_limit;
    public $status;
    public $siteid;
    public $groupcode;
    public $excode1;
    public $excode2;
    public $excode3;
    public $description;
    public $updated_at;
    public $created_at;

    function __construct() {
        //set model values (tabke keys and table name)
        parent::__construct(array_keys(get_class_vars(get_class($this))),'banks');
    }

    /**
     * this function will remove all remmants of bank and the bank himself
     */
    public function delete($data){
        $rsp = array('data' =>array(),'success' => false);
        if($data != null){
            //start transaction
            $this->trans();
            $valid = true;
            
            //find branchs
            $query   = $this->conn->query("select * from banks_branch where bank_id = '".$data['id']."'"); 
            $branchs = $query->fetchAll(\PDO::FETCH_ASSOC);
            //froreach branch
            foreach($branchs as $b){
                $query = $this->conn->query("delete from banks_accounts where branch_id = '".$b['id']."'"); 
            }

            //remove branches
            $query   = $this->conn->query("delete from banks_branch where bank_id = '".$data['id']."'");

            //remove bank
            $query   = $this->conn->query("delete from banks where id = '".$data['id']."'");

            if($valid){
                //commit transactions
                $this->trans(1);
                $rsp = array('data' =>array(),'success' => true,'msg'=>'Success...');
            }else{
                $this->trans(2);
                $rsp = array('data' =>array(),'success' => false);
            }
        }
        return $rsp;
    }

    function getTable($obj){
        $columns = array(
            'id'          => 'i.id',
            'title'       => 'i.title',
            'c_code'      => 'i.c_code',
            'b_code'      => 'i.b_code',
            'ftype'       => 'op.title  as ftype',
            'ftype_id'    => 'i.ftype_id',
            'bank_limit'  => 'i.bank_limit',
            'status'      => 'i.status',
            'description' => 'i.description',
            'created_at'  => 'i.created_at',
            'acount'     => '(select count(ba.id) from banks_accounts as ba
                                inner join banks_branch as bb on bb.id = ba.branch_id
                                where bb.bank_id = i.id)  as acount',
            'bcount'      => '(select count(id) from banks_branch where bank_id = i.id)  as bcount'
        );
        $limit = '';
        $order = '';
        $join = ' inner join sys_options as op on op.id = i.ftype_id ';
        $where = ' where i.title!=\'\' ';    
        if (isset($obj['scale']['page']) && isset($obj['scale']['limit'])) {
            $start = (intval($obj['scale']['page']) * intval($obj['scale']['limit'])) - intval($obj['scale']['limit']);
            $limit = " offset ".$start.' limit '. $obj['scale']['limit'];
        }else{
            $obj['scale']['limit'] = 1;
        }

        if (isset($obj['order'])){
            switch($obj['order']['key']){
                case 'bcount':
                    $column = 11;
                break;
                case 'acount':
                    $column = 12;
                break;
                default:
                    $column = explode(') as',$columns[$obj['order']['key']])[0];
                break;
            }
            $order = ' order by ' .$column. ' ' . $obj['order']['style'].' ';
        }else{
            $order = ' order by i.c_code asc';
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
                            $column = explode('  as',$columns[$k])[0];
                            $where.=' upper(trim(CAST('.$column.' as TEXT))) like '."'%" . $value . "%' ";
                            $i++;
                        }
                        $where .= ' ) ';
                    break;
                    default:
                        if($f['key'] == 'cur_id'){
                            $join .= 'inner join banks_branch as bb on bb.bank_id = i.id
                                      inner join banks_accounts as ba on ba.branch_id = bb.id';
                            if(\strpos($f['value'],',') !== false){
                                $where.=" and ba.cur_id in (".$f['value'].")";
                            }else{
                                $where.=" and ba.cur_id = '".$f['value']."'";
                            }
                        }else{
                            $column = explode('  as',$columns[$f['key']])[0];
                            if(trim($f['value']) != ''){
                                if($f['type'] != 'like'){
                                    $where.=" and ".$column." ='".$f['value']."' ";
                                }else{
                                    $where.=" and upper(trim(CAST(".$column." as TEXT))) like '%".$this->case_converter($f['value'],'uppercase')."%' ";
                                }
                            }
                        }
                        break;
                }
                
            }
        }     
       
        //create query    
        $sqlm = 'select distinct '.implode(",", array_values($columns)).'
                        from '.$this->table.' as i '.$join.' ' . $where.$order.$limit ;
        $result = $this->conn->query($sqlm)->fetchAll(\PDO::FETCH_ASSOC);
       
        //count query
        $sql = 'select count(distinct i.id) as row from '.$this->table.' as i '.$join.' '.$where;
        $total_count = $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        return array(
            'data'          => $result,
            'pageCount'     => ceil(intval($total_count['row']) / intval($obj['scale']['limit'])),
            'totalCount'    => $total_count['row'],
            'filteredCount' => count($result),
            'debug'         => $sqlm
        );
    }
    
}