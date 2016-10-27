<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ModalidadCentroEducativo
 *
 * @ORM\Table(name="MODALIDAD_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\ModalidadCentroEducativoRepository")
 */
class ModalidadCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_MODALIDAD_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idModalidadCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_MODALIDAD_CENTRO_EDUCATIVO", type="string", length=20, unique=true)
     */
    private $codModalidadCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_MODALIDAD_CENTRO_EDUCATIVO", type="string", length=100)
     */
    private $nbrModalidadCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="CentroEducativo",mappedBy="idModalidadCentroEducativo")
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
     * Get idModalidadCentroEducativo
     *
     * @return int
     */
    public function getIdModalidadCentroEducativo()
    {
        return $this->idModalidadCentroEducativo;
    }

    /**
     * Set codModalidadCentroEducativo
     *
     * @param string $codModalidadCentroEducativo
     *
     * @return ModalidadCentroEducativo
     */
    public function setCodModalidadCentroEducativo($codModalidadCentroEducativo)
    {
        $this->codModalidadCentroEducativo = $codModalidadCentroEducativo;

        return $this;
    }

    /**
     * Get codModalidadCentroEducativo
     *
     * @return string
     */
    public function getCodModalidadCentroEducativo()
    {
        return $this->codModalidadCentroEducativo;
    }

    /**
     * Set nbrModalidadCentroEducativo
     *
     * @param string $nbrModalidadCentroEducativo
     *
     * @return ModalidadCentroEducativo
     */
    public function setNbrModalidadCentroEducativo($nbrModalidadCentroEducativo)
    {
        $this->nbrModalidadCentroEducativo = $nbrModalidadCentroEducativo;

        return $this;
    }

    /**
     * Get nbrModalidadCentroEducativo
     *
     * @return string
     */
    public function getNbrModalidadCentroEducativo()
    {
        return $this->nbrModalidadCentroEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     *
     * @return ModalidadCentroEducativo
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
     * @return ModalidadCentroEducativo
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
        return $this->getNbrModalidadCentroEducativo();
    }
}
