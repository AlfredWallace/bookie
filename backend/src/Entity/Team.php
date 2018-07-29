<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
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
     * @Groups({"team.default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     *
     * @Groups({"team.default"})
     *
     * @Assert\NotBlank(message="Le nom de l'équipe ne peut pas être vide")
     * @Assert\Length(
     *     min="3",
     *     minMessage="Le nom de l'équipe doit faire au moins 3 caractères",
     *     max="255",
     *     maxMessage="Le nom de l'équipe doit faire au plus 255 caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[\p{L} &.-]+$/",
     *     message="Le nom de l'équipe peut contenir des lettres, des espaces, ou les 3 caractères spéciaux suivants : &.-"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=6, unique=true)
     *
     * @Groups({"team.default"})
     *
     * @Assert\NotBlank(message="L'abbréviation de l'équipe ne peut pas être vide")
     * @Assert\Length(
     *     min="2",
     *     minMessage="L'abbréviation de l'équipe doit faire au moins 2 caractères",
     *     max="6",
     *     maxMessage="L'abbréviation de l'équipe doit faire au plus 6 caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Z]+$/",
     *     message="L'abbréviation de l'équipe ne peut contenir que des lettres majuscules non-accentuées"
     * )
     */
    private $abbreviation;

    public function __construct(string $name, string $abbreviation)
    {
        $this->name = $name;
        $this->abbreviation = $abbreviation;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getAbbreviation(): ?string
    {
        return $this->abbreviation;
    }

    public function setAbbreviation(string $abbreviation): self
    {
        $this->abbreviation = $abbreviation;
        return $this;
    }
}
