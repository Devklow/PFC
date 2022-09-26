<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 19)]
    private ?string $name = null;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $Host = null;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $Player = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $PC = null;

    #[ORM\Column(type: Types::SMALLINT, nullable: true)]
    private ?int $HC = null;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $Winner = null;

    #[ORM\Column(nullable: true)]
    private ?int $HID = null;

    #[ORM\Column(nullable: true)]
    private ?int $PID = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Created = null;

    #[ORM\Column]
    private ?int $MaxRound = null;

    #[ORM\Column]
    private ?bool $Visibility = null;

    #[ORM\Column(nullable: true)]
    private ?int $CurrentRound = null;

    #[ORM\Column(nullable: true)]
    private ?bool $PlayerReady = null;

    #[ORM\Column(nullable: true)]
    private ?bool $HostReady = null;

    #[ORM\Column(nullable: true)]
    private ?int $HRV = null;

    #[ORM\Column(nullable: true)]
    private ?int $PRV = null;

    #[ORM\Column(length: 17, nullable: true)]
    private ?string $MatchWinner = null;

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

    public function getHost(): ?string
    {
        return $this->Host;
    }

    public function setHost(?string $Host): self
    {
        $this->Host = $Host;

        return $this;
    }

    public function getPlayer(): ?string
    {
        return $this->Player;
    }

    public function setPlayer(?string $Player): self
    {
        $this->Player = $Player;

        return $this;
    }

    public function getPC(): ?int
    {
        return $this->PC;
    }

    public function setPC(?int $PC): self
    {
        $this->PC = $PC;

        return $this;
    }

    public function getHC(): ?int
    {
        return $this->HC;
    }

    public function setHC(?int $HC): self
    {
        $this->HC = $HC;

        return $this;
    }

    public function getWinner(): ?string
    {
        return $this->Winner;
    }

    public function setWinner(?string $Winner): self
    {
        $this->Winner = $Winner;

        return $this;
    }

    public function getHID(): ?int
    {
        return $this->HID;
    }

    public function setHID(?int $HID): self
    {
        $this->HID = $HID;

        return $this;
    }

    public function getPID(): ?int
    {
        return $this->PID;
    }

    public function setPID(?int $PID): self
    {
        $this->PID = $PID;

        return $this;
    }

    public function isCreated(): ?bool
    {
        return $this->Created;
    }

    public function setCreated(?bool $Created): self
    {
        $this->Created = $Created;

        return $this;
    }

    public function getMaxRound(): ?int
    {
        return $this->MaxRound;
    }

    public function setMaxRound(int $MaxRound): self
    {
        $this->MaxRound = $MaxRound;

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->Visibility;
    }

    public function setVisibility(bool $Visibility): self
    {
        $this->Visibility = $Visibility;

        return $this;
    }

    public function getCurrentRound(): ?int
    {
        return $this->CurrentRound;
    }

    public function setCurrentRound(?int $CurrentRound): self
    {
        $this->CurrentRound = $CurrentRound;

        return $this;
    }

    public function isPlayerReady(): ?bool
    {
        return $this->PlayerReady;
    }

    public function setPlayerReady(?bool $PlayerReady): self
    {
        $this->PlayerReady = $PlayerReady;

        return $this;
    }

    public function isHostReady(): ?bool
    {
        return $this->HostReady;
    }

    public function setHostReady(?bool $HostReady): self
    {
        $this->HostReady = $HostReady;

        return $this;
    }

    public function getHRV(): ?int
    {
        return $this->HRV;
    }

    public function setHRV(?int $HRV): self
    {
        $this->HRV = $HRV;

        return $this;
    }

    public function getPRV(): ?int
    {
        return $this->PRV;
    }

    public function setPRV(?int $PRV): self
    {
        $this->PRV = $PRV;

        return $this;
    }

    public function getMatchWinner(): ?string
    {
        return $this->MatchWinner;
    }

    public function setMatchWinner(?string $MatchWinner): self
    {
        $this->MatchWinner = $MatchWinner;

        return $this;
    }
}
