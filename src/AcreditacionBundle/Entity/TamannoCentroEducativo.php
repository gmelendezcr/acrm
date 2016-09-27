<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TamannoCentroEducativo
 *
 * @ORM\Table(name="TAMANNO_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\TamannoCentroEducativoRepository")
 */
class TamannoCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_TAMANNO_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idTamannoCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_TAMANNO_CENTRO_EDUCATIVO", type="string", length=20, unique=true)
     */
    private $codTamannoCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_TAMANNO_CENTRO_EDUCATIVO", type="string", length=100)
     */
    private $nbrTamannoCentroEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="CentroEducativo",mappedBy="idTamannoCentroEducativo")
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
     * Get idTamannoCentroEducativo
     *
     * @return integer 
     */
    public function getIdTamannoCentroEducativo()
    {
        return $this->idTamannoCentroEducativo;
    }

    /**
     * Set codTamannoCentroEducativo
     *
     * @param string $codTamannoCentroEducativo
     * @return TamannoCentroEducativo
     */
    public function setCodTamannoCentroEducativo($codTamannoCentroEducativo)
    {
        $this->codTamannoCentroEducativo = $codTamannoCentroEducativo;

        return $this;
    }

    /**
     * Get codTamannoCentroEducativo
     *
     * @return string 
     */
    public function getCodTamannoCentroEducativo()
    {
        return $this->codTamannoCentroEducativo;
    }

    /**
     * Set nbrTamannoCentroEducativo
     *
     * @param string $nbrTamannoCentroEducativo
     * @return TamannoCentroEducativo
     */
    public function setNbrTamannoCentroEducativo($nbrTamannoCentroEducativo)
    {
        $this->nbrTamannoCentroEducativo = $nbrTamannoCentroEducativo;

        return $this;
    }

    /**
     * Get nbrTamannoCentroEducativo
     *
     * @return string 
     */
    public function getNbrTamannoCentroEducativo()
    {
        return $this->nbrTamannoCentroEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return TamannoCentroEducativo
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
     * @return TamannoCentroEducativo
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
        return $this->getNbrTamannoCentroEducativo() ;
    }
}
