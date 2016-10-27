<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZonaCentroEducativo
 *
 * @ORM\Table(name="ZONA_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\ZonaCentroEducativoRepository")
 */
class ZonaCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ZONA_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idZonaCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_ZONA_CENTRO_EDUCATIVO", type="string", length=20, unique=true)
     */
    private $codZonaCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_ZONA_CENTRO_EDUCATIVO", type="string", length=100)
     */
    private $nbrZonaCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="CentroEducativo",mappedBy="idZonaCentroEducativo")
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
     * Get idZonaCentroEducativo
     *
     * @return int
     */
    public function getIdZonaCentroEducativo()
    {
        return $this->idZonaCentroEducativo;
    }

    /**
     * Set codZonaCentroEducativo
     *
     * @param string $codZonaCentroEducativo
     *
     * @return ZonaCentroEducativo
     */
    public function setCodZonaCentroEducativo($codZonaCentroEducativo)
    {
        $this->codZonaCentroEducativo = $codZonaCentroEducativo;

        return $this;
    }

    /**
     * Get codZonaCentroEducativo
     *
     * @return string
     */
    public function getCodZonaCentroEducativo()
    {
        return $this->codZonaCentroEducativo;
    }

    /**
     * Set nbrZonaCentroEducativo
     *
     * @param string $nbrZonaCentroEducativo
     *
     * @return ZonaCentroEducativo
     */
    public function setNbrZonaCentroEducativo($nbrZonaCentroEducativo)
    {
        $this->nbrZonaCentroEducativo = $nbrZonaCentroEducativo;

        return $this;
    }

    /**
     * Get nbrZonaCentroEducativo
     *
     * @return string
     */
    public function getNbrZonaCentroEducativo()
    {
        return $this->nbrZonaCentroEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     *
     * @return ZonaCentroEducativo
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
     * @return ZonaCentroEducativo
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
        return $this->getNbrZonaCentroEducativo();
    }
}
