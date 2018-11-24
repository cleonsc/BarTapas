<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use AppBundle\Entity\Tapa;
use AppBundle\Entity\Categoria;
use AppBundle\Entity\Ingrediente;
use AppBundle\Entity\Usuario;
use AppBundle\Form\UsuarioType;

class DefaultController extends Controller {

    /**
     * @Route("/nosotros", name="nosotros")
     */
    public function nosotrosAction(Request $request) {
        // replace this example code with whatever you need
        return $this->render('frontal/nosotros.html.twig');
    }

    /**
     * @Route("/contactar/{sitio}", name="contactar")
     */
    public function contactarAction(Request $request, $sitio = 'todos') {
        return $this->render('frontal/bares.html.twig', array("sitio" => $sitio));
    }

    /**
     * @Route("/tapa/{id}", name="tapa")
     */
    public function tapaAction(Request $request, $id = null) {
        if ($id != null) {
            $tapaRepository = $this->getDoctrine()->getRepository(Tapa::class);
            $tapa = $tapaRepository->find($id);
            return $this->render('frontal/tapa.html.twig', array("tapa" => $tapa));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/categoria/{id}", name="categoria")
     */
    public function catAction(Request $request, $id = null) {
        if ($id != null) {
            $categoriaRepository = $this->getDoctrine()->getRepository(Categoria::class);
            $categoria = $categoriaRepository->find($id);
            return $this->render('frontal/categoria.html.twig', array("categoria" => $categoria));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/ingrediente/{id}", name="ingrediente")
     */
    public function ingAction(Request $request, $id = null) {
        if ($id != null) {
            $ingredienteRepository = $this->getDoctrine()->getRepository(Ingrediente::class);
            $ingrediente = $ingredienteRepository->find($id);
            return $this->render('frontal/ingrediente.html.twig', array("ingrediente" => $ingrediente));
        } else {
            return $this->redirectToRoute('homepage');
        }
    }

    /**
     * @Route("/{pagina}", name="homepage")
     */
    public function homeAction(Request $request, $pagina = 1) {

        //Recuperar el repositorio de la tabla contra la DB
        $tapaRepository = $this->getDoctrine()->getRepository(Tapa::class);

        //obtenemos el listado de tapas
        $tapas = $tapaRepository->paginaTapas($pagina);

        // replace this example code with whatever you need
        return $this->render('frontal/index.html.twig', array("tapas" => $tapas, "paginaActual" => $pagina));
    }

    /**
     * @Route("/registro/", name="registro")
     * Usamos la barra del final de registro para que no genere conflictos con la ruta del index.
     */
    public function registroAction(Request $request, UserPasswordEncoderInterface $passwordEncoder) {


        $usuario = new Usuario();
        //construyendo el formulario
        $form = $this->createForm(UsuarioType::class, $usuario);

        //Recogemos la información
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($usuario, $usuario->getPlainPassword());
            $usuario->setPassword($password);
            //3.b $username = $email
            $usuario->setUsername($usuario->getEmail());
            //3..c $roles
            $usuario->setRoles(array('ROLE_USER'));
            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($usuario);
            $entityManager->flush();
            return $this->redirectToRoute('login');
        }

        // replace this example code with whatever you need
        return $this->render('frontal/registro.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/login/", name="login")
     */
    public function loginAction(Request $request, AuthenticationUtils $authenticationUtils) {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('frontal/login.html.twig', array(
                    'last_username' => $lastUsername,
                    'error' => $error,
        ));
    }

}
