<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ReservaType;
use AppBundle\Entity\Usuario;
use AppBundle\Entity\Reserva;

/**
 * @Route("/reservas")   // ruta que que se antepone a cada ruta de los action
 */
class GestionReservasController extends Controller {

    /**
     * @Route("/nueva/{id}", name="nuevaReserva")
     */
    public function nuevaReservaAction(Request $request, $id = null) {

        if ($id) {
            $repository = $this->getDoctrine()->getRepository(Reserva::class);
            $reserva = $repository->find($id);
        } else {
            $reserva = new Reserva();
        }
        //construyendo el formulario
        $form = $this->createForm(ReservaType::class, $reserva);

        //Recogemos la información llenada en el formulario
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //obtenemos el usuario desde los datos del usuario logueado
            $usuario = $this->getUser();
            //seteamos el usuario a la reserva
            $reserva->setUsuario($usuario);

            //Almacenar nueva tapa
            $em = $this->getDoctrine()->getManager();
            $em->persist($reserva);
            $em->flush();
            return $this->redirectToRoute('reservas');
        }

        // replace this example code with whatever you need
        return $this->render('gestionReservas/nuevaReserva.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/reservas", name="reservas")
     */
    public function reservasAction(Request $request) {
        $repository = $this->getDoctrine()->getRepository(Reserva::class);
        $reservas = $repository->findByUsuario($this->getUser());
        return $this->render('gestionReservas/reservas.html.twig', array('reservas' => $reservas));
    }

    /**
     * @Route("/borrar/{id}", name="borrarReserva")
     */
    public function borrarReservaAction(Request $request, $id = null) {
        if ($id) {
            //Busqueda de la reserva
            $repository = $this->getDoctrine()->getRepository(Reserva::class);
            $reserva = $repository->find($id);

            //El borrado
            $em = $this->getDoctrine()->getManager();
            $em->remove($reserva);
            $em->flush();
        }
        return $this->redirectToRoute("reservas");
    }

}
