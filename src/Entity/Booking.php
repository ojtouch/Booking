<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date (message="Attention, la date d'arrivée n'est pas au bon format")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être ultérieur à la date d'aujourd'hui")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date (message="Attention, la date d'arrivée n'est pas au bon format")
     * @Assert\GreaterThan(propertyPath="startDate", message="Attention, la date de départ doit être superieur à la date d'arrivée")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Callback appelé à chaque fois qu'on crée une reservation.
     *
     * @ORM\PrePersist
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amount)) {
            //prix de l'annonce * nombre de jour du séjour
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);

        return $diff->days;
    }

    /**
     * Vérifier si les jours choisis sont "bookable".
     *
     * @return bool
     */
    public function isBookableDates()
    {
        //Récupérer les jours déjà booké
        $notAvailableDays = $this->ad->getNotAvailableDays();

        //Récupérer les jours choisis de la réservation
        $bookingDays = $this->getDays();

        //Tableau des chaines de caractères des journées choisies pour la réservation
        $days = array_map(function ($day) {
            return $day->format('Y-m-d');
        }, $bookingDays);

        //Tableau des chaines de caractères des journées déjà bookées
        $notAvailable = array_map(function ($day) {
            return $day->format('Y-m-d');
        }, $notAvailableDays);

        foreach ($days as $day) {
            if (array_search($day, $notAvailable) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Récuperer un tableau des journnées qui correspondent à la réservation.
     *
     * @return array d'objet DateTime représentant les jours de la réservation
     */
    public function getDays()
    {
        $resultat = range(
            $this->getStartDate()->getTimestamp(),
                $this->getEndDate()->getTimestamp(),
                24 * 60 * 60
        );

        $days = array_map(function ($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $resultat);

        return $days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
