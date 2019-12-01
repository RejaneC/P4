<?php

namespace Lib\Http;

/**
 * Classe abstraite de gestion des réponses
 * Classe non instanciable
 */
abstract class Response
{
    /** Envoi de la response */
    abstract public function send();
}
