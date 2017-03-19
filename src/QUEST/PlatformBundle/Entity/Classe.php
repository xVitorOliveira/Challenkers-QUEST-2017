<?php

namespace QUEST\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Classe
 *
 * @ORM\Table(name="classe")
 * @ORM\Entity(repositoryClass="QUEST\PlatformBundle\Repository\ClasseRepository")
 */
class Classe
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="classeName", type="string", length=255, unique=true)
     */
    private $classeName;

    //n joueurs = 1 Classe
    /**
     * @ORM\OneToMany(targetEntity="QUEST\PlatformBundle\Entity\Player", mappedBy="classe")
     */
    private $players;

    public function __construct(String $name)
    {
        $this->classeName = $name;
        $this->players = new ArrayCollection();
    }

    public function addPlayer(Player $player)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->players[] = $player;
    }

    public function removePlayer(Player $player)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer le player en argument
        $this->players->removeElement($player);
    }

    public function getPlayers()
    {
        return $this->players;
    }

    public function getId()
    {
        return $this->id;
    }


    public function setClasseName($classeName)
    {
        $this->classeName = $classeName;

        return $this;
    }

    public function getClasseName()
    {
        return $this->classeName;
    }
}
