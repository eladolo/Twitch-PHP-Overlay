<?php namespace DB;

    class DB{
    	public $driver = DB_DRIVER;
        public $host = DB_host;
    	public $dbname = DB_name;
    	public $user = DB_user;
    	public $password = DB_password;
        public $params = array(
            \PDO::ATTR_PERSISTENT => true,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        );

        public function connect(){
            $dsn = $this->driver;
            switch ($dsn) {
                case 'firebird':
                    $dsn .= ":dbname=" . $this->host;
                    break;
                case 'pgsql':
                    $dsn .= ":host=" . $this->host . ";dbname=" . $this->dbname;
                    break;
                case 'sqlite':
                    $dsn .= ":" . $this->dbname;
                    break;
                case 'odbc':
                    $dsn .= ":" . $this->host;
                    break;
                case 'sqlsrv':
                    $dsn .= ":Server=" . $this->host . ";Database=" . $this->dbname;
                    break;
                case 'oci':
                    $dsn .= ":dbname=" . $this->host;
                    break;
                default:
                    # mysql
                    $dsn .= ":host=" . $this->host . ";dbname=" . $this->dbname;
                    break;
            }
            return new \PDO($dsn, $this->user, $this->password, $this->params);
        }

        public function runTransaction($queries_params, $errormsg){
            $pdo = $this->connect();
            try {
                $pdo->beginTransaction();
                foreach ($queries_params as $qrystep) {
                    $statem = $pdo->prepare($qrystep->query);
                    if (!$statem->execute($qrystep->params)){
                        $errorsql = $statem->errorInfo();
                        $statem = null;
                        return array("error" => $errormsg, "sql" => $errorsql[2]);
                    }
                    $statem = null;
                }
                $fnlresp = $pdo->commit();
                $pdo->query('KILL CONNECTION_ID()');
                $pdo = null;
                return $fnlresp;
            } catch (\PDOException $exc) {
                $pdo->rollBack();
                $pdo->query('KILL CONNECTION_ID()');
                $pdo = null;
                return array("error" => $errormsg, "sql" => $exc->getMessage());
            }
        }

        public function runQuery($query, $params, $errormsg, &$insertid = null){
            $pdo = $this->connect();
            try{
                $statem = $pdo->prepare($query);
                if (!$statem->execute($params)) {
                    $errorsql = $statem->errorInfo();
                    return array("error" => $errormsg, "sql" => $errorsql[2]);
                }
                if (isset($insertid)) {
                    //regreso el id del ultimo insert
                    $insertid = $pdo->lastInsertId();
                }
                $pdo->query('KILL CONNECTION_ID()');
                $pdo = null;
                return $statem;
            } catch (\PDOException $exc) {
                $pdo->rollBack();
                $pdo->query('KILL CONNECTION_ID()');
                $pdo = null;
                return array("error" => $errormsg, "sql" => $exc->getMessage());
            }
        }
    }
