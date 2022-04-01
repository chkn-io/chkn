<?php

namespace App\App;
use PDO;
class App_Model{
		protected static $dbconn;
		private static $statement;
		protected static function db_connect() {
            if(DB_HOST != '' && DB_NAME != '' && DB_USER != '' && DB_CONNECTION != ''){
                try {
                    self::$dbconn=new PDO(DB_CONNECTION.':host='.DB_HOST.';dbname='.DB_NAME.';port=3306;charset='.DB_CHARSET.'',''.DB_USER.'',''.DB_PASSWORD.'');
                    self::$dbconn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                    self::$dbconn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    return [0=>"success"];
                }catch(PDOException $e){
                    $error = array();
                    $error[0] = 'Database Connection Error';
                    $error[1] = $e->getMessage();
                    return $error;
                }
            }else{
                $error = array();
                $error[0] = 'Database Connection Error';
                $error[1] = '';
                return $error;
            }

		}
		
		public static function db_prepare($sql){
            self::$statement = self::$dbconn->prepare($sql);
		}
		
		protected static function db_bind($param, $value) {
			self::$statement->bindParam($param, $value);
		}
		protected static function db_execute() {
			self::$statement->execute();
			return self::$statement->fetchAll(PDO::FETCH_ASSOC);
		}

		protected static function lastInsertedID(){
			return self::$dbconn->lastInsertId();
		}
}