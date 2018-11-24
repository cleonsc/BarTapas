<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Areas;

/**
 * @Route("/pruebas")   // ruta que que se antepone a cada ruta de los action
 */
class PruebasController extends Controller {

    /**
     * @Route("/test", name="testpage")
     */
    public function testAction() {
        //creo a la conexion a la base de sol.
        $db = $this->get('doctrine.dbal.externa_connection');
        //Busco las areas en db_sol
        $query = "SELECT * FROM areas";
        $areasArray = $db->query($query);

        //genero la conexion local (default)
        $em = $this->getDoctrine()->getManager();

        //Limpio la tabla para que los datos no se duliquen
        $queryDelete = $em->createQuery('DELETE AppBundle:Areas a');
        $queryDelete->execute();

        //recorro las areas que traigo de db_sol y las preparo para insertar en el local
        foreach ($areasArray as $valArea) {
            $area = new Areas();
            $area->setArid($valArea['arid']);
            $area->setArdesc($valArea['ardesc']);
            $area->setSaid($valArea['said']);
            $em->persist($area);
            $em->flush();
        }

        return $this->render('frontal/pruebas.html.twig', array("areas" => $areasArray));
    }

    /**
     * @Route("/sync", name="syncpage")
     */
    public function syncAction() {
        $db = $this->get('doctrine.dbal.externa_connection');

        $query = "SELECT * FROM areas order by arid";
        $areasArray = $db->query($query);

        $em = $this->getDoctrine()->getManager();

        foreach ($areasArray as $valArea) {
            $areasRepo = $em->getRepository("AppBundle:Areas");
            $areaActual = $areasRepo->findBy(array('arid' => $valArea['arid']));

            if ($areaActual) {
                $areaActual[0]->setArdesc($valArea['ardesc']);
                $areaActual[0]->setSaid($valArea['said']);
                $em->flush();
            } else {
                $area = new Areas();
                $area->setArid($valArea['arid']);
                $area->setArdesc($valArea['ardesc']);
                $area->setSaid($valArea['said']);
                $em->persist($area);
                $em->flush();
            }
        }

        return $this->render('frontal/pruebas.html.twig', array("areas" => $areasArray));
    }

    /**
     * @Route("/sub_querys", name="subquerys")
     */
    public function subQuerysAction() {
        $em = $this->getDoctrine()->getManager();
        $subQuery = $em->createQueryBuilder();

        $subQuery->select('a.said')
                ->from('AppBundle\Entity\Areas', 'a')
                ->where('a.said = 30');

        $queryPrincipal = $em->createQueryBuilder();
        $queryPrincipal->select('aa')
                ->from('AppBundle\Entity\Areas', 'aa')
                ->andwhere($queryPrincipal->expr()->notIn('aa.said', $subQuery->getDQL())
        );

        $query = $queryPrincipal->getQuery();
        dump($query);

//        return $query->getResult();
        dump($query->getResult());
//        die;
//        return $this->render('frontal/pruebas.html.twig');
    }

    /**
     * Este Metodo nos devuelve el resultado de una subquery
     *
     * Se revisan todas las tapas que cumplen con la condición de estar en la
     * categoria 1
     * @author John Doe <john.doe@example.com>
     * @param no recibe ningun parametro por ahora
     * @return array devuelve un arreglo con los datos de las tapas 
     *
     * @Route("/sub_tapa", name="subtapas")
     */
    public function subQuerys2Action() {
        $em = $this->getDoctrine()->getManager();
        $subQuery = $em->createQueryBuilder();

        $subQuery->select('t.id')
                ->from('AppBundle\Entity\Tapa', 't')
                ->where('t.categoria = 1');

        $queryPrincipal = $em->createQueryBuilder();
        $queryPrincipal->select('tt')
                ->from('AppBundle\Entity\Tapa', 'tt')
                ->innerJoin('AppBundle\Entity\Categoria', 'c')
                ->andwhere($queryPrincipal->expr()->notIn('tt.id', $subQuery->getDQL())
        );

        $query = $queryPrincipal->getQuery();
        dump($query);

//        return $query->getResult();
        dump($query->getResult());
//        die;
//        return $this->render('frontal/pruebas.html.twig');
    }

}
