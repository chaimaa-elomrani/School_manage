<?php

namespace Core ; 

use PDO ; 
use PDOException ; 

class Db {

    // instanciation unique (Singleton)
    private static ?PDO $instance = null ; 

    // pour empêcher l'instanciation directe
    private function __construct(){}

    public static function connection() : PDO {

        if(self::$instance !== null){
            return self::$instance ;
        }

        $host = 'localhost';
        $dbname = 'school_manage' ; 
        $username = 'root'; 
        $password = '';


        try{

            // data source name 
            $dsn = "mysql:host= $host; dbname= $dbname ; charset=utf8mb4" ;

            // creation de l'objet pdo 
            self::$instance = new PDO($dsn , $username , $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION , // exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,// pas de conection persistante 
                PDO::ATTR_PERSISTENT => false   // pas de connection persistante
            ]); 
        }catch (PDOException $err){
            error_log($err->getMessage()); 
            die('erreur dans config/db , la connection avec la base de données');
        }
        return self::$instance; 
    }
}