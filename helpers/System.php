<?php

namespace app\helpers;


class System {

    static function getToken()
    {
        $token = 'pickle_key'.str_replace('.','',microtime(true));
        return array(
            'token'=>base64_encode($token),
            'end' => date("Y-m-d H:i:s", strtotime("+30 minutes"))
        );
    }

    static function checkToken($obj)
    {

        if(!isset($obj['X-TOKEN'])){
            return array('success'=>false,'msg'=>'Key Not Exist !!!');
            /*//load user token model
            require_once "models/Sys_utokens.php";
            $utokens = new \app\models\Sys_utokens();
            //get token info    
            $token = $utokens->get(array(
                'user_token' => $obj['X-TOKEN'],
                'user_sign'=>$obj['User-Agent']
            ));

            if($token['success']){
                //check time is valid
                if(strtotime("now") < strtotime(explode('+',$token['data'][0]['end_at'])[0])){
                    return array('success'=>true);
                }else{
                    //give more time if you want or just reject
                    //hour diff
                    $diff =  abs(strtotime("now") - strtotime(explode('+',$token['data'][0]['end_at'])[0]))/(60*60);
                    if($diff<1){
                        //give another 30 minute
                        $utokens->update(array(
                            'id' => $token['data'][0]['id'],
                            'end_at' =>date("Y-m-d H:i:s", strtotime("+30 minutes"))
                        ));
                        return array('success'=>true);
                    }else{
                        //clear keys
                        $CI->sys_utokens->_delete($token['data'][0]['id']);
                        return array('success'=>false,'msg' => 'old key ..');
                    }
                }
            }else{
                return array('success'=>false,'msg'=>'Invalid Key..');
            }*/
        }else{
            return array('success'=>true);
            //return array('success'=>false,'msg'=>'Key Not Exist !!!');
        }
    }   

    //this method will connect with api and get or set information
    static function sync($event = 'ADD',$criterion){
        /**
         * 1 for pepper
         * 2 for fafactor 
         * 3 for plasiyer
         * 4 for report
         * 5 for modules
         * 6 for çoban
         */


        $tables = array(
            /*'clients'                 => '1,5', 
            'clients_personnel'       => '1', 
            'clients_bank'            => '1',
            'sys_addresses'           => '1',
            'work_groups_con_clients' => '1',
            'cheques'                 => '1',
            'sys_transactions'        => '1',
            'invoices_close'          => '1'*/
        );





        if (isset($tables[$criterion['table']])) {
            $config = parse_ini_file("../../conf/app/sync/config.ini");

            $curl = curl_init();
            $url = $config['sync-api'].'/request/sync_trans';
            $method = 'POST';
            $data = array(
                'host'   => 2,
                'system' => $tables[$criterion['table']], // for target system
                'trans'  => $event,
                'criterion' => json_encode($criterion)
            );
            curl_setopt($curl, CURLOPT_POST, 1);
            if (!empty($data))
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            // EXECUTE:
            $result = curl_exec($curl);
            if(!$result){die("Connection Failure");}
            curl_close($curl);
            return json_decode($result,true);
        }
    }
}