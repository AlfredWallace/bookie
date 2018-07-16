<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Team
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(name="bookie_team")
 */
class Team
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Regex(
     *     pattern="/^[A-Za-z. -]{3,255}$/",
     *     groups={"create", "update"}
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=6, unique=true)
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Regex(
     *     pattern="/^[A-Z]{2,6}$/",
     *     groups={"create", "update"}
     * )
     *
     * @var string
     */
    private $abbreviation;

    public function __construct(string $name, string $abbreviation)
    {
        $this->name = $name;
        $this->abbreviation = $abbreviation;
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return Team
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    /**
     * @param mixed $abbreviation
     * @return Team
     */
    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }
}
