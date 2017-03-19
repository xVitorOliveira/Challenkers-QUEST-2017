<?php
/**
 * Created by PhpStorm.
 * User: vitor
 * Date: 19/03/2017
 * Time: 12:32
 */

namespace QUEST\PlatformBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GlobalOverViewController extends Controller
{

    public function serverViewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('QUESTPlatformBundle:Server');
        $server = $repository->findOneBy(
            array('id' => $id));

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

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e ";
        $moyenne = $em->createQuery($dql)->getSingleScalarResult();

        //MOYENNE

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.playerclasse = ?1";
        $mKnight = $em->createQuery($dql)->setParameter(1, $cKnight)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.playerclasse = ?1";
        $mHealer = $em->createQuery($dql)->setParameter(1, $cHealer)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.playerclasse = ?1";
        $mMage= $em->createQuery($dql)->setParameter(1, $cMage)->getSingleScalarResult();

        $dql = "SELECT AVG(e.playerLVL) FROM QUESTPlatformBundle:Player e " . "WHERE e.playerclasse = ?1";
        $mRogue = $em->createQuery($dql)->setParameter(1, $cRogue)->getSingleScalarResult();

        //TOP 5

        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerclasse = :knight
                ORDER BY p.playerLVL DESC')->setParameter('knight', $cKnight)->setMaxResults(5);
        $tKnight = $query->getResult();

        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerclasse = :healer
                ORDER BY p.playerLVL DESC')->setParameter('healer', $cHealer)->setMaxResults(5);
        $tHealer = $query->getResult();

        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerclasse = :mage
                ORDER BY p.playerLVL DESC')->setParameter('mage', $cMage)->setMaxResults(5);
        $tMage = $query->getResult();

        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerclasse = :rogue
                ORDER BY p.playerLVL DESC')->setParameter('rogue', $cRogue)->setMaxResults(5);
        $tRogue= $query->getResult();


        $query = $em->createQuery(
            'SELECT p
                FROM QUESTPlatformBundle:Player p
                WHERE p.playerLVL > :balance
                ORDER BY p.playerLVL DESC')->setParameter('balance', $moyenne)->setMaxResults(13);
        $meritants = $query->getResult();

        $content = $this
            ->get('templating')
            ->render('QUESTPlatformBundle:GlobalOverView:GlobalServerView.html.twig', array('tRogue' => $tRogue,'tMage' => $tMage,'tHealer' => $tHealer,
                'tKnight' => $tKnight,'mRogue' => $mRogue,'mMage' => $mMage,'mHealer' => $mHealer,
                'mKnight' => $mKnight,'server' => $server,'meritants' => $meritants,'moyenne' => $moyenne));
        return new Response($content);
    }

}