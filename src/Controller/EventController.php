<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Repas;
use App\Entity\Video;
use App\Entity\Galops;
use App\Entity\Images;
use App\Form\EventType;
use App\Form\RepasType;
use App\Form\ImagesType;
use App\Form\AddVideoType;
use App\Form\BenevoleType;
use App\Entity\Utilisateur;
use App\Form\AssoEventType;
use App\Form\EventEditType;
use App\Form\ParticipeType;
use App\Form\AddPictureType;
use App\Form\EventCreateType;
use App\Entity\DatesEvenements;
use App\Entity\CreneauxBenevoles;
use App\Form\DatesEvenementsType;
use App\Form\EventDiversCreateType;
use App\Entity\AttributMoyenPaiements;
use App\Form\EventRegistrationTreatmentType;
use App\Entity\UtilisateurMoyenPaiementEvent;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EventController extends AbstractController
{
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
    public function event($id,Request $request,ObjectManager $manager,\Swift_Mailer $mailer) 
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);
        if($event == NULL){
            return $this->render('event/eventWhoNotExist.html.twig', [
                'controller_name' => 'EventController',
        ]);  
        }

        $formDelete = $this->createFormBuilder()
        ->getForm();
        $formDelete->handleRequest($request);
        if ($formDelete->isSubmitted() && $formDelete->isValid()) {
            return $this->redirectToRoute('security_delete_event',['id'=>$id]);
        }

        if($event->getDivers()){
            return $this->render('/event/divers.html.twig', [
                'controller_name' => 'EventController',
                'event' => $event,
                'formDelete' => $formDelete->createView(),
            ]);  
        }
        $now = new \DateTime('now');
        $formEventRegistrationTreatment= $this->createForm(EventRegistrationTreatmentType::class,$event);
        $formEventRegistrationTreatment->handleRequest($request);
        $formCancel = $this->createFormBuilder()
        ->add('annuler', SubmitType::class)
        ->getForm();
        $formCancel->handleRequest($request);
        if($session->has('paiement')){
            $paiement = $session->get('paiement');
            $userPayEvent = $session->get('userPayEvent');
            $choixRepas = $session->get('choixRepas');
           
            if ($formCancel->isSubmitted() && $formCancel->isValid()){
                $session->clear();                  
            }elseif($formEventRegistrationTreatment->isSubmitted() && $formEventRegistrationTreatment->isValid()) {
                $choixPrix=$event->getChoixPrix();

                if($choixPrix!=$event->getTarifMoinsDe12() && $choixPrix!=$event->getTarifPlusDe12() && $choixPrix!=$event->getTarifProprietaire()){
                    $this->addFlash(
                        'Warning',
                        'Le tarif entré ne correspond à aucun tarifs'
                    );
                    return $this->render('/general/eventRegistrationTreatment.html.twig', [
                        'controller_name' => 'GeneralController',
                        'event' => $event,
                        'formEventRegistrationTreatment'=> $formEventRegistrationTreatment->createView(),
                        'formCancel'=> $formCancel->createView()
                    ]);
                }
                $paiement = $this->getDoctrine()
                ->getRepository(AttributMoyenPaiements::class)
                ->find($paiement);
                $event->addUtilisateur($user);
                $user->addParticipe($event);
                if( $choixRepas == true ) {
                    $event->addUtilisateursMange($user);
                    $user->addMange($event);
                }
                $userPayEvent->setAttributMoyenPaiement($paiement);
                $userPayEvent->setUtilisateur($user);
                $userPayEvent->setEvent($event);
                $manager->persist($paiement);
                $manager->persist($userPayEvent);

                $manager->flush();

                $message = (new \Swift_Message('Prélèvement'))
                ->setFrom('administrateur@les-ecuries-du-sundgau.fr')
                ->setTo('administrateur@les-ecuries-du-sundgau.fr')
                ->setBody(
                    "Signataire : " . $event->getSignataire() . "<br> L'utilisateur " . $user->getNom() . " " . $user->getPrenom() . " accepte d'être prélevé pour l'évènement " . $event->getTitre() . " la somme de " . $choixPrix . " euros.",
                    'text/html'
                );
 
                $mailer->send($message);
                $this->addFlash(
                    'notice',
                    'Vous êtes bien inscrit a l\'évènement'
                );
                $session->clear();          
            }else {
                return $this->render('/general/eventRegistrationTreatment.html.twig', [
                    'controller_name' => 'EventController',
                    'event' => $event,
                    'formEventRegistrationTreatment'=> $formEventRegistrationTreatment->createView(),
                    'formCancel'=> $formCancel->createView()
                ]);
            }
        }

        $form = $this->createForm(ParticipeType::class, $this->getUser());
        if  ($event->getRepasPossible() == 1)
        {
            $form->add('ChoixRepas', ChoiceType::class, array(
                "mapped" => false,
                "multiple" => false,
                'label' => 'Prendre le repas?',
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
            if  ($event->getRepasPossible() == 1 ) {
                $choixRepas = $form->get("ChoixRepas")->getData();
                if( $choixRepas == true ) {
                    $event->addUtilisateursMange($user);
                    $user->addMange($event);
                }
            }else{
                $choixRepas = false;
            }
            if($paiement->getId() == 1){  
                $session->set('paiement', $paiement);
                $session->set('userPayEvent', $userPayEvent);
                $session->set('choixRepas', $choixRepas);
 
                return $this->render('/general/eventRegistrationTreatment.html.twig', [
                    'controller_name' => 'EventController',
                    'event' => $event,
                    'formEventRegistrationTreatment'=> $formEventRegistrationTreatment->createView(),
                    'formCancel'=> $formCancel->createView()
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
                $manager->persist($paiement);
                $manager->persist($userPayEvent);
                
                $this->addFlash(
                    'notice',
                    'Vous êtes bien inscrit a l\'évènement'
                );
                $manager->flush();
            }
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
                'Vous vous êtes bien inscrit en tant que bénévole à cet évènement'
            );
        }

        $formPrint = $this->createFormBuilder()
        ->add('print', SubmitType::class, ['label' => 'Imprimer', 'attr' => [ 'class' => 'btn btn-secondary index-more-button' ] ])
        ->getForm();
        $formPrint->handleRequest($request);
        if ($formPrint->isSubmitted() && $formPrint->isValid() && 'print' === $formPrint->getClickedButton()->getName()) {      
            return $this->redirectToRoute('security_print_event',['id'=>$id]);
        }
            
        return $this->render('/general/event.html.twig', [
            'controller_name' => 'EventController',
            'event' => $event,
            'form'=> $form->createView(),
            'formBenevole' => $formBenevole->createView(),
            'formDelete' => $formDelete->createView(),
            'formPrint' => $formPrint->createView()
        ]);
    }

    /**
     * @Route("/evenement/{id}/participants", name="eventUserList")
     */
    public function userList($id, Request $request,ObjectManager $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);
        if($event == NULL){
            return $this->render('event/eventWhoNotExist.html.twig', [
                'controller_name' => 'EventController',
            ]);  
        } else {
            return $this->render('event/userList.html.twig', [
                'event' => $event,
            ]);  
        }


    }

    /**
     * @Route("/asso/creationCreneaux/{id}", name="createCreneaux")
     */
    public function createPdf($id, Request $request,ObjectManager $manager)
    {
        $user = $this->getUser();
        $repo = $this->getDoctrine()->getRepository(Event::class);
        $event = $repo->find($id);

        $creneau = $event->getCreneauxBenevoles();
        $formAsso = $this->createForm(AssoEventType::class,$event);
        $formAsso->handleRequest($request);
        if ($formAsso->isSubmitted() && $formAsso->isValid()) {
            foreach ($event->getCreneauxBenevoles() as $creneaux) {
                $dateDebut=$creneaux->getDateDebut();
                $dateFin=$creneaux->getDateFin();
                if($dateDebut>$dateFin){
                    $this->addFlash(
                        'warning',
                        'La date de début doit être inferieur a la date de fin !'
                    );
                    return $this->redirectToRoute('createCreneaux', ['id'=>$event->getId()]);
                }
                if($dateDebut->diff($dateFin,true)->days!=0){
                    $this->addFlash(
                        'warning',
                        'La date de début et la date de fin doivent être sur le même jour !'
                    );
                    return $this->redirectToRoute('createCreneaux', ['id'=>$event->getId()]);                 
                }
                $event->addCreneauxBenevole($creneaux);
                $creneaux->setEvent($event);
                $manager->persist($creneaux);
            }
            $manager->persist($event);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Vos créneaux bénévoles ont bien été créés'
            );
            return $this->redirectToRoute('event',['id'=>$event->getID()]);
        }

        return $this->render('/event/createCreneauxBenevole.html.twig', [
            'controller_name' => 'EventController',
            'event' => $event,
            'formAsso' => $formAsso->createView(),
        ]);
    }

    /**
     * @Route("/admin/evenement/{idEvent}/retirer_utilisateur/{idUser}", name="remove_user_on_event")
     */
    public function removeUserOnEvent($idEvent,$idUser,ObjectManager $manager)
    {
        $event = $manager->getRepository(Event::class)->findOneById($idEvent);
        $user = $manager->getRepository(Utilisateur::class)->findOneById($idUser);
        $UtilisateursMoyenPaiementEvent = $manager->getRepository(UtilisateurMoyenPaiementEvent::class)->findOneBy(array('event'=>$event,'utilisateur'=>$user));
        $manager->remove($UtilisateursMoyenPaiementEvent);

        $event->removeUtilisateur($user); 
        $event->removeUtilisateursMange($user);
        $manager->flush();
        $this->addFlash(
            'notice',
            'L\'utilisateur '. $user->getNom() . ' ' . $user->getPrenom() . ' a bien été retiré de l\'évènement '  . $event->getTitre()
        );
        return $this->redirectToRoute('event',['id'=>$idEvent]);
    }

    /**
     * @Route("/evenement/{idEvent}/creneau/{idCreneau}/retirer_utilisateur/{idUser}", name="remove_user_on_creneau")
     */
    public function removeUserOnCreneau($idCreneau,$idUser,$idEvent,ObjectManager $manager)
    {
        $creneau = $manager->getRepository(CreneauxBenevoles::class)->findOneById($idCreneau);
        $user = $manager->getRepository(Utilisateur::class)->findOneById($idUser);

        $creneau->removeUtilisateur($user); 
        $manager->flush();
        $this->addFlash(
            'notice',
            'L\'utilisateur '. $user->getNom() . ' ' . $user->getPrenom() . ' a bien été retiré du créneau' 
        );
        return $this->redirectToRoute('event',['id'=>$idEvent]);
    }
    /**
     * @Route("/admin/creationEvenement", name="createEvent")
     * @Route("/admin/evenement/{id}/edit", name="editEvent")
     */
    public function createEvents(Event $event = null, Request $request,ObjectManager $manager)
    {
        $create = false;
        if(!$event) {
            $event = new Event();
            $create = true;
            $form = $this->createForm(EventCreateType::class, $event);
        }
        else {
            $event->setDateDivers(new \DateTime('now'));
            $form = $this->createForm(EventEditType::class, $event);
        }
        $event->setChoixPrix(0.0);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {           
            if(count($event->getDates()) == 0 ){
                $this->addFlash(
                    'warning',
                    'Vous devez ajouter au moins une date !'
                );
                return $this->redirectToRoute('createEvent');
            }else{
                foreach ($event->getDates() as $date) {
                    $dateDebut=$date->getDateDebut();
                    $dateFin=$date->getDateFin();
                    if($dateDebut>$dateFin){
                        $this->addFlash(
                            'warning',
                            'La date de début doit être inférieur à la date de fin !'
                        );
                        return $this->redirectToRoute('createEvent');
                    }
                    if($dateDebut->diff($dateFin,true)->days!=0){
                        $this->addFlash(
                            'warning',
                            'La date de début et la date de fin doivent être le même jour !'
                        );
                        return $this->redirectToRoute('createEvent');                 
                    }
                }
            }
            if((count($event->getImages()) == 0)&&(count($event->getVideos()) == 0) ){
                $this->addFlash(
                    'warning',
                    'Vous devez ajouter au moins une image ou une vidéo !'
                );
                return $this->redirectToRoute('createEvent');
            }else{ 
                foreach ($event->getImages() as $image) {
                    $image->setEvenement($event); 
                }
                foreach ($event->getVideos() as $video) {
                    $choix = explode("=",$video->getLien());
                    if(sizeof($choix) != 2){
                        $this->addFlash(
                            'warning',
                            'Lien de vidéo invalide'
                        );
                        return $this->redirectToRoute('createEvent');
                    }else {
                        $videoLien="https://www.youtube.com/embed/" . $choix[1];
                        $video->setLien($videoLien);
                        $video->setEvenement($event); 
                        $event->addVideo($video);
                    }       
                }
            }
            $event->setDivers(false);                
            $manager->persist($event);
            $manager->flush();
            $message;
            if($create == false){
                $message= 'Votre évènement a bien été modifié';
            }else{
                $message='Votre évènement a bien été créé';
            }
            $this->addFlash(
                'notice',
                $message
            );
            return $this->redirectToRoute('home');
        }

        if($create) {
            return $this->render('/general/createEvents.html.twig', [
                'controller_name' => 'GeneralController',
                'formEvent' => $form->createView()

            ]);
        }
        else {
            return $this->render('/general/editEvent.html.twig', [
                'controller_name' => 'GeneralController',
                'formEvent' => $form->createView()

            ]);
        }
    }

    /**
     * @Route("/admin/creationEvenementDivers", name="createEventDivers")
     */
    public function createEventsDivers(Request $request,ObjectManager $manager)
    {
        $event = new Event();
        $formEventDiversCreate = $this->createForm(EventDiversCreateType::class, $event);
        $event->setChoixPrix(0.0);
        $formEventDiversCreate->handleRequest($request);
        if ($formEventDiversCreate->isSubmitted() && $formEventDiversCreate->isValid()) { 
           
            $event->setDivers(true);

            $galop = $manager->getRepository(Galops::class)->findOneByNiveau(-1);
            $event->addGalop($galop);
            $galop->addEvenement($event);

            $date= $event->getDateDivers();
            $dateEvenement = new DatesEvenements();
            $dateEvenement->setDateDebut($date);
            $dateEvenement->setDateFin($date);
            $dateEvenement->setEvent($event);
            $event->addDate($dateEvenement);

            if((count($event->getImages()) == 0)&&(count($event->getVideos()) == 0) ){
                $this->addFlash(
                    'warning',
                    'Vous devez ajouter au moins une image ou une vidéo !'
                );
                return $this->redirectToRoute('createEvent');
            }else{    
                foreach ($event->getImages() as $image) {
                    $image->setEvenement($event);
                }
                foreach ($event->getVideos() as $video) {
                    $choix = explode("=",$video->getLien());
                   
                    $video->setEvenement($event);
                    if(sizeof($choix) != 2){
                        $this->addFlash(
                            'warning',
                            'Lien de vidéo invalide'
                        );
                        return $this->redirectToRoute('createEvent');
                    }else {
                        $videoLien="https://www.youtube.com/embed/" . $choix[1];
                        $video->setLien($videoLien);
                        $video->setEvenement($event); 
                        $event->addVideo($video);
                    }       
                }
            }
            $manager->persist($event);
            $manager->flush();
            foreach ($event->getImages() as $image) {
                $image->setImageFile(null); 
            }
            $this->addFlash(
                'notice',
                'Votre évènement a bien été créé .'
            );
            return $this->redirectToRoute('home');
        }    
        return $this->render('/event/createEventsDivers.html.twig', [
        'controller_name' => 'EventController',
        'formEventDiversCreate' => $formEventDiversCreate->createView()
        ]);

    }

     /**
     * @Route("/admin/supprimer_evenement/{id}", name="security_delete_event")
     */
    public function deleteEvent($id,ObjectManager $manager)
    {
        $event = $manager->getRepository(Event::class)->findOneById($id);
        $UtilisateursMoyenPaiementEvent = $manager->getRepository(UtilisateurMoyenPaiementEvent::class)->findByEvent($event);
       
        foreach($UtilisateursMoyenPaiementEvent as $UMPE){
            $manager->remove($UMPE);
        }
        $manager->remove($event); 
        $manager->flush();
        $this->addFlash(
            'notice',
            'Votre évènement a bien été supprimé.'
        );
        return $this->redirectToRoute('home');
    }

     /**
     * @Route("/admin/evenement/{idEvent}/supprimer_image/{idImage}", name="event_delete_image")
     */
    public function deleteImage($idEvent,$idImage,ObjectManager $manager)
    {
        $image = $manager->getRepository(Images::class)->findOneById($idImage);
        $manager->remove($image); 
        $manager->flush();
        $this->addFlash(
            'notice',
            'Votre image a bien été supprimée'
        );
        return $this->redirectToRoute('event',['id'=>$idEvent]);
    }

     /**
     * @Route("/admin/imprimer_evenement/{id}", name="security_print_event")
     */
    public function printPage($id,ObjectManager $manager)
    {
        $event = $manager->getRepository(Event::class)->findOneById($id);
        $UtilisateurMoyenPaiementEvent=$manager->getRepository(UtilisateurMoyenPaiementEvent::class)->findByEvent($event);

        return $this->render('security/print_event.html.twig', [
            'event'=>$event,
            'UtilisateurMoyenPaiementEvent'=>$UtilisateurMoyenPaiementEvent
        ]);  
    }

    /**
     * @Route("/admin/evenement/{id}/ajouter_une_image", name="add_pictures")
     */
    public function addPictures(Event $event, Request $request, ObjectManager $manager)
    {
        $id = $event->getId();

        $tmp = clone $event;

        foreach ($tmp->getImages() as $image) {
            $tmp->removeImage($image);
        }

        $form = $this->createForm(AddPictureType::class, $tmp);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($tmp->getImages() as $image) {
                $event->addImage($image);
                $image->setEvenement($event); 
                $manager->persist($image);
            }
            $manager->persist($event);
            $manager->flush();
            return $this->redirectToRoute('event', array('id' => $id));
        }

        return $this->render('general/addPicture.html.twig', [
                'controller_name' => 'EventController',
                'formEvent' => $form->createView(),
                'event'=>$event
        ]);  
    }

    /**
     * @Route("/admin/evenement/{id}/ajouter_une_video", name="add_video")
     */
    public function addVideo(Event $event, Request $request, ObjectManager $manager)
    {
        $id = $event->getId();

        $tmp = clone $event;

        foreach ($tmp->getVideos() as $video) {
            $tmp->removeVideo($video);
        }

        $form = $this->createForm(AddVideoType::class, $tmp);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($tmp->getVideos() as $video) {
                $choix = explode("=",$video->getLien());
                if(sizeof($choix) != 2){
                    $this->addFlash(
                        'warning',
                        'Lien de vidéo invalide'
                    );
                    return $this->redirectToRoute('add_video');
                }else {
                    $videoLien="https://www.youtube.com/embed/" . $choix[1];
                    $video->setLien($videoLien);
                    $video->setEvenement($event);
                    $event->addVideo($video);
                    $manager->persist($video);
                }       
            }

            $manager->persist($event);
            $manager->flush();
            return $this->redirectToRoute('event', array('id' => $id));
        }

        return $this->render('general/addVideo.html.twig', [
                'controller_name' => 'EventController',
                'formEvent' => $form->createView(),
                'event'=>$event
        ]);  
    }

    /**
     * @Route("/admin/evenement/{idEvent}/echange_image/{idImage}", name="change_main_image")
     */
    public function changeMainImage($idEvent,$idImage, ObjectManager $manager)
    {
        $image = $manager->getRepository(Images::class)->findOneById($idImage);
        $event =$manager->getRepository(Event::class)->findOneById($idEvent);
        $Array=$event->getImages();  
        $mainImage=$event->getImages()[0];
        $idMainImage=$mainImage->getId();
       
        $imageName=$image->getImageName();
        $mainImageName=$mainImage->getImageName();
        $updatedMainImage=$mainImage->getUpdatedAt();
        $updatedImage=$image->getUpdatedAt();

        $mainImage->setImageName($imageName);
        $image->setImageName($mainImageName);
        $mainImage->setUpdatedAt($updatedImage);
        $image->setUpdatedAt($updatedMainImage);
        $date = new \DateTime('now');
        $image->setUpdatedAt($date);
        $mainImage->setUpdatedAt($date);
        $manager->persist($event);
        $manager->flush();
        $this->addFlash(
            'notice',
            'L\'image principale a bien été modifiée.'
        );
        return $this->redirectToRoute('event',['id'=>$idEvent]);
    }
       
}

