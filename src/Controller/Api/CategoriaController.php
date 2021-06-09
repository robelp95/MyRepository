<?php

namespace App\Controller\Api;

use App\Entity\Categoria;
use App\Entity\Producto;
use App\Form\CategoriaType;
use App\Repository\CategoriaRepository;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;


class CategoriaController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/categorias")
     * @Rest\View(serializerGroups={"categoria","producto"},serializerEnableMaxDepthChecks=true)
     */
    public function getAction(CategoriaRepository $categoriaRepository)
    {
//        dd($categoriaRepository->findAll());
        return $categoriaRepository->findAll();
    }


    /**
     * @param EntityManagerInterface $em
     * @Rest\Post(path="categoria_post")
     * @Rest\View(serializerGroups={"categoria","producto"},serializerEnableMaxDepthChecks=true)
     */
    public function postAction(EntityManagerInterface $em, Request $request)
    {
        $categoria= new Categoria();
        $form= $this->createForm(CategoriaType::class,$categoria);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($categoria);
            $em->flush();
            return $categoria;
        }
        return $form;

    }
}
