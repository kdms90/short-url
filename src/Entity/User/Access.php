<?php

namespace App\Entity\User;

use App\Entity\AbstractFoundation;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Access
 * @package    App\Entity\User
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 *
 * @ORM\Table(name="app_user_access")
 * @ORM\Entity(repositoryClass="App\Repository\User\AccessRepository")
 * @UniqueEntity(fields="email")
 */
class Access extends AbstractFoundation implements UserInterface, Serializable, PasswordAuthenticatedUserInterface
{
    public $lastname;
    public $current_company_id;
    public $current_company_own_id;
    public $context_type         = '';
    public $current_company_type = CurrentWorkspace::ADMINISTRATOR;
    /**
     * @ORM\Column(type="string",name="email", unique=true)
     * @Assert\NotBlank()
     * @Assert\Email(message="email.invalid_format")
     * @Groups("item:read")
     */
    private $email;

    /**
     * @var string|null $token token unique permettant d'identifier également un utilisateur. Il est auto généré également
     *
     * @ORM\Column(type="string",name="token", nullable=true)
     * @Groups("item:read")
     */
    private $token;

    /**
     * @var string|null $confirmationToken Random string sent to the user email address in order to verify it.
     *
     * @ORM\Column(type="string",name="confirmation_token", nullable=true)
     * @Groups("item:read")
     */
    private $confirmationToken;

    /**
     * @var string|null $contextType Permet de savoir quel type de compte l'utilisateur souhaite crée
     *
     * @ORM\Column(type="string",name="context_type", nullable=true)
     * @Groups("item:read")
     */
    private $contextType;

    /**
     * @Assert\Length(max=250)
     */
    private $plainPassword;
    /**
     * The below length depends on the "algorithm" you use for encoding
     * the password, but this works well with bcrypt.
     *
     * @ORM\Column(type="string",name="password")
     */
    private $password;
    /**
     * @ORM\Column(name="is_active", type="boolean", nullable=true)
     */
    private $isActive = false;
    /**
     * @var boolean|null $hasCredentials permet de savoir si l'utilisateur a déjà ses identifiants. Très utile lorsqu'on initie le processus d'invitation
     * @ORM\Column(name="has_credentials", type="boolean", nullable=true, options={"default" : 1})
     */
    private $hasCredentials = true;
    /**
     * @var \App\Entity\User\Actor|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\User\Actor", mappedBy="access", cascade={"persist"})
     * @Assert\Valid()
     */
    private $actor;
    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles = [];

    /**
     * Access constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        return 'app.entity.user.access';
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
        return $this->getEmail();
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     *
     * @return \App\Entity\User\Access
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }

    /**
     * @return null|string
     */
    public function getSalt()
    {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param $password
     *
     * @return \App\Entity\User\Access
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        if (empty($this->roles)) {
            return ['ROLE_USER'];
        }
        return $this->roles;
    }

    public function eraseCredentials()
    {

    }

    /**
     * @param $role
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt,
        ]);
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->email,
            $this->password,
            $this->isActive,
            // see section on salt below
            // $this->salt
        ] = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $plainPassword
     *
     * @return \App\Entity\User\Access
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param $isActive
     *
     * @return \App\Entity\User\Access
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        if ($this->getActor())
            return $this->getActor()->getFullname();
        return '';
    }

    /**
     * @return \App\Entity\User\Actor
     */
    public function getActor(): ?Actor
    {
        return $this->actor;
    }

    /**
     * @param \App\Entity\User\Actor $actor
     *
     * @return Access
     */
    public function setActor(?Actor $actor): Access
    {
        $actor->setAccess($this);
        $this->actor = $actor;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstname()
    {
        if ($this->getActor())
            return $this->getActor()->getFirstname();
        return '';
    }

    /**
     * @return \App\Entity\User\Avatar|null
     * @Groups("item:read")
     */
    public function getAvatar()
    {
        if (!$this->getActor())
            return null;
        $consultant = $this->getActor()->getConsultant();
        if (!$consultant) {
            $recruiter = $this->getActor()->getRecruiter();
            if (!$recruiter) {
                $collaborator = $this->getActor()->getCollaborator();
                return null;
            } else return $recruiter->getThumbnail();
        }
        return $consultant->getThumbnail();
    }

    /**
     * @return \App\Entity\User\Avatar|null
     * @Groups("item:read")
     */
    public function getCover()
    {
        if (!$this->getActor())
            return null;
        $consultant = $this->getActor()->getConsultant();
        if (!$consultant) {
            $recruiter = $this->getActor()->getRecruiter();
            if (!$recruiter) {
                $collaborator = $this->getActor()->getCollaborator();
                return null;
            } else return $recruiter->getCover();
        }
        return $consultant->getCover();
    }

    /**
     * Permet de savoir s'il est propriétaire de la compagnie dans laquelle il est actuelle connectée
     *
     * @return bool
     */
    public function grantOwner()
    {
        return $this->getActor()->getId() === $this->current_company_own_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * {@inheritdoc}
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContextType(): ?string
    {
        return $this->contextType;
    }

    /**
     * @param string|null $contextType
     * @return Access
     */
    public function setContextType(?string $contextType): Access
    {
        $this->contextType = $contextType;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @param string|null $token
     * @return Access
     */
    public function setToken(?string $token): Access
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getHasCredentials(): ?bool
    {
        return $this->hasCredentials;
    }

    /**
     * @param bool|null $hasCredentials
     * @return Access
     */
    public function setHasCredentials(?bool $hasCredentials): Access
    {
        $this->hasCredentials = $hasCredentials;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }
}
