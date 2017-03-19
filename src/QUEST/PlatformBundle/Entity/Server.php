<?php

namespace QUEST\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table(name="server")
 * @ORM\Entity(repositoryClass="QUEST\PlatformBundle\Repository\ServerRepository")
 */
class Server
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
     * @ORM\Column(name="serverName", type="string", unique=true)
     */
    private $serverName;

    /**
     * @var string
     *
     * @ORM\Column(name="serverType", type="string")
     */
    private $serverType;


    /**
     * @ORM\OneToMany(targetEntity="QUEST\PlatformBundle\Entity\Guild", mappedBy="server")
     */
    private $guilds;


    public function __construct($name, $type)
    {
        $this->serverName = $name;
        $this->serverType = $type;
        $this->guilds = new ArrayCollection();
    }

    public function addGuild(Guild $guild)
    {
        // Ici, on utilise l'ArrayCollection vraiment comme un tableau
        $this->guilds[] = $guild;
    }

    public function removeGuild(Guild $guild)
    {
        // Ici on utilise une méthode de l'ArrayCollection, pour supprimer la guild en argument
        $this->guilds->removeElement($guild);
    }

    public function setServerName($serverName)
    {
        $this->serverName = $serverName;

        return $this;
    }

    public function setServerType($serverType)
    {
        $this->serverType = $serverType;

        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getGuilds()
    {
        return $this->guilds;
    }

    public function getServerName()
    {
        return $this->serverName;
    }

    public function getServerType()
    {
        return $this->serverType;
    }
}
