<?php

namespace Lib\Http;

/**
 * Classe qui hérite de la classe mère Response
 * Classe qui gère les redirections
 */
class RedirectResponse extends Response
{

    /**
     * @var mixed
     */
    private $uri; // Pour savoir sur quelle url en l'envoie


    /**
     * Constructeur de RedirectResponse
     * @param [string] $uri
     */
    public function __construct($uri)
    {
        $this->uri = $uri;
    }

    /** Envoi de la redirection */
    public function send()
    {
        header("location: " . $this->uri);
    }
}
