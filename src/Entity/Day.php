<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DayRepository;

/**
 * @ORM\Entity(repositoryClass=DayRepository::class)
 */
class Day
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Month", inversedBy="days")
     * @ORM\JoinColumn(nullable=false)
     */
    private $month;

    /**
     * @ORM\Column(type="boolean")
     */
    private $weekendDay;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setDate(int $date): void
    {
        $this->date = $date;
    }

    public function getDate(): ?int
    {
        return $this->date;
    }

    public function getMonth(): ?Month
    {
        return $this->month;
    }

    public function setMonth(Month $month): void
    {
        $this->month = $month;
    }


    public function getWeekendDay(): ?bool
    {
        return $this->weekendDay;
    }

    public function setWeekendDay($weekendDay): void
    {
        $this->weekendDay = $weekendDay;
    }

}