<?php

namespace App\Entity\User;

use App\Entity\AbstractFoundation;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Guest
 * @package App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_guest")
 * @ORM\Entity(repositoryClass="App\Repository\User\GuestRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Guest extends AbstractFoundation
{
    /**
     * @return string
     */
    public static function getSequence()
    {
        return 'app.entity.user.guest';
    }

    /**
     * Ce permet d'être toujours présent dans le résultat d'une api.
     * Cela permet l'autocomplétion dans une liste déroulante
     *
     * @return string
     * @Groups("item:read")
     */
    public function getLabel()
    {
        return $this->getId();
    }
}

