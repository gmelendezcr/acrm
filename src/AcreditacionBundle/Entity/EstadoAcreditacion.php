<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EstadoAcreditacion
 *
 * @ORM\Table(name="ESTADO_ACREDITACION")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\EstadoAcreditacionRepository")
 */
class EstadoAcreditacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ESTADO_ACREDITACION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idEstadoAcreditacion;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_ESTADO_ACREDITACION", type="string", length=2)
     */
    private $codEstadoAcreditacion;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_ESTADO_ACREDITACION", type="string", length=100)
     */
    private $nbrEstadoAcreditacion;

    /**
     * @var int
     *
     * @ORM\Column(name="ANIOS_VIGENCIA", type="smallint")
     */
    private $aniosVigencia;

    /**
     * @ORM\OneToMany(targetEntity="Acreditacion",mappedBy="idEstadoAcreditacion")
     */
    private $acreditaciones;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->acreditaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idEstadoAcreditacion
     *
     * @return int
     */
    public function getIdEstadoAcreditacion()
    {
        return $this->idEstadoAcreditacion;
    }

    /**
     * Set codEstadoAcreditacion
     *
     * @param string $codEstadoAcreditacion
     *
     * @return EstadoAcreditacion
     */
    public function setCodEstadoAcreditacion($codEstadoAcreditacion)
    {
        $this->codEstadoAcreditacion = $codEstadoAcreditacion;

        return $this;
    }

    /**
     * Get codEstadoAcreditacion
     *
     * @return string
     */
    public function getCodEstadoAcreditacion()
    {
        return $this->codEstadoAcreditacion;
    }

    /**
     * Set nbrEstadoAcreditacion
     *
     * @param string $nbrEstadoAcreditacion
     *
     * @return EstadoAcreditacion
     */
    public function setNbrEstadoAcreditacion($nbrEstadoAcreditacion)
    {
        $this->nbrEstadoAcreditacion = $nbrEstadoAcreditacion;

        return $this;
    }

    /**
     * Get nbrEstadoAcreditacion
     *
     * @return string
     */
    public function getNbrEstadoAcreditacion()
    {
        return $this->nbrEstadoAcreditacion;
    }

    /**
     * Set aniosVigencia
     *
     * @param integer $aniosVigencia
     *
     * @return EstadoAcreditacion
     */
    public function setAniosVigencia($aniosVigencia)
    {
        $this->aniosVigencia = $aniosVigencia;

        return $this;
    }

    /**
     * Get aniosVigencia
     *
     * @return int
     */
    public function getAniosVigencia()
    {
        return $this->aniosVigencia;
    }

    /**
     * Add acreditaciones
     *
     * @param \AcreditacionBundle\Entity\Acreditacion $acreditaciones
     * @return EstadoAcreditacion
     */
    public function addAcreditaciones(\AcreditacionBundle\Entity\Acreditacion $acreditaciones)
    {
        $this->acreditaciones[] = $acreditaciones;

        return $this;
    }

    /**
     * Remove acreditaciones
     *
     * @param \AcreditacionBundle\Entity\Acreditacion $acreditaciones
     */
    public function removeAcreditaciones(\AcreditacionBundle\Entity\Acreditacion $acreditaciones)
    {
        $this->acreditaciones->removeElement($acreditaciones);
    }

    /**
     * Get acreditaciones
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAcreditaciones()
    {
        return $this->acreditaciones;
    }

    public function __toString(){
        return $this->getCodEstadoAcreditacion() . ' - ' . $this->getNbrEstadoAcreditacion();
    }
}
