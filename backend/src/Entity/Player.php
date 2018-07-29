<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bookie_player")
 */
class Player implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"player.default"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=16, unique=true)
     *
     * @Groups({"player.default"})
     *
     * @Assert\NotBlank(message="Le login ne peut pas être vide")
     * @Assert\Length(
     *     min="3",
     *     minMessage="Le login doit faire au moins 3 caractères",
     *     max="16",
     *     maxMessage="Le login doit faire au plus 16 caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9_. -]+$/",
     *     message="Le login peut contenir des lettres non-accentuées, des chiffres, des espaces, ou les 3 caractères spéciaux suivants : _.-"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Za-z].+/",
     *     message="Le login doit commencer par une lettre non-accentuée"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     *
     * @Groups({"user.sensitive"})
     *
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide")
     * @Assert\Length(
     *     min="4",
     *     minMessage="Le mot de passe doit faire au moins 4 caractères",
     *     max="64",
     *     maxMessage="Le mot de passe doit faire au plus 64 caractères"
     * )
     * @Assert\Regex(
     *     pattern="/^[A-Za-z0-9!@#$%&_.-]+$/",
     *     message="Le mot de passe peut contenir des lettres non-accnetuées, des chiffres, ou les caractères spéciaux suivants : !@#$%&_.-"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     *
     * @Groups({"player.default"})
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bet", mappedBy="user")
     *
     * @Groups({"user.bets"})
     */
    private $bets;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Groups({"player.default"})
     *
     * @Assert\Type(type="integer")
     */
    private $points = 0;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getSalt(): void
    {
        return;
    }

    public function eraseCredentials(): void
    {
        return;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }

    public function addRole(string $role): self
    {
        $value = strtoupper($role);
        $this->roles[$value] = $value;
        return $this;
    }

    public function removeRole(string $role): self
    {
        unset($this->roles[$role]);
        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

    public function getBets(): ?Collection
    {
        return $this->bets;
    }

    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets->add($bet);
            $bet->setPlayer($this);
        }
        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        $this->bets->removeElement($bet);
        return $this;
    }

    public function getPoints(): ?int
    {
        return $this->points;
    }

    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }
}
