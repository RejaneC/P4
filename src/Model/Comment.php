<?php

namespace Src\Model;

use Lib\Model;

/**
 * Classe qui hérite de la classe mère Model
 * Gestion de la table comment
 */

class Comment extends Model
{
    /**
     * FRONT
     */

    /** Récupérer et afficher les commentaires modérés sous un chapitre */
    public function getComments($chapter_id)
    {
        /** Connexion à la BDD avec la méthode statique dbConnect */
        $db = static::dbConnect();

        $sql = "SELECT * FROM comment WHERE validate = 1 AND chapter_id = ?";

        $req = $db->prepare($sql);
        $req->execute([$chapter_id]);
 
        return $req->fetchAll();
    }


    /** Ajout d'un commentaire */
    public function insert($content, $pseudo, $chapter_id)
    {
        $db = static::dbConnect();

        $sql = "INSERT INTO comment(content, pseudo, chapter_id, validate, moderate) 
                VALUES (:content, :pseudo, :chapter_id, 0, 0)";

        $req = $db->prepare($sql);

        $req->execute([
            "content" => $content,
            "pseudo" => $pseudo,
            "chapter_id" => $chapter_id
        ]);
    }


    /** Signaler un commentaire */
    public function warn($id)
    {
        $db = static::dbConnect();

        $sql = "UPDATE comment SET moderate = 1 WHERE id = :id";
        
        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id,
        ]);
    }


    /** Lier un commentaire à son chapitre */
    public function join($id)
    {
        $db = static::dbConnect();

        $sql = "SELECT chapter.* 
                FROM chapter 
                JOIN comment ON (comment.chapter_id = chapter.id) 
                WHERE comment.id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id,
        ]);
                
        return $req->fetch();
    }


    /**
     * BACK-OFFICE
     */

    
    /** Récupérer les commentaires à modérer dans l'admin */
    public function getCommentsAdmin()
    {
        $db = static::dbConnect();

        $sql = "SELECT * FROM comment WHERE validate = 0 OR moderate = 1";

        $req = $db->query($sql);

        return $req->fetchAll();
    }


    /** Valider un commentaire après sa soumission */
    public function validate($id)
    {
        $db = static::dbConnect();

        $sql = "UPDATE comment SET validate = 1 WHERE id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id
        ]);
    }

    /** Refuser un commentaire et le supprimer de la table */
    public function remove($id)
    {
        $db = static::dbConnect();

        $sql = "DELETE FROM comment WHERE id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id
        ]);
    }


    /** Réaccepter un commentaire après son signalement */
    public function accept($id)
    {
        $db = static::dbConnect();

        $sql = "UPDATE comment SET moderate = 0 WHERE id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id
        ]);
    }
}
