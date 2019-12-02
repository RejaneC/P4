<?php

namespace Src\Controller;

use Lib\Router\Router;
use Lib\Router\Route;
use Lib\Controller;
use Lib\Http\HtmlResponse;
use Src\Model\Model;
use Src\Model\Chapter;

/**
 * Classe qui hérite de la classe mère Controller
 * Classe du contrôleur qui gère les homepages du site, de l'admin et de la page about
 */

class HomeController extends Controller
{
    /** Affichage de la page d'accueil */
    public function index()
    {
        $chapterManager = new Chapter();
        $recentChapters = $chapterManager->getRecentChapters();

        return $this->render('homepage/index.html.twig', [
            "recentChapters" => $recentChapters,
            "session" => $_SESSION["id"] ?? null
        ]);
    }


    /** Affichage de la page d'accueil de l'admin */
    public function admin()
    {
        return $this->render('homepage/index-admin.html.twig');
    }


    /** Affichage de la page A propos */
    public function about()
    {
        return $this->render('about.html.twig', [
            "session" => $_SESSION["id"]
        ]);
    }
}
