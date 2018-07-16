<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class User
 * @package App\Entity
 * @ORM\Table(name="bookie_user")
 * @ORM\Entity()
 */
class User implements UserInterface
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
     * @ORM\Column(type="string", length=16, unique=true)
     * @Assert\NotBlank(groups={"create", "update"})
     * @Assert\Regex(
     *     pattern="/^[A-Za-z][A-Za-z0-9_. -]{2,15}$/",
     *     groups={"create", "update"}
     * )
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     * @Serializer\Groups({"user.sensitive"})
     * @Assert\NotBlank(groups={"create", "update"})
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     * @Serializer\Groups({"user.sensitive"})
     *
     * @var array
     */
    private $roles = [];

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bet", mappedBy="user")
     * @Serializer\Groups({"user.bets"})
     */
    private $bets;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @var int
     */
    private $points = 0;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @var int
     */
    private $pointsAlternative = 0;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    /**
     * @return string The password
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null The salt
     */
    public function getSalt()
    {
        return;
    }

    /**
     * @return string The username
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        return;
    }

    /**
     * @return mixed
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param mixed $username
     * @return User
     */
    public function setUsername(?string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $password
     * @return User
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles(): ?array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     * @return User
     */
    public function setRoles(array $roles): self
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }
        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function addRole(string $role): self
    {
        $value = strtoupper($role);
        $this->roles[$value] = $value;
        return $this;
    }

    /**
     * @param string $role
     * @return $this
     */
    public function removeRole(string $role): self
    {
        unset($this->roles[$role]);
        return $this;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return array
     */
    public function getBetsIds(): ?array
    {
        return array_map(function (Bet $bet) {
            return $bet->getId();
        }, $this->bets->toArray());
    }

    /**
     * @return mixed
     */
    public function getBets(): ?Collection
    {
        return $this->bets;
    }

    /**
     * @param Bet $bet
     * @return $this
     */
    public function addBet(Bet $bet): self
    {
        if (!$this->bets->contains($bet)) {
            $this->bets->add($bet);
            $bet->setUser($this);
        }
        return $this;
    }

    /**
     * @param Bet $bet
     * @return $this
     */
    public function removeBet(Bet $bet): self
    {
        if ($this->bets->contains($bet)) {
            $this->bets->removeElement($bet);
            if ($bet->getUser() === $this) {
                $bet->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoints(): ?int
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     * @return User
     */
    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPointsAlternative(): ?int
    {
        return $this->pointsAlternative;
    }

    /**
     * @param mixed $pointsAlternative
     * @return User
     */
    public function setPointsAlternative(int $pointsAlternative): self
    {
        $this->pointsAlternative = $pointsAlternative;
        return $this;
    }
}
