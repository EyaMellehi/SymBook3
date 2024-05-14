<?php

namespace App\Entity;

use App\Repository\OrdersDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersDetailsRepository::class)]
class OrdersDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 0)]
    private ?string $prix = null;

    #[ORM\ManyToOne(inversedBy: 'ordersDetails')]
    private ?Commande $orders = null;

    #[ORM\ManyToOne(inversedBy: 'ordersDetails')]
    private ?Livres $livres = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getOrders(): ?Commande
    {
        return $this->orders;
    }

    public function setOrders(?Commande $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    public function getLivres(): ?Livres
    {
        return $this->livres;
    }

    public function setLivres(?Livres $livres): static
    {
        $this->livres = $livres;

        return $this;
    }
}
