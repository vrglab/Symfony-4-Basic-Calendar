<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Month;
use App\Repository\YearRepository;


/**
 * @ORM\Entity(repositoryClass=YearRepository::class)
 */
class Year
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
    private $year;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Month", mappedBy="year", cascade={"persist", "remove"})
     */
    private $months;

    public function __construct()
    {
        $this->months = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    public function getYear(): int
    {
        return $this->year;
    }


    public function setYear($year): void
    {
        $this->year = $year;
    }



    public function addMonth(Month $month): self
    {
        if (!$this->months->contains($month)) {
            $this->months[] = $month;
            $month->setYear($this);
        }
        return $this;
    }

    public function removeMonth(Month $month): self
    {
        if ($this->months->removeElement($month)) {
            if ($month->getYear() === $this) {
                $month->setYear(0);
            }
        }
        return $this;
    }
}