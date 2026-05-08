<?php

namespace App\Entity;

use App\Repository\KudosRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KudosRepository::class)]
class Kudos
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $msgContent = null;

    #[ORM\Column]
    private ?\DateTime $dateTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMsgContent(): ?string
    {
        return $this->msgContent;
    }

    public function setMsgContent(string $msgContent): static
    {
        $this->msgContent = $msgContent;

        return $this;
    }

    public function getDateTime(): ?\DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTime $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }
}
