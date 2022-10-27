<?php

namespace App\Entity\Business;

use App\Entity\AbstractFoundation;
use App\Validator\UrlConstraint;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Url
 *
 * @ORM\Table(name="app_business_url")
 * @ORM\Entity(repositoryClass="App\Repository\Business\UrlRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Url extends AbstractFoundation
{
    /**
     * @var string $original url d'origine
     *
     * @ORM\Column(name="original", type="string", length=255, nullable=true)
     * @UrlConstraint()
     */
    private $original;

    /**
     * @var string $rewrited url reécrite
     *
     * @ORM\Column(name="rewrited", type="string", length=255, nullable=true)
     * @Assert\Length(max="255")
     */
    private $rewrited;
    /**
     * @var integer|null $views nombre de fois que le lien a été consulté
     *
     * @ORM\Column(name="views", type="integer", nullable=true, options={"default": 0})
     */
    private $views;

    /**
     * @var \App\Entity\User\Guest|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User\Guest", cascade={"persist"})
     * @Assert\Valid()
     */
    private $guest;

    /**
     * Return next sequence of entity code
     *
     * @return string
     */
    public static function getSequence()
    {
        // TODO: Implement getSequence() method.
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return $this->getOriginal();
    }

    /**
     * @inheritDoc
     */
    public function getLabel()
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function getOriginal(): ?string
    {
        return $this->original;
    }

    /**
     * @param string $original
     * @return Url
     */
    public function setOriginal(?string $original): Url
    {
        $this->original = $original;
        return $this;
    }

    /**
     * @return string
     */
    public function getRewrited(): ?string
    {
        return $this->rewrited;
    }

    /**
     * @param string $rewrited
     * @return Url
     */
    public function setRewrited(?string $rewrited): Url
    {
        $this->rewrited = $rewrited;
        return $this;
    }

    /**
     * @return \App\Entity\User\Guest|null
     */
    public function getGuest(): ?\App\Entity\User\Guest
    {
        return $this->guest;
    }

    /**
     * @param \App\Entity\User\Guest|null $guest
     * @return Url
     */
    public function setGuest(?\App\Entity\User\Guest $guest): Url
    {
        $this->guest = $guest;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getViews(): ?int
    {
        return (int)$this->views;
    }

    /**
     * @param int|null $views
     * @return Url
     */
    public function setViews(?int $views): Url
    {
        $this->views = $views;
        return $this;
    }

    /**
     * @return Url
     */
    public function increaseViews(): Url
    {
        $this->setViews($this->getViews() + 1);
        return $this;
    }
}
