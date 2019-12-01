<?php

/** Démarrer une nouvelle session */
session_start();


require 'vendor/autoload.php';

use Lib\Router\Router;
use Src\Controller\HomeController;
use Src\Controller\ChapterController;
use Src\Controller\SecurityController;
use Src\Controller\AdminController;
use Src\Controller\CommentController;
use Src\Model\Model;
use Lib\Http\Request;
use Lib\Http\Response;
use Lib\Http\HtmlRespose;



/** Création d'une requête utilisateur */
$request = new Request($_GET, $_POST, $_SERVER);

/** Création du router */
$router = new Router($request);


/**
 * FRONT - Déclaration des routes
 */


 /** 
  * Création de la route "home" 
*/

$router->add(
    "home", 
    "/", 
    [], 
    HomeController::class, 
    "index", 
    false
);


/** 
 * Création des routes liées aux chapitres 
 */

$router->add(
    "chapitres", 
    "/chapitres", 
    [], 
    ChapterController::class, 
    "chapters", 
    false
);
    
$router->add(
    "chapitre", 
    "/chapitre-:num_chap/:title/:id", 
    ["num_chap" => "\d+", "id" => "\d+"], 
    ChapterController::class, 
    "chapter", 
    false
); 

$router->add(
    "dernier-chapitre", 
    "/dernier-chapitre", 
    [], 
    ChapterController::class, 
    "lastChapter", 
    false
);


/** 
 * Création de la route pour insérer un commentaire 
*/

$router->add(
    "insert-comment", 
    "/chapitre/:id/comment", 
    ["id" => "\d+"],
    CommentController::class, 
    "insert", 
    false
);


/** 
 * Création de la route de la page "about" 
*/

$router->add(
    "about", 
    "/a-propos", 
    [], 
    HomeController::class, 
    "about", 
    false
);


/** 
 * Création des routes de connexion et déconnexion 
 */

$connexion = $router->add(
    "connection", 
    "/connexion", 
    [], 
    SecurityController::class, 
    "login", 
    false
);

$router->add(
    "disconnection", 
    "/deconnexion", 
    [], 
    SecurityController::class, 
    "logout", 
    true
);



/**
 * BACK-OFFICE - Déclaration des routes
 */


/** 
 * Création de la route "home-admin" 
 */

$router->add(
    "home-admin", 
    "/admin", 
    [], 
    HomeController::class, 
    "admin", 
    true
);


/** 
 * Création des routes liées à la gestion des chapitres 
*/

$router->add(
    "add-chapter", 
    "/admin/add-chapter", 
    [], 
    ChapterController::class, 
    "addChapter", 
    true
); 

$router->add(
    "manage-chapters", 
    "/admin/chapters-management", 
    [], 
    ChapterController::class, 
    "list", 
    true
);

$router->add(
    "manage-chapter", 
    "/admin/chapter-management/:id/chapitre-:num_chap", 
    ["id" => "\d+", "num_chap" => "\d+"], 
    ChapterController::class, 
    "modify", 
    true
);

$router->add(
    "publish-chapter", 
    "/admin/publish-chapter/:id", 
    [], 
    ChapterController::class, 
    "publish", 
    true
); 

$router->add(
    "delete-chapter", 
    "/admin/delete-chapter/:id", 
    [], 
    ChapterController::class, 
    "delete", 
    true
);



/** 
 * Création des routes liées à la gestion des commentaires 
 */

$router->add(
    "comment", 
    "/admin/comment", 
    [], 
    CommentController::class, 
    "commentAdmin", 
    true
);

$router->add(
    "validate-comment", 
    "/admin/validate-comment/:id", 
    [], CommentController::class, 
    "validate", 
    true
); 

$router->add(
    "remove-comment", 
    "/admin/remove-comment/:id", 
    [], 
    CommentController::class, 
    "remove"
); 

$router->add(
    "warn-comment", 
    "/admin/warn-comment/:id", 
    [], 
    CommentController::class, 
    "warn", 
    true
); 

$router->add(
    "accept-comment", 
    "/admin/accept-comment/:id", 
    [], 
    CommentController::class, 
    "accept", 
    true
); 



/** Envoi de la response */
$response = $router->run();

/** Si la réponse est nulle, appel vers la page connexion */
if($response === null) {
    $response = $connexion->call($request);
}

/** Envoi du contenu de la response */
$response->send();
