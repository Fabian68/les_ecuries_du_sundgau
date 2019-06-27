<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Images;
use App\Form\EventType;
use App\Form\EventCreateType;
use App\Entity\DatesEvenements;
use App\Form\DatesEvenementsType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
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

        return $this->render('/general/index.html.twig', [
            'controller_name' => 'GeneralController',
            'events' => $recents
        ]);
    }

    /**
     * @Route("/tarif", name="prices")
     */
    public function prices()
    {
        return $this->render('/general/prices.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact()
    {   
        return $this->render('/general/contact.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    }

    /**
     * @Route("/localisation", name="localization")
     */
    public function localization()
    {
        return $this->render('/general/localization.html.twig', [
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

        return $this->render('/general/events.html.twig', [
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

        return $this->render('/general/event.html.twig', [
            'controller_name' => 'GeneralController',
            'event' => $event,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/creationEvenement", name="createEvent")
     * @Route("/evenement/{id}/edit", name="editEvent")
     */
    public function createEvents(Event $event = null, Request $request,ObjectManager $manager)
    {
        if(!$event) {
            $event = new Event();
        }

        $form = $this->createForm(EventCreateType::class, $event);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            var_dump($event->getDates());
                        
            foreach ($event->getDates() as $date) {
                $event->addDate($date);
                $date->setEvent($event);
                $manager->persist($date);
            }
            foreach ($event->getGalops() as $galop) {
                $event->addGalop($galop);
                $galop->addEvent($event);
            }
            echo('sfqfsfsfsdfsfsfsdfsdfsf \n \n \n fgsdgsdgsdgs');
            $image=new Images();
            $image->setUrl('voilamonurl');
            $manager->persist($image);
            $event->addImage($image);
            $manager->persist($event);
            $manager->flush();

            //return $this->redirectToRoute('events');
        }

        return $this->render('/general/createEvents.html.twig', [
            'controller_name' => 'GeneralController',
            'formEvent' => $form->createView()

        ]);
    }

    /**
     * @Route("/lecentre", name="facilities")
     */
    public function facilities()
    {
        return $this->render('/facilities.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    } 
    
     /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        return $this->render('/general/test.php', [
            'controller_name' => 'GeneralController',
        ]);
    }
}
