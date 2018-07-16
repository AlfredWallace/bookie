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
    const MONTHS = [
        'janvier',
        'février',
        'mars',
        'avril',
        'mai',
        'juin',
        'juillet',
        'août',
        'septembre',
        'octobre',
        'novembre',
        'décembre',
    ];
    const DAYS = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi',];

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    protected $isOver = false;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     *
     * @var \DateTime
     */
    protected $kickOff;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"match.teams"})
     *
     * @var Team
     */
    protected $homeTeam;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Team")
     * @ORM\JoinColumn(nullable=false)
     * @Serializer\Groups({"match.teams"})
     *
     * @var Team
     */
    protected $awayTeam;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0")
     *
     * @var int
     */
    protected $homeScore = 0;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Assert\GreaterThanOrEqual(value="0")
     *
     * @var int
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
    public function isOver(): ?bool
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
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @Serializer\VirtualProperty()
     *
     * @return null|string
     */
    public function getPrettyKickOff(): ?string
    {
        $kickOff = $this->kickOff;

        if ($kickOff !== null) {
            $weekDay = self::DAYS[(int)$kickOff->format('w')];
            $day = (int)$kickOff->format('j');
            $dayText = $day === 1 ? $day . 'er' : $day;
            $month = self::MONTHS[(int)$kickOff->format('n') - 1];
            $year = $kickOff->format('Y');
            return "$weekDay $dayText $month $year";
        }

        return null;
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
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getHomeTeamId(): ?int
    {
        return isset($this->homeTeam) ? $this->homeTeam->getId() : null;
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
     * @Serializer\VirtualProperty()
     *
     * @return int|null
     */
    public function getAwayTeamId(): ?int
    {
        return isset($this->awayTeam) ? $this->awayTeam->getId() : null;
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
    public function getHomeScore(): ?int
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
    public function getAwayScore(): ?int
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

    /**
     * @Serializer\VirtualProperty()
     *
     * @return bool
     */
    public function isToday(): ?bool
    {
        $kickOff = $this->kickOff;
        if ($kickOff === null) {
            return false;
        }

        $today = new \DateTime();
        return (int)$today->format('Y') === (int)$kickOff->format('Y')
            && (int)$today->format('n') === (int)$kickOff->format('n')
            && (int)$today->format('j') === (int)$kickOff->format('j');
    }
}
