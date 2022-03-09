<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
  session_start();

  class Passage {
    //temp token for test
    private $token = '-';
    private $pid = 0;
    private $config;  

    private $permissions = array(
      //'sys_users',
      //'sys_demands',
    );

    function __construct(){
      $this->config = parse_ini_file("../../../conf/app/client/config.ini");
      //set session informations to params if logged in
      if(isset($_SESSION['sinfo'])){
        $this->pid    = $_SESSION['sinfo']['person_id'];
        $this->token  = $_SESSION['sinfo']['token'];
      }
    }

    /**
     * this method will send and receive data between api and client
     */
    public function call($url){
      switch($url){
        case '/login':
          return json_encode($this->login());
        case '/shepherd':
          $this->shepherd();
          break;
        break;
        case '/getDocno':
          return json_encode($this->getDocno());
        case '/forgot':
          return $this->forgot($_POST);
        break;
        default:
          //file transactions
          if(isset($_FILES['file']['name']) && !empty($_FILES['file']['name'])) {
            $fresponse = $this->saveFile($_FILES,explode('/',$url)[2]);
            if(boolval($fresponse['rsp']) != true){
              return json_encode(array('rsp'=>false,'msg'=>'System Not Saved File !!','rsp'=>$fresponse));
            }else{
              $_POST['s_file'] = $fresponse['file'];
            }
          }

          $method = $_SERVER['REQUEST_METHOD'];
          
          //its just a trick for file sendings (cannot send fille with patch from javascript)
          if(isset($_POST['method']) && $_POST['method'] == 'PATCH'){
            unset($_POST['method']);
            $method = 'PATCH';
          }

          $curl = curl_init();
          switch ($method){
              case 'POST':
                curl_setopt($curl, CURLOPT_POST, 1);
                if (!empty($_POST))
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $_POST);
                break;
              case 'DELETE':
                $data = array();
                mb_parse_str(file_get_contents("php://input"),$data);

                curl_setopt($curl, CURLOPT_CUSTOMREQUEST,$method);
                if (!empty($data))
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));			 					
                break;
              case 'PATCH':
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST,$method);
                if (!empty($_POST))
                    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($_POST));			 					
                break;
              default:
                break;
          }
          // OPTIONS:
          curl_setopt($curl, CURLOPT_URL, $this->config['api_url'].$url);
          curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              'X-TOKEN:'.$this->token,
              'Person-Id:'.$this->pid,
              'User-Agent:'.$_SERVER['HTTP_USER_AGENT'],
              'IP:'.$_SERVER['REMOTE_ADDR'],
          ));
          curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
          // EXECUTE:
          $result = curl_exec($curl);
          if(!$result){die("Connection Failure");}
          curl_close($curl);
          if(isset($_POST['s_file'])){
            $result = json_decode($result,true);
            $result['s_file'] = $_POST['s_file'];
            $result = json_encode($result);
          }
          return $result;
        break;
      }
    }

    /**
     * this method will connect with shepherd
     */
    function login($data = null){
      if($data == null) $data = $_POST;
      //login from shepherd
      $url = $this->config['shepherd_api_url'].'login';
      $curl = curl_init();
      $headers =  array(
          "Cache-Control: no-cache",
          'User-Agent:'.$_SERVER['HTTP_USER_AGENT'],
          'IP:'.$_SERVER['REMOTE_ADDR'],
      );

      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      
      // OPTIONS:
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
      curl_setopt($curl, CURLOPT_HTTPHEADER,$headers );
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
      curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      // EXECUTE:
      $result = curl_exec($curl);
      if(!$result){die("Connection Failure");}
      curl_close($curl);
      $person = json_decode($result,true);
      
      if(intval($person['rsp'])==1){
        $person['data']['cdnUrl']    = $this->config['cdn_url'];
        $person['data']['sysClient'] = $this->config['sysclient'];
        $_SESSION['sinfo'] = $person['data'];
        $_SESSION['sinfo']['image'] = $this->config['shp_cdn_url'].'shepherd/per_card/'.$_SESSION['sinfo']['image'];
        return array('success' => true,'data' => $_SESSION['sinfo']);
      }else{
        return array('success' => false,'data' => array(),'err'=>$person['msg']);
      }
    }

    function shepherd(){
      $rsp = $this->login();
      if($rsp['success']){
        echo "<script>localStorage.setItem('sinfo',JSON.stringify(".json_encode($rsp['data']).")); window.location.href = '/#/home';</script>";
        //header("Location: /#/home");
      }else{
        header("Location: /#/login");
      }
      die;
    }

    /**
     * this method will send forgot user info request
     */
    function forgot($data){
      //login from shepherd
      $url =$this->config['shepherd_api_url'].'forgot';
      $curl = curl_init();
      $headers =  array(
          "Cache-Control: no-cache",
          'User-Agent:'.$_SERVER['HTTP_USER_AGENT'],
          'IP:'.$_SERVER['REMOTE_ADDR'],
      );

      curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
      
      // OPTIONS:
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLINFO_HEADER_OUT, 1);
      curl_setopt($curl, CURLOPT_HTTPHEADER,$headers );
      curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      // EXECUTE:
      $result = curl_exec($curl);
      if(!$result){die("Connection Failure");}
      curl_close($curl);
      return $result;
    }

    function logout(){
      session_destroy();
      header('Location: /');
    }

    /**
     * this method will save file to client
     */
    function saveFile($file,$model){
      $allowed = array('gif', 'png', 'jpg', 'jpeg','pdf');
      if (in_array(strtolower(pathinfo($file['file']['name'], PATHINFO_EXTENSION)), $allowed)) {
        $filename = $model.'tempfile.'.strtolower(pathinfo($file['file']['name'], PATHINFO_EXTENSION));
        
        @unlink(realpath('./files/'.$filename));
        //first save for temperory
        $res = move_uploaded_file($file['file']['tmp_name'], './files/'.$filename);
        if (function_exists('curl_file_create')) { // php 5.5+
            $cFile = curl_file_create(realpath('./files/'.$filename));
        }
        $post = array('model' => $model,'key'=> date('Ymdh').'-'.str_replace(".", "", microtime(true)),'file_contents'=> $cFile);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->config['cdn_url'].'fresolver.php?job=_accept&dir='.$model);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close ($ch);
        /*$filename = date('Ymdh').'-'.str_replace(".", "", microtime(true)).'.'.pathinfo($file['file']['name'], PATHINFO_EXTENSION);
        
        //first save for temperory
        $res = move_uploaded_file($file['file']['tmp_name'], './files/'. $model.'/'.$filename);
        return array('rsp'=>$res,'file' => $filename);*/
        return json_decode($result,true);
      }else{
        return array('rsp'=>false);
      }
    }

    function getDocno(){
      return array('success' => true,'data' => 'F'.str_replace('.','',strval(microtime(true))));
    }

  }   



print_r((new Passage())->call($_GET['url']));
die;