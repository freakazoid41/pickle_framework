<?php
//this file will contain db parameters of project
namespace app\config;

class Database {
    /**
     * database params
     * @var type 
     */
    private $params;

    protected function __construct(){
        //set database parameters
        /*$config = parse_ini_file("../../conf/app/api/config.ini");
        $this->params = array(
            //host name  parameter
            'host'     => $config['hostname'],
            //post parameter
            'port'     => '5432',
            //username parameter
            'user'     => $config['username'],
            //password parameter
            'password' => $config['password'],
            //db name parameter
            'database' => $config['database'],
        );*/
    }


    /**
     * this method will connect to database and return pdo element
     * @return \PDO
     * @throws \Exception
     */
    function connect(){
        /*$pdo = new \PDO('pgsql:host='.$this->params['host'].';port=5432;dbname='.$this->params['database'].';user='.$this->params['user'].';password='.$this->params['password']);
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $pdo;*/
    }
}

