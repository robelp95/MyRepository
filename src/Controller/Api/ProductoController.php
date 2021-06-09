<?php

namespace App\Controller\Api;

use App\Entity\Categoria;
use App\Entity\Producto;

use App\Form\Model\ProductoDto;
use App\Form\ProductoFormType;
use App\Form\ProductoType;
use App\Repository\ProductoRepository;
use Doctrine\ORM\EntityManagerInterface;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use League\Flysystem\FilesystemOperator;


class ProductoController extends AbstractFOSRestController
{

    /**
     * @Rest\Get(path="/productos")
     * @Rest\View(serializerGroups={"producto"},serializerEnableMaxDepthChecks=true)
     */
    public function getAction(ProductoRepository $productoRepository)
    {
        return $productoRepository->findAll();
    }

    /**
     * @param Request $request
     * @param ProductoRepository $productoRepository
     * @Route("/list")
     */
    public function list(ProductoRepository $productoRepository)
    {
        $productos = $productoRepository->findAll();
        $array = [];
        foreach ($productos as $producto) {
            $array[] = [
                'id' => $producto->getId(),
                'name' => $producto->getName(),
                'descripcion' => $producto->getDescripcion()
            ];
        };
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => $array
        ]);
        return $response;
    }

    /**
     * @param EntityManagerInterface $em
     * @Rest\Post(path="producto_post")
     * @Rest\View(serializerGroups={"producto"},serializerEnableMaxDepthChecks=true)
     */
    public function postAction(EntityManagerInterface $em, Request $request, FilesystemOperator $defaultStorage)
    {
        $productoDto = new ProductoDto();
//        dd($productoDto);
        $form = $this->createForm(ProductoType::class, $productoDto);
        $form->handleRequest($request);
//        dd($productoDto);
        if ($form->isSubmitted() && $form->isValid()) {
            $extension = explode('/', mime_content_type($productoDto->base64Image));
//            dd($extension);
            $data = explode(',', $productoDto->base64Image);
//            dd($data);
            $filename = sprintf('%s.%s', uniqid('producto_', true), $extension[1]);
//dd($filename);
            $defaultStorage->write($filename, base64_decode($data[1]));
            $producto = new Producto();
            $producto->setName($productoDto->name);
            $producto->setDescripcion($productoDto->descripcion);
            $producto->setPrecio($productoDto->precio);

            $producto->setImage($filename);
            if($productoDto->categoria!==null){
                $categoria=$em->getRepository(Categoria::class)->find($productoDto->categoria);
                /** @var Categoria $categoria */
                $categoria->addProducto($producto);
            }
//            dd($producto);
//            $em->persist($producto);
            $em->flush();
            return $producto;
        }
        return $form;


    }
}
