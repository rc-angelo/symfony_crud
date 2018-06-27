<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TodoRepository")
 */
class Todo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $details;

    /**
     * @ORM\Column(type="integer")
     */
    private $label;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDetails()
    {
        return $this->details;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getDate()
    {
        if($this->date instanceof \DateTime){
            return $this->date->format('Y-m-d');
        }else{
            return '';
        }
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setDetails($details)
    {
        $this->details = $details;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
    /**
     * @param \DateTime|null $date
     */
    public function setDate($value)
    {
        $this->date = $value;
    }
}
