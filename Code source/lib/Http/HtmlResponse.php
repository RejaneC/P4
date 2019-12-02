<?php

namespace Lib\Http;

/**
 * Classe qui hérite de la classe mère Response
 * Classe qui gère l'envoi de contenu de type html
 */
class HtmlResponse extends Response
{

    /**
     * @var [string]
     */
    protected $html;

    /**
     * Constructeur de HtmlResponse
     * @param [string] $html
     */
    public function __construct($html)
    {
        $this->html = $html;
    }

    /** Envoi de la réponse */
    public function send()
    {
        echo $this->html;
    }
}
