<?php

namespace QUEST\PlatformBundle\Entity;

use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\Mapping as ORM;

/**
 * Player
 *
 * @ORM\Table(name="player")
 * @ORM\Entity(repositoryClass="QUEST\PlatformBundle\Repository\PlayerRepository")
 */
class Player
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
     * @ORM\Column(name="playerName", type="string", length=255, unique=true)
     */
    private $playerName;

    /**
     * @var int
     *
     * @ORM\Column(name="playerLVL", type="integer")
     */
    private $playerLVL;

    //n joueurs = 1 Classe
    /**
     * @ORM\ManyToOne(targetEntity="QUEST\PlatformBundle\Entity\Guild", inversedBy="players")
     */
    private $guild;

    //n joueurs = 1 Guild
    /**
     * @ORM\ManyToOne(targetEntity="QUEST\PlatformBundle\Entity\Classe", inversedBy="players")
     */
    private $playerclasse;

    public function __construct(String $name, $lvl, Classe $classe, Guild $guild)
    {
        $this->playerName = $name;
        $this->playerLVL = $lvl;
        $this->guild = $guild;
        $this->playerclasse = $classe;
    }

    public function setPlayerLVL($playerLVL)
    {
        $this->playerLVL = $playerLVL;

        return $this;
    }

    public function setPlayerName($playerName)
    {
        $this->playerName = $playerName;

        return $this;
    }

    public function getClasse(){
        return $this->playerclasse->getClasseName();
    }

    public function getGuild(){
        return $this->guild;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getPlayerName()
    {
        return $this->playerName;
    }


    public function getPlayerLVL()
    {
        return $this->playerLVL;
    }
}
