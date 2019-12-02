<?php

namespace Src\Controller;

use Lib\Router\Router;
use Lib\Http\HtmlResponse;
use Lib\Http\RedirectResponse;
use Lib\Controller;
use Lib\Model;
use Src\Model\Security;

/**
 * Classe qui hérite de la classe mère Controller
 * Classe du contrôleur qui gère l'authentification
 */

class SecurityController extends Controller
{

    /** Connexion au back-office */
    public function login()
    {   
        /**
         * @var array
         * Tableau vide d'erreur
         */
        $errors = [];

        if (
            $this->request->getMethod() === "POST"
            && isset($this->request->getPost()["username"])
            && isset($this->request->getPost()["password"])
        ) {
            $access = new Security();
            
            /** Récupération de l'identifiant dans $form_username*/
            $form_username = $this->request->getPost()["username"];
            $username = $access->user($form_username); /
                
            /** Récupération du mot de passe dans $form_password  */
            $form_password = $this->request->getPost()["password"];
            
            
            /** Gestion des erreurs lors de l'authentification */
            if ($username !== false) {
                if (password_verify($form_password, $username["password"])) { 

                    /** Création de la session */
                    $_SESSION["id"] = $username["id"];

                    return $this->redirect("/admin"); 
                    
                } else {
                    /** Erreur du mot de passe */
                    $errors = "Oups, mauvais mot de passe";
                }
            } else {
                /** Erreur d'identifiant */
                $errors = "Cet identifiant n'est pas le bon";
            }
        }
        return $this->render('security/connection.html.twig', [
            "errors" => $errors,
            "session" => $session
        ]);
    }
    

    /** Déconnexion au back-office */
    public function logout()
    {
        if (isset($_SESSION["id"])) {

            /** Destruction de la session en cours */
            session_destroy();
        }

        return $this->redirect("/connexion");
    }
}
