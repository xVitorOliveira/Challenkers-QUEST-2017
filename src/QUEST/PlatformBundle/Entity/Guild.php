<?php

namespace QUEST\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Guild
 *
 * @ORM\Table(name="guild")
 * @ORM\Entity(repositoryClass="QUEST\PlatformBundle\Repository\GuildRepository")
 */
class Guild
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="guildName", type="string", length=255, unique=true)
     */
    private $guildName;

    //n joueurs = 1 Guild
    /**
     * @ORM\OneToMany(targetEntity="QUEST\PlatformBundle\Entity\Player", mappedBy="guild")
     */
    private $players;

    //n Guilds = 1 Serveur
    /**
     * @ORM\ManyToOne(targetEntity="QUEST\PlatformBundle\Entity\Server", inversedBy="guilds")
     */
    private $server;

    public function __construct(String $name, Server $server)
    {
        $this->guildName = $name;
        $this->server = $server;
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

    public function setGuildName($guildName)
    {
        $this->guildName = $guildName;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getGuildName()
    {
        return $this->guildName;
    }
}
