<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Event;

class GeneralController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('general/index.html.twig', [
            'controller_name' => 'GeneralController',
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
    public function event($id)
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);

        $event = $repo->find($id);

        return $this->render('event.html.twig', [
            'controller_name' => 'GeneralController',
            'event' => $event
        ]);
    }

    /**
     * @Route("/creationEvenement", name="createEvent")
     */
    public function createEvents()
    {
        return $this->render('createEvents.html.twig', [
            'controller_name' => 'GeneralController',
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
