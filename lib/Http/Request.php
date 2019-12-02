<?php

namespace Lib\Http;

/**
 * Classe qui récupère les requêtes
 */

class Request
{

    /** @var string */
    private $query;

    /** @var string */
    private $post;

    /** @var string */
    private $server;

    
    /**
     * Constructeur de la classe Request
     *
     * @param [string] $query
     * @param [string] $post
     * @param [string] $server
     */
    public function __construct($query, $post, $server)
    {
        $this->query = $query;
        $this->post = $post;
        $this->server = $server;
    }

    /** Récupération des éléments GET */
    public function getQuery()
    {
        return $this->query;
    }

    
    /** Récupération des éléments POST */
    public function getPost()
    {
        return $this->post;
    }

    
    /** $_SERVER */
    public function getServer()
    {
        return $this->server;
    }


    /** REQUEST_METHOD */
    public function getMethod()
    {
        /** Méthode de requête utilisée pour accéder à la page */
        return $this->server["REQUEST_METHOD"];
    }


    /** Récupération de l'uri */
    public function getUri()
    {
        if ($this->server["PATH_INFO"] === null) {
            $uri = $this->server["REQUEST_URI"] ?? "/";
        } else {
            $uri = $this->server["PATH_INFO"];
        }
        return $uri;
    }
}