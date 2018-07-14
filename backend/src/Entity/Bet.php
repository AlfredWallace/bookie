<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JMS\Serializer\Annotation as Serializer;

/**
 * Class Bet
 * @package App\Entity\Bet
 * @ORM\Entity()
 * @ORM\Table(name="bookie_bet", uniqueConstraints={
 *     @UniqueConstraint(name="user_match_idx", columns={"user_id", "match_id"})
 * })
 */
class Bet
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bets")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Match", inversedBy="bets")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $match;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $homeScore;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $awayScore;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $points = 0;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $pointsAlternative = 0;

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
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Bet
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMatch(): ?Match
    {
        return $this->match;
    }

    /**
     * @param mixed $match
     * @return Bet
     */
    public function setMatch(?Match $match): self
    {
        $this->match = $match;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    /**
     * @param mixed $homeScore
     * @return Bet
     */
    public function setHomeScore(?int $homeScore): self
    {
        $this->homeScore = $homeScore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    /**
     * @param mixed $awayScore
     * @return Bet
     */
    public function setAwayScore(?int $awayScore): self
    {
        $this->awayScore = $awayScore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPoints(): int
    {
        return $this->points;
    }

    /**
     * @param mixed $points
     * @return Bet
     */
    public function setPoints(int $points): self
    {
        $this->points = $points;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPointsAlternative(): int
    {
        return $this->pointsAlternative;
    }

    /**
     * @param mixed $pointsAlternative
     * @return Bet
     */
    public function setPointsAlternative(int $pointsAlternative): self
    {
        $this->pointsAlternative = $pointsAlternative;
        return $this;
    }
}
