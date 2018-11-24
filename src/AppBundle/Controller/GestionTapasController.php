<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\TapaType;
use AppBundle\Entity\Tapa;
use AppBundle\Entity\Categoria;
use AppBundle\Entity\Ingrediente;
use AppBundle\Form\CategoriaType;
use AppBundle\Form\IngredienteType;

/**
 * @Route("/gestionTapas")   // ruta que que se antepone a cada ruta de los action
 */
class GestionTapasController extends Controller {

    /**
     * @Route("/nuevaTapa", name="nuevaTapa")
     */
    public function nuevaTapaAction(Request $request) {
        //testeamos que nos trae el objeto $request

        /* if(!is_null($request)){
          $datos = $request->request->all();
          var_dump($datos);
          } */

        $tapa = new Tapa();
        //construyendo el formulario               
        $form = $this->createForm(TapaType::class, $tapa);

        //Recogemos la información
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //rellenar el entity tapa
            $tapa = $form->getData();

            //Iniciamos el guardado de la foto
            $fotoFile = $tapa->getFoto();
            //Generamos un nombre unico par el archivo
            $fileName = $this->generateUniqueFileName() . '.' . $fotoFile->guessExtension();

            //movemos el archivo de temporal al disco duro del servidor
            $fotoFile->move(
                    $this->getParameter('tapaImg_directory'), $fileName
            );

            //le ponemos datos estaticos para que funcione por ahora            
            $tapa->setFoto($fileName);
            $tapa->setFechaCreacion(new \DateTime);

            //Almacenar nueva tapa
            $em = $this->getDoctrine()->getManager();
            $em->persist($tapa);
            $em->flush();
            return $this->redirectToRoute('tapa', array('id' => $tapa->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('gestionTapas/nuevaTapa.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/nuevaCategoria", name="nuevaCategoria")
     */
    public function nuevaCatAction(Request $request) {
        $categoria = new Categoria();
        //construyendo el formulario               
        $form = $this->createForm(CategoriaType::class, $categoria);

        //Recogemos la información
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //rellenar el entity Categotia
            $categoria = $form->getData();
            $fotoFile = $categoria->getFoto();

            //Generamos un nombre unico par el archivo
            $fileName = $this->generateUniqueFileName() . '.' . $fotoFile->guessExtension();

            //movemos el archivo de temporal al disco duro del servidor
            $fotoFile->move(
                    $this->getParameter('tapaImg_directory'), $fileName
            );

            //le ponemos datos estaticos para que funcione por ahora            
            $categoria->setFoto($fileName);

            //Almacenar nueva tapa
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoria);
            $em->flush();
            return $this->redirectToRoute('categoria', array('id' => $categoria->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('gestionTapas/nuevaCategoria.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/nuevoIngrediente", name="nuevoIngrediente")
     */
    public function nuevoIngrAction(Request $request) {
        $ingrediente = new Ingrediente();
        //construyendo el formulario               
        $form = $this->createForm(IngredienteType::class, $ingrediente);

        //Recogemos la información
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //rellenar el entity Ingrediente
            $ingrediente = $form->getData();

            //Almacenar nuevo ingrediente
            $em = $this->getDoctrine()->getManager();
            $em->persist($ingrediente);
            $em->flush();
            return $this->redirectToRoute('ingrediente', array('id' => $ingrediente->getId()));
        }

        // replace this example code with whatever you need
        return $this->render('gestionTapas/nuevoIngrediente.html.twig', array('form' => $form->createView()));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName() {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

}