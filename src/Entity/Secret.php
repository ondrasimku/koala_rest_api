<?php

namespace App\Entity;

use App\Repository\SecretRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: SecretRepository::class)]
class Secret
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(["secret"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::BIGINT)]
    #[Groups(["secret"])]
    private ?string $secret_code = null;

    #[ORM\ManyToOne(inversedBy: 'secrets')]
    #[ORM\JoinColumn(name: 'nemesis_id', referencedColumnName: 'id', nullable: false)]
    #[Groups(["secret_nemesis"])]
    private ?Nemesis $nemesis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSecretCode(): ?string
    {
        return $this->secret_code;
    }

    public function setSecretCode(string $secret_code): static
    {
        $this->secret_code = $secret_code;

        return $this;
    }

    public function getNemesis(): ?Nemesis
    {
        return $this->nemesis;
    }

    public function setNemesis(?Nemesis $nemesis): static
    {
        $this->nemesis = $nemesis;

        return $this;
    }
}
