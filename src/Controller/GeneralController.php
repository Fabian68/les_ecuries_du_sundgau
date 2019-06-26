<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Event;
use App\Entity\DatesEvenements;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\EventType;
use App\Form\DatesEvenementsType;
use App\Form\EventCreateType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);

        $recents = array();
        $events = $repo->findAll();
        $iter = 0;
        foreach($events as $event){
            if($iter<5){
                array_push($recents, $event);
            }
            $iter++;
        }

        return $this->render('general/index.html.twig', [
            'controller_name' => 'GeneralController',
            'events' => $recents
        ]);
    }

    /**
     * @Route("/tarif", name="prices")
     */
    public function prices()
    {
        return $this->render('prices.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {   
        return $this->render('contact.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    }

    /**
     * @Route("/localisation", name="localization")
     */
    public function localization()
    {
        return $this->render('localization.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    }

    /**
     * @Route("/evenements", name="events")
     */
    public function events()
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);

        $events = $repo->findAll();

        return $this->render('events.html.twig', [
            'controller_name' => 'GeneralController',
            'events' => $events
        ]);
    }

    /**
     * @Route("/evenement/{id}", name="event")
     */
    public function event($id,Request $request,ObjectManager $manager)
    {
        
       // $form->handleRequest($request);
       $form = $this->createFormBuilder()
       ->add('save', SubmitType::class, ['label' => 'S\'enregistrer.'])
       ->getForm();


        $repo = $this->getDoctrine()->getRepository(Event::class);

        $event = $repo->find($id);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user=$this->getUser();
            $event->addUtilisateur($user);
            $user->addParticipe($event);
            $manager->flush();
            return $this->redirectToRoute('events');
        }

        return $this->render('event.html.twig', [
            'controller_name' => 'GeneralController',
            'event' => $event,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/creationEvenement", name="createEvent")
     */
    public function createEvents(Request $request,ObjectManager $manager)
    {
        $event = new Event();

        $form = $this->createForm(EventCreateType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($event->getDates());
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event');
        }

        return $this->render('createEvents.html.twig', [
            'controller_name' => 'GeneralController',
            'formEvent' => $form->createView()
        ]);
    }

    /**
     * @Route("/lecentre", name="facilities")
     */
    public function facilities()
    {
        return $this->render('facilities.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    } 
    
     /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->render('test.php', [
            'controller_name' => 'GeneralController',
        ]);
    }
}
