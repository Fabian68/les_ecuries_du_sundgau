<?php

namespace App\Controller;

use App\Entity\Galops;
use App\Entity\Description;
use App\Entity\Utilisateur;
use App\Form\DescriptionType;
use App\Form\RegistrationType;
use App\Form\ModifyAccountType;
use App\Form\ResetPasswordType;
use App\Form\ChangePasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController 
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request,ObjectManager $manager,UserPasswordEncoderInterface $encoder): Response
    {  
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationType::class,$user);
        $user->setUpdatedAt(new \DateTime());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            
            $user->setVerifiedMail(false);
            $user->setUpdatedAt(new \DateTime());
            $hash = $encoder->encodePassword($user,$user->getMotDePasse());
            $user->setMotDePasse($hash);
            $user->setRoles(array('ROLE_USER'));
            $manager->persist($user);
            $manager->flush();
            $user->setImageFile(null);//la valeur doit être vidé car elle ne sert plus et n'est pas serializable
            $this->addFlash(
                'notice',
                'Votre compte a bien été crée . '
            );
            return $this->redirectToRoute('security_login');
        }
         $user->setImageFile(null);//si le formulaire est invalide la valeur doit aussi être vidé

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
     * @Route("/profil/modifier",name="security_profile_modify")
     */
    public function profile_modify(UserInterface $user ,Request $request,ObjectManager $manager){

            $form = $this->createForm(ModifyAccountType::class,$user);
    //   $user=$this->getUser();
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTime());
        
            $manager->persist($user);
            $manager->flush();
            $user->setImageFile(null);
            $this->addFlash(
                'notice',
                'Votre compte a bien été modifié .'
            );
            return $this->redirectToRoute('security_profile');
        }
        $user->setImageFile(null);
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

        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) { 
                
        // !password_verify( $user->confirm_oldMotDePasse ,$user->oldMotDePasse) ancienne manniere 
            if(!$encoder->isPasswordValid($user, $user->confirm_oldMotDePasse)){
                // Gérer l'erreur
                $form->get('confirm_oldMotDePasse')->addError(new FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel !"));
            }else{

            $hash = $encoder->encodePassword($user,$user->nouveau_motDePasse);
            $user->setMotDePasse($hash);
            $user->setUpdatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "Votre mot de passe a bien été modifié !"
            );
            return $this->redirectToRoute('security_profile');
            }
        }
        return $this->render('security/profile_modify_password.html.twig', [
            'form'=> $form->createView(),
            'user'=>$user
        ]);
    }
    /**
     * @Route("/profil",name="security_profile")
     * @Route("/profil/{ident}",name="security_profile_withid")
     */
    public function profile($ident=null,ObjectManager $manager){
        $user=new Utilisateur();
        if($ident==null){
            $user = $this->getUser();
        }else{
            $this->denyAccessUnlessGranted('ROLE_ADMIN');
            $user=$user = $manager->getRepository(Utilisateur::class)->findOneById($ident);
        }

        return $this->render('security/profile.html.twig',[
            'user'=>$user
        ]);
    }

     /**
     * @Route("/description",name="security_description")
     */
    public function description(ObjectManager $manager,Request $request){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Pour avoir qu'une seule iteration d'inscription dans la BdD
        $description = $manager->getRepository(Description::class)->findOneById('1');
        if($description ==null){
            $description = new Description();
            $description->setTexte('Les écuries du sundgau est un dans un cadre idéale qui favorise l\'activitée etc etc ');
        }
        
        $form = $this->createForm(DescriptionType::class,$description);
        
        $form->handleRequest($request);
     
        if ($form->isSubmitted() && $form->isValid()) {

            $manager->persist($description);
            $manager->flush();

            $this->addFlash('notice', 'Description modifié.');
            return $this->redirectToRoute('security_profile');
        }

        return $this->render('security/description.html.twig',[
            'form'=> $form->createView(),
            'description'=> $description
        ]);
    }

    /**
     * @Route("/mot_de_passe_oublier", name="security_forgotten_password")
     */
    public function forgottenPassword(ObjectManager $manager,Request $request,UserPasswordEncoderInterface $encoder,\Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator ): Response
    {
        if ($request->isMethod('POST')) {
 
            $email = $request->request->get('email');
 
            $user = $manager->getRepository(Utilisateur::class)->findOneByEmail($email);
            /* @var $user User */
 
            if ($user === null) {
                $this->addFlash('danger', 'Email Inconnu');
                return $this->redirectToRoute('home');
            }
            $token = $tokenGenerator->generateToken();
 
            try{
                $user->setResetToken($token);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }
 
            $url = $this->generateUrl('security_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);
 
            $message = (new \Swift_Message('Mot de passe oublier'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    " Voici le lien pour modifier votre mot de passe : " . $url,
                    'text/html'
                );
 
            $mailer->send($message);
 
            $this->addFlash('notice', 'Mail envoyé');
 
            return $this->redirectToRoute('home');
        }
        return $this->render('security/forgotten_password.html.twig');
    }

    /**
     * @Route("/modifier_mot_de_passe/{token}", name="security_reset_password")
     */
    public function resetPassword(ObjectManager $manager,Request $request, string $token, UserPasswordEncoderInterface $encoder)
    {
 
        $user = $manager->getRepository(Utilisateur::class)->findOneByResetToken($token);
        $form = $this->createForm(ResetPasswordType::class,$user);

        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) { 
           
            $user->setResetToken(null);
            $hash = $encoder->encodePassword($user,$user->nouveau_motDePasse);
            $user->setMotDePasse($hash);
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Votre mot de passe a bien été modifier . '
            );      
            return $this->redirectToRoute('home');
        }else {
 
            return $this->render('security/reset_password.html.twig', [
                'token' => $token,
                'form'=>$form->createView()
            ]);
        }
    }

    /**
     * @Route("/verifier_mail", name="security_verification_mail")
     */
    public function verificationEmail(UserInterface $user,ObjectManager $manager,Request $request,UserPasswordEncoderInterface $encoder,\Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator ): Response
    {
        $form = $this->createFormBuilder()
       ->add('save', SubmitType::class, ['label' => ' Envoyer mail verification '])
       ->getForm();

       $form->handleRequest($request);

       if($form->isSubmitted() && $form->isValid()) { 

            $token = $tokenGenerator->generateToken();
    
            try{
                $user->setValidationEmailToken($token);
                $manager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('home');
            }

            $url = $this->generateUrl('security_verification_mail_validation', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

            $message = (new \Swift_Message('Verification mail'))
                ->setFrom('g.ponty@dev-web.io')
                ->setTo($user->getEmail())
                ->setBody(
                    " Voici le lien pour valdier votre email : " . $url,
                    'text/html'
                );

            $mailer->send($message);

            $this->addFlash('notice', 'Mail envoyé');
           
       

            return $this->redirectToRoute('home');
        }
        else {

        return $this->render('security/verification_mail.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
    /**
     * @Route("/verifier_mail_validation/{token}", name="security_verification_mail_validation")
     */
    public function verificationMailValidation(ObjectManager $manager,Request $request, string $token, UserPasswordEncoderInterface $encoder)
    {
 

        $user = $manager->getRepository(Utilisateur::class)->findOneByValidationEmailToken($token);//permet de ne pas utiliser le token d'une autre personne

        if($user != null){

        $form = $this->createFormBuilder()
        ->add('save', SubmitType::class, ['label' => ' Confirmer '])
        ->getForm();

        $form->handleRequest($request);
    
        if($form->isSubmitted() && $form->isValid()) { 
           
            $user->setValidationEmailToken(null);
            $user->setVerifiedMail(true);
          
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'notice',
                'Votre mail est verifié .'
            );
            return $this->redirectToRoute('home');
        }else {
 
            return $this->render('security/verification_mail_validation.html.twig', [
                'token' => $token,
                'form'=>$form->createView()
            ]);
        }

        }else {
            $this->addFlash(
                'notice',
                'Token introuvable'
            );
            return $this->redirectToRoute('home');
        }
 
    }
}
