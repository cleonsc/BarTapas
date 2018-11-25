<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use AppBundle\Entity\Categoria;

/**
 * @Route("/api")
 */
class ApiController extends Controller {

    function catToArray($categoria) {
        $categoriaArray = array();
        $categoriaArray['id'] = $categoria->getId();
        $categoriaArray['nombre'] = $categoria->getNombre();
        $categoriaArray['descripcion'] = $categoria->getDescripcion();
        return $categoriaArray;
    }

    function catsToArray($categorias) {
        $categoriasArray = array();
        foreach ($categorias as $categoria) {
            $categoriasArray[] = $this->catToArray($categoria);
        }
        return $categoriasArray;
    }

    /**
     * @Route("/listarCategorias", methods={"GET"})
     */
    public function listarCategoriasAction() {
        $repository = $this->getDoctrine()->getRepository(Categoria::class);
        $categorias = $repository->findAll();

        $response = new JsonResponse($this->catsToArray($categorias));
        return $response;
    }

    /**
     * @Route("/insertarCategoria/{nombre}/{descripcion}", methods={"POST"})
     */
    public function insertarCategoriaAction($nombre = "", $descripcion = "") {
        if (strlen($nombre) > 0) {
            $categoria = new Categoria();
            $categoria->setNombre($nombre);
            $categoria->setDescripcion($descripcion);
            $categoria->setFoto("");
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoria);
            $em->flush();

            $response = new JsonResponse($this->catToArray($categoria));
            return $response;
        } else {
            throw new BadRequestHttpException("Falta nombre", null, 400);
        }
    }

}
