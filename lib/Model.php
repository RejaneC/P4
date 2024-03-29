<?php

namespace Lib;

use PDO;

/**
 * Classe mère qui gère la connexion à la BDD
 */

class Model
{

    /**
     * Propriété statique de $pdo
     */
    protected static $pdo;

    /** Fonction statique de connexion à la BDD */
    public static function dbConnect()
    {
        try {
            /** Si $pdo est nulle, alors on l'instancie */
            if (static::$pdo === null) {
                $db_local = "mysql:host=localhost:8889;dbname=blog_writer;charset=utf8mb4";

                /** Création de l'instance PDO dans la variable $pdo */
                static::$pdo = new PDO($db_local, "root", "root");
                
                static::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            }

            /** Return de l'objet $pdo */
            return static::$pdo;
        
            /** Echec de la connexion à la BDD */
        } catch (PDOException $e) {
            echo 'Connection failed : ' . $e->getMessage();
        }
    }
}
