<?php

namespace Lib\Router;

use Router;
use Lib\Http\Request;


class Route
{

    /**  @var string */
    private $name;

    /** @var string */
    private $path;

    /**
     * Tableau contenant l'ensemble des paramètres
     * @var array
    */
    private $params = [];

    /** @var string */
    private $controller;

    /** @var string */
    private $method;

    /**
     * Tableau contenant l'ensemble des matches
     * @var array
    */
    private $matches = [];

    /** @var bool */
    public $admin; // Mettre en private et faire un getter


    /**
     * Constructeur de la classe Route
     *
     * @param [string] $name
     * @param [string] $path
     * @param [array] $params
     * @param [string] $controller
     * @param [string] $method
     * @param [bool] $admin
     */

    public function __construct($name, $path, $params, $controller, $method, $admin)
    {
        $this->name = $name;
        $this->path = $path;
        $this->params = $params;
        $this->controller = $controller;
        $this->method = $method;
        $this->admin = $admin;
    }


    /** Tester la correspondance entre la route et l'url composé */
    public function match($request): bool
    {
        /** Remplacement de l'expression régulière par l'appel d'une methode */
        $path = preg_replace_callback('#:([\w]+)#', [$this, "paramMatch"], $this->path);
        
        $pattern = "#^$path$#i"; // Le regex global, pour tester

        /** Tester si des éléments ont été paramétrés */
        if (!preg_match($pattern, $request, $matches)) {
            return false;
        }

        array_shift($matches);

        $this->matches = $matches;

        return true;
    }


    /** Faire matcher la bonne route */
    private function paramMatch(array $matches): string
    {
        if (isset($this->params[$matches[1]])) {
            return '(' . $this->params[$matches[1]] . ')';
        }
        return '([^/]+)';
    }


    /**
     * Appeler le controller et ses méthodes
     * @param request
    */
    public function call(Request $request)
    {
        /** Instanciation de la classe du controller */
        $controller = new $this->controller($request);

        /** Appel dynamique de la méthode du controller instancié */
        return call_user_func_array([$controller, $this->method], $this->matches);
    }
}
