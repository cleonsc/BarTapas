<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Areas
 *
 * @ORM\Table(name="areas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AreasRepository")
 */
class Areas {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="arid", type="integer")
     */
    private $arid;

    /**
     * @var string
     *
     * @ORM\Column(name="ardesc", type="string", length=50)
     */
    private $ardesc;

    /**
     * @var int
     *
     * @ORM\Column(name="said", type="smallint")
     */
    private $said;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set ardesc
     *
     * @param string $ardesc
     *
     * @return Areas
     */
    public function setArdesc($ardesc) {
        $this->ardesc = $ardesc;

        return $this;
    }

    /**
     * Get ardesc
     *
     * @return string
     */
    public function getArdesc() {
        return $this->ardesc;
    }

    /**
     * Set said
     *
     * @param integer $said
     *
     * @return Areas
     */
    public function setSaid($said) {
        $this->said = $said;

        return $this;
    }

    /**
     * Get said
     *
     * @return int
     */
    public function getSaid() {
        return $this->said;
    }


    /**
     * Set arid
     *
     * @param integer $arid
     *
     * @return Areas
     */
    public function setArid($arid)
    {
        $this->arid = $arid;

        return $this;
    }

    /**
     * Get arid
     *
     * @return integer
     */
    public function getArid()
    {
        return $this->arid;
    }
}
