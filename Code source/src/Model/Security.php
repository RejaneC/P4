<?php

namespace Src\Model;

use Lib\Model;

/**
 * Classe qui hérite de la classe mère Model
 * Gestion de la table user
 */

class Security extends Model
{

    /** Récupération de l'identifiant */
    public function user($username)
    {
        $security = new Security();

        /** Connexion à la BDD avec la méthode statique dbConnect */
        $db = static::dbConnect();

        $sql = "SELECT * FROM user WHERE username = :username";

        $req = $db->prepare($sql);
        
        $req->execute([
            "username" => $username
        ]);
        
        return $req->fetch();
    }
    

    /** Récupération du mot de passe */
    public function password($pass)
    {
        $security = new Security();

        $db = static::dbConnect();

        $sql = "SELECT * FROM user WHERE pass = :pass";

        $req = $db->prepare($sql);

        $req->execute([
            "pass" => $pass
        ]);
    }
}
