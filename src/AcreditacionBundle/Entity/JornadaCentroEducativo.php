<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JornadaCentroEducativo
 *
 * @ORM\Table(name="JORNADA_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\JornadaCentroEducativoRepository")
 */
class JornadaCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_JORNADA_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idJornadaCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_JORNADA_CENTRO_EDUCATIVO", type="string", length=20, unique=true)
     */
    private $codJornadaCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_JORNADA_CENTRO_EDUCATIVO", type="string", length=100)
     */
    private $nbrJornadaCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="CentroEducativo",mappedBy="idJornadaCentroEducativo")
     */
    private $centrosEducativos;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->centrosEducativos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idJornadaCentroEducativo
     *
     * @return integer 
     */
    public function getIdJornadaCentroEducativo()
    {
        return $this->idJornadaCentroEducativo;
    }

    /**
     * Set codJornadaCentroEducativo
     *
     * @param string $codJornadaCentroEducativo
     * @return JornadaCentroEducativo
     */
    public function setCodJornadaCentroEducativo($codJornadaCentroEducativo)
    {
        $this->codJornadaCentroEducativo = $codJornadaCentroEducativo;

        return $this;
    }

    /**
     * Get codJornadaCentroEducativo
     *
     * @return string 
     */
    public function getCodJornadaCentroEducativo()
    {
        return $this->codJornadaCentroEducativo;
    }

    /**
     * Set nbrJornadaCentroEducativo
     *
     * @param string $nbrJornadaCentroEducativo
     * @return JornadaCentroEducativo
     */
    public function setNbrJornadaCentroEducativo($nbrJornadaCentroEducativo)
    {
        $this->nbrJornadaCentroEducativo = $nbrJornadaCentroEducativo;

        return $this;
    }

    /**
     * Get nbrJornadaCentroEducativo
     *
     * @return string 
     */
    public function getNbrJornadaCentroEducativo()
    {
        return $this->nbrJornadaCentroEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return JornadaCentroEducativo
     */
    public function setActivo($activo)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return string 
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Add centrosEducativos
     *
     * @param \AcreditacionBundle\Entity\CentroEducativo $centrosEducativos
     * @return JornadaCentroEducativo
     */
    public function addCentrosEducativos(\AcreditacionBundle\Entity\CentroEducativo $centrosEducativos)
    {
        $this->centrosEducativos[] = $centrosEducativos;

        return $this;
    }

    /**
     * Remove centrosEducativos
     *
     * @param \AcreditacionBundle\Entity\CentroEducativo $centrosEducativos
     */
    public function removeCentrosEducativos(\AcreditacionBundle\Entity\CentroEducativo $centrosEducativos)
    {
        $this->centrosEducativos->removeElement($centrosEducativos);
    }

    /**
     * Get centrosEducativos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosEducativos()
    {
        return $this->centrosEducativos;
    }
    public function __toString(){
        return $this->getNbrJornadaCentroEducativo() ;
    }
}
