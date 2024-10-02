<?php

namespace App\Entity;

use App\Repository\UrlRepository;
use Doctrine\ORM\Mapping as ORM;
use DateInterval;

/**
 * @ORM\Entity(repositoryClass=UrlRepository::class)
 */
class Url
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $hash;

    /**
     * @ORM\Column(name="created_date", type="datetime_immutable")
     */
    private $createdDate;

    /**
     * @ORM\Column(name="end_date", type="datetime_immutable")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $is_logged;


    public function __construct()
    {
        $date = new \DateTimeImmutable();
        $this->setCreatedDate($date);
        $this->setIsLogged(0);
        $this->setEndDate($date->add(new DateInterval("P1D")));
        $this->setHash($date->format('YmdHis'));
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->createdDate;
    }

    public function setCreatedDate(\DateTimeImmutable $createdDate): self
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getIsLogged(): ?int
    {
        return $this->is_logged;
    }

    public function setIsLogged(int $is_logged): self
    {
        $this->is_logged = $is_logged;

        return $this;
    }
}
