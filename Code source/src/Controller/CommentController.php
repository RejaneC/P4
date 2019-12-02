<?php

namespace Src\Controller;

use Lib\Router\Router;
use Lib\Router\Route;
use Lib\Controller;
use Lib\Model ;
use Src\Model\Comment;

/**
 * Classe qui hérite de la classe mère Controller
 * Classe du contrôleur qui gère les commentaires
 */

class CommentController extends Controller
{

    /**
    * FRONT
    */

    /** Signaler un commentaire */
    public function warn($id)
    {
        $comment = new Comment();

        $comment->warn($id);
        $chapter = $comment->join($id);

        return $this->redirect("/chapitre-" . $chapter["num_chap"] . "/" . $chapter["title"] . "/" . $chapter["id"]);
    }


    /**
    * BACK-OFFICE
    */

    /** Liste des commentaires à modérer */
    public function commentAdmin()
    {
        $commentManager = new Comment();

        $commentAdmin = $commentManager->getCommentsAdmin();

        return $this->render('comment/comment-admin.html.twig', [
            "commentAdmin" => $commentAdmin
        ]);
    }


    /** Validation d'un commentaire ajouté */
    public function validate($id)
    {
        $comment = new Comment();
        $comment->validate($id);

        return $this->redirect("/admin/comment");
    }


    /** Suppression d'un commentaire ajouté ou signalé */
    public function remove($id)
    {
        $comment = new Comment();
        $comment->remove($id);

        return $this->redirect("/admin/comment");
    }

    
    /** Acceptation d'un commentaire signalé */
    public function accept($id)
    {
        $comment = new Comment();
        $comment->accept($id);

        return $this->redirect("/admin/comment");
    }
}
