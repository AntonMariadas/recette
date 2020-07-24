<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminSecuController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(InscriptionType::class, $utilisateur);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $passwordCrypt = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($passwordCrypt);
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            return $this->redirectToRoute('aliments');
                
        }

        return $this->render('admin_secu/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/login", name="connexion")
     */
    public function login(AuthenticationUtils $utils)
    {
        $lastUserName = $utils->getLastUsername();
        $error = $utils->getLastAuthenticationError();
        
        return $this->render('admin_secu/login.html.twig', [
            'lastUsername' => $lastUserName,
            'error' => $error
        ]);
    }

    
    /**
     * @Route("/logout", name="deconnexion")
     */
    public function logout()
    {
        
    }
}
