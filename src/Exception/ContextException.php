<?php

namespace App\Exception;

/**
 * Check si l'utilisateur a le droit d'accéder à une ressource de l'application
 *
 * Class ContextException
 * @package DB\CoreBundle\Exception
 */
class ContextException extends AppException
{
    const ACCESS_FOR_SENT_REQUEST_REQUIRED       = 1;//Possibilité de faire les demande des service
    const ACCESS_FOR_OFFERS_FOR_REQUEST_REQUIRED = 2;//Peut sélectionner une offre parmis celle envoyées par ceux qui ont postulés#}
}