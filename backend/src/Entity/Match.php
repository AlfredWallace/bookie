<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MatchRepository")
 * @ORM\Table(name="bookie_match")
 */
class Match
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Groups({"match.default"})
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Groups({"match.default"})
     */
    protected $isOver = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Groups({"match.default"})
     *
     * @Assert\DateTime(message="Le coup d'envoi doit être une date au format AAAA-MM-JJ H:m:s")
     */
    protected $kickOff;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups({"match.teams"})
     */
    protected $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=true)
     *
     * @Groups({"match.teams"})
     */
    protected $awayTeam;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Groups({"match.default"})
     *
     * @Assert\GreaterThanOrEqual(
     *     value="0",
     *     message="Les scores doivent être des entiers positifs ou nuls"
     * )
     */
    protected $homeScore;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     *
     * @Groups({"match.default"})
     *
     * @Assert\GreaterThanOrEqual(
     *     value="0",
     *     message="Les scores doivent être des entiers positifs ou nuls"
     * )
     */
    protected $awayScore;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bet", mappedBy="match")
     *
     * @Groups({"match.bets"})
     */
    protected $bets;

    public function __construct()
    {
        $this->bets = new ArrayCollection();
    }

    public function isOver(): ?bool
    {
        return $this->isOver;
    }

    public function setIsOver(bool $isOver): self
    {
        $this->isOver = $isOver;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKickOff(): ?\DateTime
    {
        return $this->kickOff;
    }

    public function setKickOff(?\DateTime $kickOff): self
    {
        $this->kickOff = $kickOff;
        return $this;
    }

    public function getHomeTeam(): ?Team
    {
        return $this->homeTeam;
    }

    public function setHomeTeam(?Team $homeTeam): self
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    public function getAwayTeam(): ?Team
    {
        return $this->awayTeam;
    }

    public function setAwayTeam(?Team $awayTeam): self
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    public function getHomeScore(): ?int
    {
        return $this->homeScore;
    }

    public function setHomeScore(int $homeScore): self
    {
        $this->homeScore = $homeScore;
        return $this;
    }

    public function getAwayScore(): ?int
    {
        return $this->awayScore;
    }

    public function setAwayScore(int $awayScore): self
    {
        $this->awayScore = $awayScore;
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
            $bet->setMatch($this);
        }
        return $this;
    }

    public function removeBet(Bet $bet): self
    {
        $this->bets->removeElement($bet);
        return $this;
    }
}
