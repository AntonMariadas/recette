<?php

namespace App\Controller\Admin;

use App\Entity\Aliment;
use App\Form\AlimentType;
use App\Repository\AlimentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAlimentController extends AbstractController
{
    /**
     * @Route("/admin/aliments", name="admin_aliments")
     */
    public function index(AlimentRepository $repo)
    {   
        $aliments = $repo->findAll();

        return $this->render('admin/adminAliments.html.twig', [
            'aliments' => $aliments
        ]);
    }


    /**
     * @Route("/admin/aliment/creation", name="admin_aliment_creation")
     * @Route("/admin/aliment/{id}", name="admin_aliment_modification", methods="GET|POST")
     */
    public function ajoutEtModif(Aliment $aliment = null, Request $request, EntityManagerInterface $entityManager)
    {   
        if (!$aliment) {
            $aliment = new Aliment();
        }

        $form = $this->createForm(AlimentType::class, $aliment);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $modif = $aliment->getId() !== null;  //Renvoie true or false
            $entityManager->persist($aliment);
            $entityManager->flush();
            $this->addFlash('success', ($modif) ? 'La modification a été efféctuée' : 'L\'ajout a été efféctuée');
            return $this->redirectToRoute('admin_aliments');
        }

        return $this->render('admin/modifEtAjout.html.twig', [
            'aliment' => $aliment,
            'form' => $form->createView(),
            'isModification' => $aliment->getId() !== null
        ]);
    }


    /**
     * @Route("/admin/aliments/{id}", name="admin_aliment_suppression", methods="DELETE")
     */
    public function suppression(Aliment $aliment, Request $request, EntityManagerInterface $entityManager)
    {   
        if ($this->isCsrfTokenValid('SUP'. $aliment->getId(), $request->get('_token'))) {
            
            $entityManager->remove($aliment);
            $entityManager->flush();
            $this->addFlash('success', 'La suppression a été éffectuée');
            return $this->redirectToRoute('admin_aliments');
        }
        
    }
}
