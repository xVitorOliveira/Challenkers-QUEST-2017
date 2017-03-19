<?php
namespace QUEST\PlatformBundle\Controller;
use QUEST\PlatformBundle\Entity\Classe;
use QUEST\PlatformBundle\Entity\Guild;
use QUEST\PlatformBundle\Entity\Player;
use QUEST\PlatformBundle\Entity\Server;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GameMasterController extends Controller
{

    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Server');
        $serverList = $repository->findAll();
        $content = $this
            ->get('templating')
            ->render('QUESTPlatformBundle:GameMaster:ListServerView.html.twig', array('servers' => $serverList));
        return new Response($content);
    }

    public function viewServerAction($id)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Guild');
        $guildList = $repository->findBy(
            array('server' => $id));

        $repository2 = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Server');
        $server = $repository2->findOneBy(
            array('id' => $id));

        $content = $this
            ->get('templating')
            ->render('QUESTPlatformBundle:GameMaster:ServerView.html.twig', array('guilds' => $guildList, 'server' => $server));
        return new Response($content);
    }

    public function viewGuildAction($serverId, $guildId)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Guild');
        $guild = $repository->findOneBy(
            array('id' => $guildId));

       $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Server');
        $server = $repository->findOneBy(
            array('id' => $serverId));

        //cVar = Classe Var
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Classe');
        $cKnight = $repository->findOneBy(
            array('classeName' => 'Knight'));
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Classe');
        $cHealer = $repository->findOneBy(
            array('classeName' => 'Healer'));
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Classe');
        $cMage = $repository->findOneBy(
            array('classeName' => 'Mage'));
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Classe');
        $cRogue = $repository->findOneBy(
            array('classeName' => 'Rogue'));

        //mVar = Moyenne Var
        $em = $this->getDoctrine()->getManager();
        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.guild = ?1";
        $moyenne = $em->createQuery($dql)->setParameter(1, $guildId)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.guild = ?1 AND e.playerclasse = ?2";
        $mKnight = $em->createQuery($dql)->setParameter(1, $guildId)->setParameter(2, $cKnight)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.guild = ?1 AND e.playerclasse = ?2";
        $mHealer = $em->createQuery($dql)->setParameter(1, $guildId)->setParameter(2, $cHealer)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.guild = ?1 AND e.playerclasse = ?2";
        $mMage= $em->createQuery($dql)->setParameter(1, $guildId)->setParameter(2, $cMage)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.guild = ?1 AND e.playerclasse = ?2";
        $mRogue = $em->createQuery($dql)->setParameter(1, $guildId)->setParameter(2, $cRogue)->getSingleScalarResult();

        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerLVL > :balance
                AND p.guild = :guild 
                ORDER BY p.playerLVL DESC')->setParameter('balance', $moyenne)->setParameter('guild', $guildId);
        $meritants = $query->getResult();

        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerLVL <= :balance
                AND p.guild = :guild
                ORDER BY p.playerLVL DESC')->setParameter('balance', $moyenne)->setParameter('guild', $guildId);
        $moinsMeritants = $query->getResult();

        $content = $this
            ->get('templating')
            ->render('QUESTPlatformBundle:GameMaster:GuildView.html.twig', array('mRogue' => $mRogue,'mMage' => $mMage,'mHealer' => $mHealer,
                'mKnight' => $mKnight,'moyenne' => $moyenne,'guild' => $guild, 'server' => $server, 'meritants' => $meritants,
                'moinsMeritants' => $moinsMeritants));
        return new Response($content);
    }

    public function addInfoBdAction()
    {
        $em = $this->getDoctrine()->getManager();

        $yg = new Server("Ygdrasil", "RP");
        $em->persist($yg);

        $knight = new Classe("Knight");
        $em->persist($knight);

        $mage = new Classe("Mage");
        $em->persist($mage);

        $healer = new Classe("Healer");
        $em->persist($healer);

        $rogue = new Classe("Rogue");
        $em->persist($rogue);

        $tg = new Guild("Thieves Guild", $yg);
        $em->persist($tg);

        $cp = new Guild("Companions", $yg);
        $em->persist($cp);

        $dh = new Guild("DarkBrotherHood", $yg);
        $em->persist( $dh);

        $em->flush();

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("TG_Knight_".$i, 10+$i, $knight, $tg);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("TG_Mage_".$i, 11+$i, $mage, $tg);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("TG_Healer_".$i, 12+$i, $healer, $tg);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("TG_Rogue_".$i, 13+$i, $rogue, $tg);
            $em->persist($player);
            $em->flush();
        }



        for ($i =0; $i<=5 ;$i++){
            $player = new Player("CP_Knight_".$i, 10+$i, $knight, $cp);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("CP_Mage_".$i, 11+$i, $mage, $cp);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("CP_Healer_".$i, 12+$i, $healer, $cp);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("CP_Rogue_".$i, 13+$i, $rogue, $cp);
            $em->persist($player);
            $em->flush();
        }


        for ($i =0; $i<=5 ;$i++){
            $player = new Player("DH_Knight_".$i, 10+$i, $knight, $dh);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("DH_Mage_".$i, 11+$i, $mage, $dh);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("DH_Healer_".$i, 12+$i, $healer, $dh);
            $em->persist($player);
            $em->flush();
        }

        for ($i =0; $i<=5 ;$i++){
            $player = new Player("DH_Rogue_".$i, 13+$i, $rogue, $dh);
            $em->persist($player);
            $em->flush();
        }

        $content = $this
            ->get('templating')
            ->render('QUESTPlatformBundle:GameMaster:ajoutReussie.html.twig');
        return new Response($content);

    }
















}
