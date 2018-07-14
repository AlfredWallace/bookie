<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Match
 * @package App\Entity\Match
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 * @ORM\Table(name="bookie_match")
 */
class Match
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isOver = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    protected $kickOff;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $awayTeam;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0")
     */
    protected $homeScore = 0;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0")
     */
    protected $awayScore = 0;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bet", mappedBy="match")
     * @Serializer\Groups({"match.bets"})
     */
    protected $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isOver(): bool
    {
        return $this->isOver;
    }

    /**
     * @param mixed $isOver
     * @return Match
     */
    public function setIsOver(bool $isOver): self
    {
        $this->isOver = $isOver;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKickOff(): ?\DateTime
    {
        return $this->kickOff;
    }

    /**
     * @param mixed $kickOff
     * @return Match
     */
    public function setKickOff(?\DateTime $kickOff): self
    {
        $this->kickOff = $kickOff;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    /**
     * @param mixed $homeTeam
     * @return Match
     */
    public function setHomeTeam(?Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    /**
     * @param mixed $awayTeam
     * @return Match
     */
    public function setAwayTeam(?Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHomeScore(): int
    {
        return $this->homeScore;
    }

    /**
     * @param mixed $homeScore
     * @return Match
     */
    public function setHomeScore(int $homeScore): self
    {
        $this->homeScore = $homeScore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAwayScore(): int
    {
        return $this->awayScore;
    }

    /**
     * @param mixed $awayScore
     * @return Match
     */
    public function setAwayScore(int $awayScore): self
    {
        $this->awayScore = $awayScore;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBets(): Collection
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
            $bet->setMatch($this);
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
        if ($bet->getMatch() === $this) {
            $bet->setMatch(null);
        }
    }
        return $this;
    }
}
