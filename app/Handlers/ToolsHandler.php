<?php

namespace App\Handlers;

use Illuminate\Support\Facades\Config;

/**
 * Cette classe regroupe diverses fonctions outils qui sont utilisées dans divers autres classes.
 *
 * Class ToolsHandler
 * @package App\Handlers
 */
class ToolsHandler
{
    /**
     * Cette fonction retourne un token avec le nombre de caractères renseigné en paramètre.
     *
     * @param $nb_character integer
     * @return string
     */
    static function makeToken
    (
        $nb_character
    )
    {
        return bin2hex(random_bytes($nb_character / 2));
    }

    /**
     * Obtient l'url de base renseignées dans le .env.
     *
     * @return mixed
     */
    static function getBaseUrl()
    {
        return Config::get('constants.base_url');
    }

}