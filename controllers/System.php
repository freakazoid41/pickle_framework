<?php
//this file is main parent for model files
namespace app\controllers;
require_once "autoloader.php";

class System{
    function __construct($headers){
		$parts = explode('/',$_SERVER['REQUEST_URI']);
		$this->config = parse_ini_file("../../conf/app/api/config.ini");
		/*if($parts[1]!='admin_login'){
			$rsp = \app\helpers\System::checkToken($headers);
			//token failed
			if(!$rsp['success']){
				
				header('Content-Type: application/json');
				//Status Code 401 = Unauthorized
				header("HTTP/1.1 401 Unauthorized");
				//Writes the JSON message for the user
				$return = array(
					'rsp'		=>	$rsp['success'],
					'message' 	=> 	$rsp['msg'],
					'command'	=> 	0,
				);
				echo json_encode($return);
				die; //Stops executing the PHP 
			}
			$_SESSION['headers'] = $headers;
		}*/
    }


    public function handleRequest($parts,$request){
        //1.part is model name
        //get model
        require_once "models/".ucfirst($parts[1]).".php";
        //create new instence
        $model = 'app\\models\\'.ucfirst($parts[1]);
        $model = new $model();
        
        //2.part is id for get,patch or delete transactions
		$sync = 'ADD';
        switch($request){
            case "GET":
                //get request for data getting
                return $model->get(array(
					'id'=>isset($parts[2]) ? $parts[2] : '-1'
				));
            break;
			case "POST":
				//check if is table data request
				if(isset($_POST['rtype']) && $_POST['rtype'] = 'table'){
					unset($_POST['rtype']);
					return $model->getTable(json_decode($_POST['data'],true));
				}

				$rsp = $model->add($_POST);
				if($rsp['success'] == true){
					//send chages to sync api
					\app\helpers\System::sync('ADD',array(
						'table' => $parts[1],
						'where' => array(
							'id' => $rsp['data']['id']
						)
					));
				}
				//post request for data setting
				return $rsp;
            break;
			case "PATCH":
				if(isset($parts[2])){
					//patch request for data updating
					$var_array = array();
					mb_parse_str(file_get_contents("php://input"),$var_array);
					$var_array['id'] = $parts[2];
					$rsp = $model->update($var_array);

					if($rsp['success'] == true){
						//send chages to sync api
						\app\helpers\System::sync('UPD',array(
							'table' => $parts[1],
							'where' => array(
								'id' => $var_array['id']
							)
						));
					}

					return $rsp;
				}
			break;
			case "DELETE":
				$sync = 'DEL';
				if(isset($parts[2])){
					//first get old record
					$rsp = $model->get(array(
						'id' => $parts[2]
					));
					//send del request to api
					if($rsp['success'] == true){
						//send chages to sync api
						if(isset($rsp['data'][0]['sync_w'])){
							$where = array('sync_w' => $rsp['data'][0]['sync_w']);
						}else{
							$where = array('id' => $parts[2]);
						}
						\app\helpers\System::sync('DEL',array(
							'table' => $parts[1],
							'where' => $where
						));
					}
					//delete request for data removing
					return $model->delete(array('id'=>$parts[2]));
				}
            break;
        }
		return array('msg' => 'Maybe something is missing ? (you did not send "id" idiot..)');
    }

	public function handleTableRequest($parts,$request){
        //1.part is model name
        //get model
        require_once "models/".ucfirst($parts[1]).".php";
        //create new instence
        $model = 'app\\models\\'.ucfirst($parts[1]);
        $model = new $model();
        
        return $model->getTable(json_decode($_POST['tableReq'],true));
	}
	
	public function handleQueryRequest($parts,$request){
        //1.part is model name
        //get model
        require_once "models/".ucfirst($parts[1]).".php";
        //create new instence
        $model = 'app\\models\\'.ucfirst($parts[1]);
        $model = new $model();
        
        return $model->get($_POST,true);
    }


    public function login($data,$headers){
		

        //get models
        require_once "models/Sys_users.php";
		require_once "models/Sys_utokens.php";

		$users = new \app\models\Sys_users();
		$utokens = new \app\models\Sys_utokens();

		//get user
		$rsp = $users->get($data);
		//if user exist
		if(!empty($rsp['data'])){
			//get token and person info
			$token = \app\helpers\System::getToken();
			$label = $headers['User-Agent'];
			//clean all old dated tokens
			$utokens->delete(array(
				'user_id'=>$rsp['data'][0]['id'],
				'user_sign'=>$label
			));

			//add new token to database
			$utokens->add(array(
				'user_sign'=>$label,
				'user_id'=>$rsp['data'][0]['id'],
				'user_token' => $token['token'],
				'end_at'=>$token['end']
			));

			//send new created token to client
			return array(
				'rsp'=>true,
				'msg' => 'Logged in with debug !!',
				'data' => array(
					'user_id'   => $rsp['data'][0]['id'],
					//'person_id'=>$person['data'][0]['id'],
					'type'      => $rsp['data'][0]['utype'],
					'token'     => $token['token'],
					'sysclient' => $this->config['sysclient']
					//'person' => $person['data'][0]->name,
				)
			);
		}else{
			return array('rsp'=>false,'msg'=>'Bilgiler Yanlış !!');
		}
    }

}