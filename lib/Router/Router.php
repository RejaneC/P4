<?php

namespace Lib\Router;

use Lib\Http\Request;
use Lib\Http\HtmlResponse;
use Lib\Http\RedirectResponse;
use Lib\Controller;

/**
 * Classe qui hérite de la classe Controller
 * ??
 */
class Router
{
    /**
     * Contient l'uri sur laquelle on souhaite se rendre
     * @var request
     */
    protected $request;

    /**
     * Tableau contenant l'ensemble des objets routes
     * @var Route[]
     */
    private $routes = [];


    /**
     * Constructeur de la classe Router
     * @param request $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }


    /**
     * Création de routes
     *
     * @param string $name
     * @param string $path
     * @param array $params
     * @param string $controller
     * @param string $method
     * @param bool $admin
     */

    public function add(string $name, string $path, array $params, string $controller, string $method, bool $admin = false): Route
    {

        /** Création d'une nouvelle route avec le $name comme index, dans le tableau de route */
        $this->routes[$name] = new Route($name, $path, $params, $controller, $method, $admin);

        /** @return Route qui vient d'être créée */
        return $this->routes[$name];
    }


    /** Vérifier si la route matche avec l'uri */
    public function run()
    {
        foreach ($this->routes as $route) {
            if ($route->match($this->request->getUri())) {

                /**
                 * Restriction de l'accès aux uri du back-office
                 *
                 * Vérifier si c'est une route de l'admin,
                 * qu'il y a une session
                 * ou que ce n'est pas une route admin
                 */
                if (($route->admin && isset($_SESSION["id"])) || !$route->admin) {
                    // On fait le call
                    return $route->call($this->request);
                }
            }
        }
        // S'il n'a pas trouvé la route, @return null
        return null;
    }      
}
