<?php

namespace App\Controller;

use App\Repository\AlimentRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AlimentController extends AbstractController
{
    
    /**
     * @Route("/", name="aliments")
     */
    public function index(AlimentRepository $repo)
    {
        $aliments = $repo->findAll();

        return $this->render('aliment/aliments.html.twig', [
            'aliments' => $aliments,
            'isCalorie' => false,
            'isGlucide' => false
        ]);
    }


    /**
     * @Route("/aliments/calorie/{calorie}", name="alimentsParCalorie")
     */
    public function alimentsMoinsCaloriques(AlimentRepository $repo, $calorie)
    {
        $aliments = $repo->getAlimentParPropriete('calorie', '<',  $calorie);

        return $this->render('aliment/aliments.html.twig', [
            'aliments' => $aliments,
            'isCalorie' => true,
            'isGlucide' => false
        ]);
    }

    /**
     * @Route("/aliments/glucide/{glucide}", name="alimentsParGlucide")
     */
    public function alimentsMoinsGlucides(AlimentRepository $repo, $glucide)
    {
        $aliments = $repo->getAlimentParPropriete('glucide', '<',  $glucide);

        return $this->render('aliment/aliments.html.twig', [
            'aliments' => $aliments,
            'isCalorie' => false,
            'isGlucide' => true
        ]);
    }
}

