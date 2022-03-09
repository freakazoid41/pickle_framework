<?php
//this file is main parent for model files
namespace app\models;


require_once "config/database.php";
use app\config\Database as db; 

class Main extends db{
    /**
     * table keys
     * @var array 
     */
    private $keys = [];

    /**
     * table name
     * @var string
     */
    protected $table = 'no_name';


    /**
     * Connection
     * @var object 
     */
    protected $conn;


    /**
     * this method will contruct table keys and database connection
     * @var array 
     */
    function __construct($keys = array(),$table = 'none'){
        //connect to database
        parent::__construct();
        //set table keys
        $this->keys = $keys;
        //se table name
        $this->table = $table;
        //connect to database
        $this->conn = $this->connect();
    }


    public function get($obj = null){
        $select = [];
        foreach($this->keys as $c){
            if($c != 'table' && $c != 'conn' )array_push($select,'i.'.$c);
        }
        //build sql
        $sql = "select  ".implode(',',$select)." from ".$this->table." as i";
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
                            array_push($like,$c." like '%".$z."%'");
                        }
                    }
                }else{
                    if($z != '-1')array_push($values,trim($k)."='".trim($z)."'");
                }
            }
            if(count($values) > 0)$sql .= ' where '.implode(' and ',$values);
            if(count($like) > 0) $sql .= ' and  ('.implode(' or ',$like).') ';
        }
        $sql.= ' order by id ';
        $query = $this->conn->query($sql); 
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        //return result
        if(count($result)==0){
            return array('data' =>array(),'success' => false);
        }
        return array('data' =>$result,'success' => true);
    }

    public function add($data=null){
        $rsp = array('data' =>array(),'success' => false,'msg'=>'Empty form..');
        if($data != null){
            $values = array();
            $keys = array();
            //set every key for update string
            foreach($data as $k=>$z){
                if($z != '-1' && $k != 'id' && \strlen($z)>0){
                    \array_push($keys,$k);
                    \array_push($values,"'".$z."'");
                }
            }

            //set lang key if exist in model
            if(in_array('lang_key',$this->keys)){
                array_push($keys,'lang_key');
                array_push($values,"'".$this->lang_key."'");
            }
            
            //build sql
            $sql = 'insert into '.$this->table.' ('.implode(',',$keys).") values (".implode(',',$values).")" ;
            
            $query = $this->conn->query($sql); 
            //get effected row count
            $result = $this->conn->lastInsertId();
            //return result
            if($result==0){
                $rsp = array('data' =>array(),'success' => false);
            }else{
                $rsp = array('data' =>array('id'=>$result),'success' => true,'msg'=>'Success...');
            }
        }
        return $rsp;
    }

    public function update($data){
        $rsp = array('data' =>array(),'success' => false,'msg'=>'Empty form..');
        if($data != null){
            //if id sended
            if(isset($data['id'])){
                $values = array();
                //set every key for update string
                foreach($data as $k=>$z){
                    if($z != '-1' && $k != 'id')array_push($values,trim($k)."='".trim($z)."'");
                }
                //build sql
                $sql = 'update '.$this->table.' set '.implode(',',$values)." where id='".$data['id']."'" ;
                $query = $this->conn->query($sql); 
                //get effected row count
                $result = $query->rowCount();
                //return result
                if($result!=1){
                    $rsp = array('data' =>array(),'success' => false);
                }else{
                    $rsp = array('data' =>array(),'success' => true,'msg'=>'Success...');
                }
            }else{
                $rsp = array('data' =>array(),'success' => false,'msg'=>'No id..');
            }
            
        }
        return $rsp;
    }

    public function delete($data){
        $rsp = array('data' =>array(),'success' => false);
        if($data != null){
            
            $values = array();
            //set every key for update string
            foreach($data as $k=>$z){
                if($z != '-1')array_push($values,trim($k)."='".trim($z)."'");
            }
            //build sql
            $sql = 'delete from '.$this->table.' where '.implode(' and ',$values) ;
            $query = $this->conn->query($sql); 
            //get effected row count
            $result = $query->rowCount();
            //return result
            if($result!=1){
                $rsp = array('data' =>array(),'success' => false);
            }else{
                $rsp = array('data' =>array(),'success' => true,'msg'=>'Success...');
            }
            
        }
        return $rsp;
    }

    public function trans($type=0){
        switch($type){
            case 0:
                //begin transaction
                $this->conn->beginTransaction();
            break;
            case 1:
                //commit transaction
                $this->conn->commit();
            break;
            case 2:
                //rollback transaction
                $this->conn->rollBack();
            break;
        }

    }


    function case_converter( $keyword, $transform='lowercase' ){

		$low = array('a','b','c','ç','d','e','f','g','ğ','h','ı','i','j','k','l','m','n','o','ö','p','r','s','ş','t','u','ü','v','y','z','q','w','x');
		$upp = array('A','B','C','Ç','D','E','F','G','Ğ','H','I','İ','J','K','L','M','N','O','Ö','P','R','S','Ş','T','U','Ü','V','Y','Z','Q','W','X');

		if( $transform=='uppercase' OR $transform=='u' )
		{
			$keyword = str_replace( $low, $upp, $keyword );
			$keyword = function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $keyword ) : $keyword;

		}elseif( $transform=='lowercase' OR $transform=='l' ) {
			
			$keyword = str_replace( $upp, $low, $keyword );
			$keyword = function_exists( 'mb_strtolower' ) ? mb_strtolower( $keyword ) : $keyword;

		}

		return $keyword;

	}

    //simple query returning function
    public function query($sql){
        $query = $this->conn->query($sql); 
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        //return result
        if(count($result)==0){
            return array('data' =>array(),'success' => false);
        }
        return array('data' =>$result,'success' => true);
    }
}