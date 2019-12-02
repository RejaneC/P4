<?php

namespace Lib\Http;

/**
 * Classe qui hérite de la classe mère Response
 * Classe qui gère l'envoi de contenu de type JSON
 */
class JsonRespnse extends Response
{

    /**
     * @var mixed
     */
    private $data;


    /**
     * Constructeur de JsonRespnse
     * @param mixed $data
     */
    public function __construct($data)
    {
        $this->date = $data;
    }

    
    public function send()
    {
        /** Renvoi du json pour l'encoder */
        echo json_encode($this->data);
    }
}
