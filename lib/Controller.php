<?php

namespace Lib;

use Lib\Http\Request;
use Lib\Http\HtmlResponse;
use Src\Model\Model;
use Cocur\Slugify\Slugify;
use Lib\Http\RedirectResponse;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * Classe mère abstraite des controllers
 */

abstract class Controller
{
    protected $request;

    /**
     * Constructeur du Controller
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * Charger Twig avec le nom de la vue et des données
     * @param string $view
     * @param array $data
     */
    public function render($view, $data = [])
    {
        /** Où se trouver le dossier template (racine puis template) */
        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment($loader);
        
        /** Création de filtre pour gérer les slugs  */
        $filter = new TwigFilter('slug', function ($string) {
            $slugify = new Slugify();
            
            return $slugify->slugify($string);
        });

        /** Ajout de filtres possible */
        $twig->addFilter($filter);

        return new HtmlResponse(
            $twig->render($view, $data)
        );
    }

    /**
     * Gestion de la redirection
     * @param string $uri
     */
    public function redirect($uri)
    {
        return new RedirectResponse($uri);
    }
}
