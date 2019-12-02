<?php

namespace Src\Model;

use Lib\Model;

/**
 * Classe qui hérite de la classe mère Model
 * Gestion de la table chapter
 */

class Chapter extends Model
{

    /**
     * FRONT
     */

    /** Récupérer la liste des chapitres */
    public function getChapters()
    {
        /** Connexion à la BDD avec la méthode statique dbConnect */
        $db = static::dbConnect();

        $sql = "SELECT * FROM chapter WHERE online = 1";

        $req = $db->query($sql);

        return $req->fetchAll();
    }

    /** Récupérer un chapitre */
    public function getChapter($id)
    {
        $db = static::dbConnect();

        $sql = "SELECT * FROM chapter WHERE id = :id";

        $req = $db->prepare($sql);
        $req->execute(["id" => $id]);

        return $req->fetch();
    }


    /** Récupérer le dernier chapitre publié */
    public function getLastChapter()
    {
        $db = static::dbConnect();

        $sql = "SELECT * FROM chapter WHERE online = 1 ORDER BY id DESC LIMIT 1";

        $req = $db->query($sql);

        return $req->fetch();
    }

    
    /** Récupérer les 4 derniers chapitres publiés */
    public function getRecentChapters()
    {
        $db = static::dbConnect();

        $sql = "SELECT * FROM chapter 
                WHERE online = 1 
                ORDER BY num_chap DESC LIMIT 0, 4";

        $req = $db->query($sql);

        return $req->fetchAll();
    }


    /**
    * BACK-OFFICE
    */

    /** Liste des chapitres dans l'admin */
    public function getChaptersAdmin()
    {
        $db = static::dbConnect();

        $sql = "SELECT * FROM chapter";

        $req = $db->query($sql);

        return $req->fetchAll();
    }


    /** Ecrire un nouveau chapitre */
    public function addChapter($title, $content, $num_chap)
    {
        $db = static::dbConnect();

        $sql = "INSERT INTO chapter(title, content, num_chap, online) 
                VALUES (:title, :content, :num_chap, 0)";

        $req = $db->prepare($sql);

        $req->execute([
            "title" => $title,
            "content" => $content,
            "num_chap" => $num_chap
        ]);
    }


    /** Modifier un chapitre */
    public function modify($title, $content, $num_chap, $id)
    {
        $db = static::dbConnect();

        $sql = "UPDATE chapter 
                SET title = :title, num_chap = :num_chap, content = :content 
                WHERE id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "title" => $title,
            "content" => $content,
            "num_chap" => $num_chap,
            "id" => $id
        ]);
    }


    /** Publier en ligne un chapitre enregistré en brouillon */
    public function publish($id)
    {
        $db = static::dbConnect();

        $sql = "UPDATE chapter SET online = 1 WHERE id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id
        ]);
    }


    /** Supprimer un chapitre */
    public function delete($id)
    {
        $db = static::dbConnect();

        $sql = "DELETE FROM chapter WHERE id = :id";

        $req = $db->prepare($sql);

        $req->execute([
            "id" => $id
        ]);
    }
}
