<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Repas;
use App\Entity\Images;
use App\Form\EventType;
use App\Form\RepasType;
use App\Entity\FilesPdf;
use App\Form\ImagesType;
use App\Form\BenevoleType;
use App\Form\FilesPdfType;
use App\Form\AssoEventType;
use App\Form\ParticipeType;
use App\Form\EventCreateType;
use App\Entity\DatesEvenements;
use App\Form\DatesEvenementsType;
use App\Entity\AttributMoyenPaiements;
use App\Form\EventRegistrationTreatmentType;
use App\Entity\UtilisateurMoyenPaiementEvent;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

        $repo2 = $this->getDoctrine()->getRepository(Images::class);

        $events = $repo->findFutureEvents();

        $images = $repo2->findAll();

        return $this->render('/general/index.html.twig', [
            'controller_name' => 'GeneralController',
            'events' => $events,
            'images' => $images
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

        $events = $repo->findAllDesc();

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
        $session = $request->getSession();
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);

        $formEventRegistrationTreatment= $this->createForm(EventRegistrationTreatmentType::class,$event);
        $formEventRegistrationTreatment->handleRequest($request);
        if($session->has('paiement')){

            $paiement = $session->get('paiement');
            dump($paiement);
            $userPayEvent = $session->get('userPayEvent');
            $choixRepas = $session->get('choixRepas');
           
        
            if ($formEventRegistrationTreatment->isSubmitted() && $formEventRegistrationTreatment->isValid()) {
                $paiement = $this->getDoctrine()
                ->getRepository(AttributMoyenPaiements::class)
                ->find($paiement);
                if( $choixRepas == true ) {
                    $event->addUtilisateursMange($user);
                    $user->addMange($event);
                }
                $event->addUtilisateur($user);
                $user->addParticipe($event);
                $userPayEvent->setAttributMoyenPaiement($paiement);
                $userPayEvent->setUtilisateur($user);
                $userPayEvent->setEvent($event);
                $userPayEvent->setAttributMoyenPaiements($paiement);
                $userPayEvent->setUtilisateurs($user);
                $userPayEvent->setEvents($event);
                $manager->persist($paiement);
                $manager->persist($userPayEvent);
               
                
                $this->addFlash(
                    'notice',
                    'Vous êtes bien inscrit a l\'évenement'
                );
                $manager->flush();
                $session->clear();          
            }else {
                return $this->render('/general/eventRegistrationTreatment.html.twig', [
                    'controller_name' => 'GeneralController',
                    'event' => $event,
                    'formEventRegistrationTreatment'=> $formEventRegistrationTreatment->createView()
                ]);
            }
        }

        $form = $this->createForm(ParticipeType::class, $this->getUser());
        if  ($event->getRepasPossible() == 1 || $event->getRepasPossible() == null)
        {
            $form->add('ChoixRepas', ChoiceType::class, array(
                "mapped" => false,
                "multiple" => false,
                "attr" => array(
                    'class' => "form-control"
                ),
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false
                )
            ));
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $idpaiement = $form['Paiement']->getData();
            $paiement = $this->getDoctrine()
                             ->getRepository(AttributMoyenPaiements::class)
                             ->find($idpaiement);
            $userPayEvent = new UtilisateurMoyenPaiementEvent();
            dump($paiement);
            $choixRepas = $form->get("ChoixRepas")->getData();
            if($paiement->getId() == 1){
                
                $session->set('paiement', $paiement);
                $session->set('userPayEvent', $userPayEvent);
                $session->set('choixRepas', $choixRepas);
 
                return $this->render('/general/eventRegistrationTreatment.html.twig', [
                    'controller_name' => 'GeneralController',
                    'event' => $event,
                    'formEventRegistrationTreatment'=> $formEventRegistrationTreatment->createView()
                ]);
        
            }else{
                if( $choixRepas == true ) {
                    $event->addUtilisateursMange($user);
                    $user->addMange($event);
                }
                $event->addUtilisateur($user);
                $user->addParticipe($event);
                $userPayEvent->setAttributMoyenPaiement($paiement);
                $userPayEvent->setUtilisateur($user);
                $userPayEvent->setEvent($event);
                $userPayEvent->setAttributMoyenPaiements($paiement);
                $userPayEvent->setUtilisateurs($user);
                $userPayEvent->setEvents($event);
                $manager->persist($paiement);
                $manager->persist($userPayEvent);
                
                $this->addFlash(
                    'notice',
                    'Vous êtes bien inscrit a l\'évenement'
                );
                $manager->flush();
            }
        }
        $creneau = $event->getCreneauxBenevoles();
        $formAsso = $this->createForm(AssoEventType::class,$event);
        $formAsso->handleRequest($request);
        if ($formAsso->isSubmitted() && $formAsso->isValid()) {
            foreach ($event->getCreneauxBenevoles() as $creneaux) {
                $event->addCreneauxBenevole($creneaux);
                $creneaux->setEvent($event);
                $manager->persist($creneaux);
            }
            $manager->persist($event);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Vos créneaux bénévol ont bien été crée'
            );
        }

        $formBenevole = $this->createForm(BenevoleType::class, null, array( 'id' => $id ));
        $formBenevole->add('save', SubmitType::class, ['label' => 'S\'inscrire']);
        $formBenevole->handleRequest($request);
        if ($formBenevole->isSubmitted() && $formBenevole->isValid()) {
            $creneauxData = $formBenevole->get('creneaux')->getData();
            foreach ($event->getCreneauxBenevoles() as $creneauxEvent) {
                foreach ($creneauxData as $data) {
                    if( $data->getId() == $creneauxEvent->getId() ) {
                        $creneauxEvent->addUtilisateur($user);
                        $manager->persist($creneauxEvent);
                    }
                }
            }
            $manager->flush();
            $this->addFlash(
                'notice',
                'Vous vous êtes bien inscrit en tant que bénévole a cet évènement'
            );
        }

        $formDelete = $this->createFormBuilder()
        ->getForm();
        $formDelete->handleRequest($request);
        if ($formDelete->isSubmitted() && $formDelete->isValid()) {
            return $this->redirectToRoute('security_delete_event',['id'=>$id]);
        }

        $formPrint = $this->createFormBuilder()
        ->add('print', SubmitType::class, ['label' => 'Imprimer'])
        ->getForm();
        $formPrint->handleRequest($request);
        if ($formPrint->isSubmitted() && $formPrint->isValid() && 'print' === $formPrint->getClickedButton()->getName()) {      
            return $this->redirectToRoute('security_print_event',['id'=>$id]);
        }
            
        return $this->render('/general/event.html.twig', [
            'controller_name' => 'GeneralController',
            'event' => $event,
            'form'=> $form->createView(),
            'formAsso' => $formAsso->createView(),
            'formBenevole' => $formBenevole->createView(),
            'formDelete' => $formDelete->createView(),
            'formPrint' => $formPrint->createView()
        ]);
    }

   
    /**
     * @Route("/creationEvenement", name="createEvent")
     * @Route("/evenement/{id}/edit", name="editEvent")
     */
    public function createEvents(Event $event = null, Request $request,ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if(!$event) {
            $event = new Event();
        }
        $form = $this->createForm(EventCreateType::class, $event);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {
            //var_dump($event->getDates());
              
            if(count($event->getDates()) == 0 ){
                $this->addFlash(
                    'warning',
                    'Vous devez ajouter au moins une date !'
                );
                return $this->redirectToRoute('createEvent');
            }else{
                foreach ($event->getDates() as $date) {
                    $event->addDate($date);
                    $date->setEvent($event);
                    $manager->persist($date);
                }
            }
            foreach ($event->getGalops() as $galop) {
                $event->addGalops($galop);
               // $galop->addEvenement($event);
                $manager->persist($galop);
            }
            if(count($event->getImages()) == 0 ){
                $this->addFlash(
                    'warning',
                    'Vous devez ajouter au moins une image !'
                );
                return $this->redirectToRoute('createEvent');
            }else{
                foreach ($event->getImages() as $image) {
                    $event->addImage($image);
                    $image->setEvenement($event); 
                    $manager->persist($image);
                }
            }

            $manager->persist($event);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Votre évènement a bien été crée .'
            );
            return $this->redirectToRoute('events');
        }

        return $this->render('/general/createEvents.html.twig', [
            'controller_name' => 'GeneralController',
            'formEvent' => $form->createView()

        ]);
    }

     /**
     * @Route("/creationImage", name="createImage")
     */
    public function createImages(Request $request,ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $image = new Images();
        $form = $this->createForm(ImagesType::class, $image);

        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($image);
            $manager->flush();

            //return $this->redirectToRoute('events');
        }

        return $this->render('/general/createImages.html.twig', [
            'controller_name' => 'GeneralController',
            'formImage' => $form->createView()

        ]);
    }

     /**
     * @Route("/image/{id}", name="show_image")
     */
    public function image($id,Request $request,ObjectManager $manager)
    {
        
       // $form->handleRequest($request);
      

        $repo = $this->getDoctrine()->getRepository(Images::class);

        $image = $repo->find($id);
        
        return $this->render('/general/showImage.html.twig', [
            'controller_name' => 'GeneralController',
            'image' => $image
        ]);
    }

    /**
     * @Route("/creationPdf", name="createPdf")
     */
    public function createPdf(Request $request,ObjectManager $manager)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
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
            //return $this->redirectToRoute('events');
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
