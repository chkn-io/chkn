<?php
namespace App\Database;

use App\Model\Model;

class DB extends Model{
    private static $line;
    private static $q;
    private static $indexes = array();
    private static $sets = array();
    private static $index_counter = 1;
    private static $trans;
    private static $jointrace;
	

    public static function check_db(){
        if(QUERY_BUILDER == 1){
            $response = self::check_db();
            return $response;
        }
    }

    public static function lastInsertId(){
        if(QUERY_BUILDER == 1){
            return self::lastInsertId();
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
        
    }

    public static function join($table,$reference1,$comparison,$reference2){
        self::$q .=' INNER JOIN '.$table." ON ".$reference1." = ".$reference2." ";
        self::$jointrace = true;
        return new static;
    }

    public static function select($table,$fields="*"){
        if(QUERY_BUILDER == 1){
            self::$index_counter = 0;
            self::$indexes = array();
            self::$jointrace = false;
            self::$q = "";
            self::$q = 'SELECT '.$fields.' FROM '.$table.' ';

            return new static;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
        
    }

    public static function where($index,$operation,$value,$opt = 'and'){
        self::$indexes[$index] = $value;
        if(self::$index_counter == 0){
            if(self::$jointrace == false){
                self::$q .='WHERE '.$index.' '.$operation.' :chknParam'.$index.' ';
            }else{
                $trace = str_replace(".", "", $index);
                self::$q .='WHERE '.$index.' '.$operation.' :chknParam'.$trace.' ';
            }
            
        }else{
            if(self::$jointrace == false){
                self::$q .=$opt.' '.$index.' '.$operation.' :chknParam'.$index.' ';;
            }else{
                $trace = str_replace(".", "", $index);
                self::$q .=$opt.' '.$index.' '.$operation.' :chknParam'.$trace.' ';;
            }
        }
        self::$index_counter++;
        return new static;
    }

    public static function bind($index,$value){
        self::$indexes[$index] = $value;
        return new static;
    }
    public static function orderBy($index,$type){
        self::$q .=' ORDER BY '.$index.' '.$type;
        return new static;
    }

    public static function limit($value){
        self::$q .=' LIMIT '.$value;
        return new static;
    }

    public static function delete($table){
        if(QUERY_BUILDER == 1){
            self::$index_counter = 0;
            self::$q = 'DELETE FROM '.$table.' ';
            self::$trans = 'delete';
            return new static;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }
    public static function query($sql){
        if(QUERY_BUILDER == 1){
            self::$index_counter = 0;
            self::$q = $sql;
            self::$trans = "query";
            return new static;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }

    public static function insert($table){
        if(QUERY_BUILDER == 1){
            self::$index_counter = 0;
            self::$q = 'INSERT INTO '.$table.' (';
            self::$trans = "insert";
            return new static;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }

    public static function field($index,$value){
        self::$sets[$index] = $value;
        return new static;
    }
    
	public static function fetch($method = "",$array = []){
        $bt = debug_backtrace();
        self::$line = $bt[0]['line'];
		$response = self::get_list(self::$q,self::$line,self::$indexes);
        if($method != ""){
            if($method == "remove"){
                foreach ($array as $v) {
                    foreach ($response as $key => $value) {
                        if(isset($response[$key][$v])){
                            unset($response[$key][$v]);
                        }
                    }
                }
            }
        }
        self::$indexes = array();
        self::$index_counter = 0;
		return $response;
	}

    public static function execute(){
        $bt = debug_backtrace();
        self::$line = $bt[0]['line'];
        if(self::$trans == "delete"){
            $response = self::delete_query_execute(self::$q,self::$line,self::$indexes);
        }elseif(self::$trans == "insert"){
            $ind = '';
            $par = '';
            foreach (self::$sets as $key => $value) {
                $ind .=$key.',';
                $par .=':chknParam'.$key.',';
            }
            $ind = substr($ind,0,strlen($ind) - 1);
            $par = substr($par,0,strlen($par) - 1);
            self::$q .= $ind.') VALUES('.$par.')';
            $response = self::add_query_execute(self::$q,self::$line,self::$sets);
            self::$sets = array();
        }elseif(self::$trans == "update"){

            $set = '';
            foreach (self::$sets as $key => $value) {
                $set .=$key.'='.':chknvParam'.$key.',';
            }

            $set = substr($set,0,strlen($set) - 1);
            self::$q = str_replace('{SET_PARAMETER}', $set, self::$q);


            $response = self::update_query_execute(self::$q,self::$line,self::$sets,self::$indexes);
            self::$indexes = array();
            self::$index_counter = 0;
            self::$sets = array();
        }elseif(self::$trans == "query"){
            self::$q = str_replace('{',':chknParam',self::$q);
            self::$q = str_replace('}','',self::$q);
            $response = self::get_list(self::$q,self::$line,self::$indexes);
            self::$indexes = array();
            self::$index_counter = 0;
        }
        return $response;
    }

    public static function update($table){
        if(QUERY_BUILDER == 1){
        
            self::$index_counter = 0;
    		self::$q = 'UPDATE '.$table.' SET {SET_PARAMETER} ';
            self::$trans = "update";
            return new static;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
	}

    public static function truncate($table){
        if(QUERY_BUILDER == 1){
            $bt = debug_backtrace();
            self::$line = $bt[0]['line'];
            $response = self::truncate_exec($table,self::$line);
            return $response;
        }else{
            echo "QUERY_BUILDER is disabled";
            exit;
        }
    }
 
}