<?php

namespace App\Controller\Admin;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminTypeController extends AbstractController
{
    /**
     * @Route("/admin/types", name="admin_types")
     */
    public function affichage(TypeRepository $repo)
    {
        $types = $repo->findAll();
        return $this->render('admin/adminTypes.html.twig', [
            'types' => $types
        ]);
    }


    /**
     * @Route("/admin/type/modif/{id}", name="admin_modif")
     * @Route("/admin/type/ajout", name="admin_ajout")
     */
    public function ajoutEtModif(Type $type = null, Request $request, EntityManagerInterface $entityManager)
    {
        if (!$type) {
            $type = new Type();
        }

        $isModif = $type->getId() !== null;

        $form = $this->createForm(TypeType::class, $type);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($type);
            $entityManager->flush();
            if ($isModif) {
                $this->addFlash('success', 'La modification a été efféctuée');
            } else {
                $this->addFlash('success', 'L\'ajout a été efféctuée');
            }
            return $this->redirectToRoute('admin_types');
        }

        return $this->render('admin/modifEtAjoutTypes.html.twig', [
            'type' => $type,
            'form' => $form->createView(),
            'isModif' => $isModif
        ]);
    }


    /**
     * @Route("/admin/type/supp/{id}", name="admin_supp")
     */
    public function supprimer(Type $type, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($type);
        $entityManager->flush();
        $this->addFlash('success', 'La suppression a été efféctuée');

        return $this->redirectToRoute('admin_types');
    }
}
