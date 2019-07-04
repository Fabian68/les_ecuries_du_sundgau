<?php

namespace App\Controller;

use App\Entity\Galops;
use App\Entity\Utilisateur;
use App\Form\RegistrationType;
use App\Form\ModifyAccountType;
use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController 
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder): Response
    {  
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationType::class,$user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user,$user->getMotDePasse());
            $user->setMotDePasse($hash);
            $user->setRoles(array('ROLE_USER'));
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('security_login');
        }
        return $this->render('security/registration.html.twig', [
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/connexion",name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {   
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

     /**
     * @Route("/deconnexion",name="security_logout")
     */
    public function logout(){
    }

    /**
     * @Route("/profil",name="security_profile")
     */
    public function profile(){
        return $this->render('security/profile.html.twig');
    }
    
    /**
     * @Route("/profil/modifier",name="security_profile_modify")
     */
    public function profile_modify(UserInterface $user ,Request $request,ObjectManager $manager){

        
           
            $form = $this->createForm(ModifyAccountType::class,$user);
     //   $user=$this->getUser();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();
    
            return $this->redirectToRoute('security_profile');
        }
        return $this->render('security/profile_modify.html.twig', [
            'form'=> $form->createView(),
            'user2'=>$user
        ]);
    }

    /**
     * @Route("/profil/modifier_mot_de_passe",name="security_profile_modify_password")
     */
    public function profile_modify_password(UserInterface $user ,Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder){   
        $form = $this->createForm(ChangePasswordType::class,$user);
 //   $user=$this->getUser();
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
        $hash = $encoder->encodePassword($user,$user->getMotDePasse());
        $user->setMotDePasse($hash);
        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute('security_profile');
    }
    return $this->render('security/profile_modify_password.html.twig', [
        'form'=> $form->createView(),
        'user'=>$user
    ]);
}
}
