<?php
namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MonthRepository;
use App\Entity\Day;

/**
 * @ORM\Entity(repositoryClass=MonthRepository::class)
 */
class Month
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    private $month;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Day", mappedBy="month", cascade={"persist", "remove"})
     */
    private $days;

    public function __construct()
    {
        $this->days = new ArrayCollection();
    }


    public function getId(): int
    {
        return $this->id;
    }

    /** @return Collection */
    public function getDays(): Collection
    {
        return $this->days;
    }


    public function getMonth(): int
    {
        return $this->month;
    }


    public function setMonth($month): void
    {
        $this->month = $month;
    }



    public function addDay(Day $day): self
    {
        if (!$this->days->contains($day)) {
            $this->days[] = $day;
            $day->setMonth($this);
        }
        return $this;
    }

    public function removePost(Day $day): self
    {
        if ($this->days->removeElement($day)) {
            if ($day->getMonth() === $this) {
                $day->setMonth(0);
            }
        }
        return $this;
    }
}