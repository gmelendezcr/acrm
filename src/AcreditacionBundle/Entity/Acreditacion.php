<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acreditacion
 *
 * @ORM\Table(name="ACREDITACION")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\AcreditacionRepository")
 */
class Acreditacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ACREDITACION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idAcreditacion;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="CentroEducativo", inversedBy="acreditaciones")
     * @ORM\JoinColumn(name="ID_CENTRO_EDUCATIVO",referencedColumnName="ID_CENTRO_EDUCATIVO")
     */
    private $idCentroEducativo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_REGISTRO", type="datetime")
     */
    private $fechaRegistro;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_INICIO", type="datetime")
     */
    private $fechaInicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FECHA_FIN", type="datetime")
     */
    private $fechaFin;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="EstadoAcreditacion", inversedBy="acreditaciones")
     * @ORM\JoinColumn(name="ID_ESTADO_ACREDITACION",referencedColumnName="ID_ESTADO_ACREDITACION")
     */
    private $idEstadoAcreditacion;


    /**
     * Get idAcreditacion
     *
     * @return int
     */
    public function getIdAcreditacion()
    {
        return $this->idAcreditacion;
    }

    /**
     * Set idCentroEducativo
     *
     * @param integer $idCentroEducativo
     *
     * @return Acreditacion
     */
    public function setIdCentroEducativo($idCentroEducativo)
    {
        $this->idCentroEducativo = $idCentroEducativo;

        return $this;
    }

    /**
     * Get idCentroEducativo
     *
     * @return int
     */
    public function getIdCentroEducativo()
    {
        return $this->idCentroEducativo;
    }

    /**
     * Set fechaRegistro
     *
     * @param \DateTime $fechaRegistro
     *
     * @return Acreditacion
     */
    public function setFechaRegistro($fechaRegistro)
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    /**
     * Get fechaRegistro
     *
     * @return \DateTime
     */
    public function getFechaRegistro()
    {
        return $this->fechaRegistro;
    }

    /**
     * Set fechaInicio
     *
     * @param \DateTime $fechaInicio
     *
     * @return Acreditacion
     */
    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;

        return $this;
    }

    /**
     * Get fechaInicio
     *
     * @return \DateTime
     */
    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    /**
     * Set fechaFin
     *
     * @param \DateTime $fechaFin
     *
     * @return Acreditacion
     */
    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;

        return $this;
    }

    /**
     * Get fechaFin
     *
     * @return \DateTime
     */
    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    /**
     * Set idEstadoAcreditacion
     *
     * @param  \AcreditacionBundle\Entity\EstadoAcreditacion $idEstadoAcreditacion
     *
     * @return Acreditacion
     */
    public function setidEstadoAcreditacion(\AcreditacionBundle\Entity\EstadoAcreditacion $idEstadoAcreditacion)
    {
        $this->idEstadoAcreditacion = $idEstadoAcreditacion;

        return $this;
    }

    /**
     * Get idEstadoAcreditacion
     *
     * @return \AcreditacionBundle\Entity\EstadoAcreditacion
     */
    public function getidEstadoAcreditacion()
    {
        return $this->idEstadoAcreditacion;
    }
}
