<?php

namespace Src\Controller;

use Lib\Router\Router;
use Lib\Router\Route;
use Lib\Controller;
use Lib\Model;
use Lib\Http\HtmlResponse;
use Lib\Http\RedirectResponse;
use Src\Model\Chapter;
use Src\Model\Comment;

/**
 * Classe qui hérite de la classe mère Controller
 * Classe du contrôleur qui gère les chapitres
 */

class ChapterController extends Controller
{

    /**
    * FRONT
    */

    /** Affichage de la liste de tous les chapitres */
    public function chapters()
    {
        $chapterManager = new Chapter();
        $chapters = $chapterManager->getChapters();

        return $this->render('chapter/chapters.html.twig', [
            "chapters" => $chapters,
            "session" => $_SESSION["id"],
        ]);
    }
 
    // public function getPostComment($request, $commentManager, $chapter, $response)
    // {
    //     if (
    //         $request->getMethod() === "POST"
    //         && isset($request->getPost()["content"])
    //         && !empty($request->getPost()["content"])
    //         && isset($request->getPost()["pseudo"])
    //         && !empty($request->getPost()["pseudo"])
    //     ) {
    //         /** Requête insertion */
    //         $commentManager->insert(
    //             $request->getPost()["content"],
    //             $request->getPost()["pseudo"],
    //             $chapter["id"]
    //         );
            
    //         /** Redirection */
    //         return $response;
    //     }
    // }

    /** Affichage de la page d'un chapitre  */
    public function chapter($num_chap, $title, $id)
    {
        /** Affichage du contenu du chapitre */
        $chapterManager = new Chapter();
        $chapter = $chapterManager->getChapter($id);

        /** Affichage des commentaires */
        $commentManager = new Comment();
        $comments = $commentManager->getComments($id);

        if (
            $this->request->getMethod() === "POST"
            && isset($this->request->getPost()["content"])
            && !empty($this->request->getPost()["content"])
            && isset($this->request->getPost()["pseudo"])
            && !empty($this->request->getPost()["pseudo"])
        ) {
            /** Requête insertion */
            $commentManager->insert(
                $this->request->getPost()["content"],
                $this->request->getPost()["pseudo"],
                $chapter["id"]
            );
            
            /** Redirection */
            return $this->redirect("/chapitre-" . $num_chap . "/" . $title . "/" . $id);
        }

        // $response = $this->redirect("/chapitre-" . $num_chap . "/" . $title . "/" . $id);
        // getPostComment($this->request, $commentManager, $chapter, $response);

        return $this->render('chapter/chapter.html.twig', [
            "chapter" => $chapter,
            "comments" => $comments,
            "session" => $_SESSION["id"],
        ]);
    }

    
    /** Affichage du dernier chapitre publié */
    public function lastChapter()
    {
        $chapterManager = new Chapter();
        $lastchapter = $chapterManager->getLastChapter();

        $commentManager = new Comment();
        $comments = $commentManager->getComments($lastchapter["id"]);

        if (
            $this->request->getMethod() === "POST"
            && isset($this->request->getPost()["content"])
            && !empty($this->request->getPost()["content"])
            && isset($this->request->getPost()["pseudo"])
            && !empty($this->request->getPost()["pseudo"])
        ) {
            /** Requête insertion */
            $commentManager->insert(
                $this->request->getPost()["content"],
                $this->request->getPost()["pseudo"],
                $lastchapter["id"]
            );
            
            /** Redirection */
            return $this->redirect("/dernier-chapitre");
        }

        // $response = $this->redirect("/dernier-chapitre");
        // getPostComment($this->request, $commentManager, $chapter, $response);

        return $this->render('chapter/lastChapter.html.twig', [
            "lastchapter" => $lastchapter,
            "comments" => $comments,
            "session" => $_SESSION["id"],
        ]);
    }


    /**
    * BACK-OFFICE
    */

    /** Affichage de la liste de tous les chapitres */
    public function list()
    {
        $chapterManager = new Chapter();
        $adminChapters = $chapterManager->getChaptersAdmin();

        return $this->render('chapter/chapters-admin.html.twig', [
            "adminChapters" => $adminChapters
        ]);
    }


    /** Ajout d'un nouveau chapitre */
    public function addChapter()
    {
        $chapter = new Chapter();

        if (
            $this->request->getMethod() === "POST" // On check la nature de la requête
            && isset($this->request->getPost()["chapter-title"]) // Name de l'input dans Tiwig
            && !empty($this->request->getPost()["chapter-title"]) // On vérifie qu'il y a bien des infos remplies dans l'input (pas vide) et qu'il est bien défini
            && isset($this->request->getPost()["chapter-content"])
            && !empty($this->request->getPost()["chapter-content"])
            && isset($this->request->getPost()["chapter-nb"])
            && !empty($this->request->getPost()["chapter-nb"])
        ) { // Si tout est rempli/défini etc
            /** Requêtes d'ajout du chapitre */
            $chapter->addChapter(
                $this->request->getPost()["chapter-title"], // On crée le chapitre en envoyant ce qu'il y a dans le formulaire (on envoie à la bdd les infos)
                $this->request->getPost()["chapter-content"],
                $this->request->getPost()["chapter-nb"]
            );

            /** Redirection */
            return $this->redirect("/admin/add-chapter");
        }
        
        return $this->render("chapter/addChapter.html.twig");
    }

    /** Modification d'un article */
    public function modify($id)
    {
        $chapterManager = new Chapter();
        $chapter = $chapterManager->getChapter($id);

        if (
            $this->request->getMethod() === "POST"
            && isset($this->request->getPost()["chapter-title"])
            && !empty($this->request->getPost()["chapter-title"])
            && isset($this->request->getPost()["chapter-content"])
            && !empty($this->request->getPost()["chapter-content"])
            && isset($this->request->getPost()["chapter-nb"])
            && !empty($this->request->getPost()["chapter-nb"])
        ) {
            /** Requêtes de modification du chapitre */
            $chapterManager->modify(
                $this->request->getPost()["chapter-title"],
                $this->request->getPost()["chapter-content"],
                $this->request->getPost()["chapter-nb"],
                $chapter["id"]
            );

            /** Redirection */
            return $this->redirect("/admin/chapter-management/" . $id . "/chapitre-" . $chapter["num_chap"]);
        }
        
        return $this->render("/chapter/chapter-admin.html.twig", [
            "chapter" => $chapter
        ]);
    }


    /** Publication d'un article en ligne */
    public function publish($id)
    {
        $chapter = new Chapter();

        $chapter->publish($id);
        $chapter = $chapter->getChapter($id);

        return $this->redirect("/admin/chapter-management/" . $id . "/chapitre-" . $chapter["num_chap"]);
    }


    /** Suppression d'un chapitre */
    public function delete($id)
    {
        $chapter = new Chapter();
        $chapter->delete($id);

        return $this->redirect("/admin/chapters-management");
    }
}
