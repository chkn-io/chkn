<?php

namespace App\Model;

use App\App\App_Model;
use PDO;
/**CHKN Framework PHP
 * Copyright 2015 Powered by Percian Joseph C. Borja
 * Created May 12, 2015
 *
 * Class process_model
 * This class holds the PDO Main function the CRUD
 *
 *
 */
class Model extends App_Model{
    protected $keys;
    protected $params = array();
    protected $key_values = array();
 
    /**
     * @return mixed
     * Checks if the database is already settled up and does'nt used the default value for the variables
     */
    public static function check_db(){
        $response = self::db_connect();
        return $response;
    }

    public static function lastInsertId(){
        return self::lastInsertedID();
    }

    /**
     * @param $sql
     * @return mixed
     * This function collects all the data needed by the user
     * It will automatically generate a SELECT query
     */
    public static function get_list($query,$line,$indexes){
        try {
            self::db_connect();
            self::db_prepare($query);
            foreach ($indexes as $key => $value) {
                $key = str_replace(".", "", $key);
                self::db_bind(':chknParam'.$key,$value,PDO::PARAM_STR);
            }
            $data = self::db_execute();
            $count = count($data);
            return $data;
        } catch (PDOException $e) {
            print_r($e);
        }
    }

   
    /**
     * @param $sql
     * @return mixed
     * This function allows the user to add data to the database easier
     * It will automatically generate a INSERT query and VALUES that is based on the data gathered by $sql
     */
    public static function add_query_execute($query,$line,$indexes){
        try {
            self::db_connect();
            
            self::db_prepare($query);
            foreach ($indexes as $key => $value) {
                self::db_bind(':chknParam'.$key,$value,PDO::PARAM_STR);
            }
            self::db_execute();

            $id = self::lastInsertId();
            return $id;
        } catch (Exception $e) {
        }
        
    }

    /**
     * @param $sql
     * @return mixed
     * This function allows the user to delete data to the database easier
     * It will automatically generate a DELETE query that is based on the data gathered by $sql
     */
    public static function delete_query_execute($query,$line,$indexes){
         try {
            self::db_connect();
            self::db_prepare($query);
            foreach ($indexes as $key => $value) {
                self::db_bind(':chknParam'.$key,$value,PDO::PARAM_STR);
            }
            self::db_execute();
        } catch (PDOException $e) {
        }
    }

    /**
     * @param $sql
     * @return mixed
     * This function allows the user to update data to the database easier
     * It will automatically generate a UPDATE query that is based on the data gathered by $sql
     */
    public static function update_query_execute($query,$line,$sets,$indexes){
        try {
            self::db_connect();
            self::db_prepare($query);
            foreach ($indexes as $key => $value) {
                self::db_bind(':chknParam'.$key,$value);
            }
            foreach ($sets as $key => $value) {
                self::db_bind(':chknvParam'.$key,$value);
            }
            self::db_execute();
        } catch (PDOException $e) {
        }
    }

    /**
     * @param $table
     * @return mixed
     * Truncate or remove all the data on a table
     */
    public static function truncate_exec($table,$line){
        self::db_connect();
        $query = 'TRUNCATE TABLE '.$table;
        self::db_prepare($query);
        $response = self::db_execute();
        return $response;
    }


   
}
