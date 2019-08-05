<?php

namespace App\Controller;

use App\Entity\Description;
use App\Entity\Event;
use App\Entity\Images;
use App\Entity\FilesPdf;
use App\Form\FilesPdfType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class GeneralController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);

        $repo2 = $this->getDoctrine()->getRepository(Images::class);

        $repo3 = $this->getDoctrine()->getRepository(Description::class);

        $events = $repo->findFutureEvents();

        $images = $repo2->findAll();

        $descs = $repo3->findAll();

        return $this->render('/general/index.html.twig', [
            'controller_name' => 'GeneralController',
            'events' => $events,
            'images' => $images,
            'descs' => $descs
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
     * @Route("/admin/creationPdf", name="createPdf")
     */
    public function createPdf(Request $request,ObjectManager $manager)
    {
       
        $pdf = new FilesPdf();
        $form = $this->createForm(FilesPdfType::class, $pdf);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            
            $manager->persist($pdf);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Pdf envoyé .'
            );
        }

        return $this->render('/general/createPdf.html.twig', [
            'controller_name' => 'GeneralController',
            'formPdf' => $form->createView()

        ]);
    }

    /**
     * @Route("/lecentre", name="facilities")
     */
    public function facilities()
    {
        return $this->render('/general/facilities.html.twig', [
            'controller_name' => 'GeneralController',
        ]);
    } 
    
}
