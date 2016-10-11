<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ViewFormularioPorCentroEducativoSeccionPonderacion
 *
 * @ORM\Table(name="VIEW_FORMULARIO_POR_CENTRO_EDUCATIVO_SECCION_PONDERACION")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\ViewFormularioPorCentroEducativoSeccionPonderacionRepository")
 */
class ViewFormularioPorCentroEducativoSeccionPonderacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_RESPUESTA_POR_FORMULARIO_POR_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idRespuestaPorFormularioPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="FormularioPorCentroEducativo", inversedBy="formulariosPorCentroEducativoSeccionPonderacion")
     * @ORM\JoinColumn(name="ID_FORMULARIO_POR_CENTRO_EDUCATIVO",referencedColumnName="ID_FORMULARIO_POR_CENTRO_EDUCATIVO")
     */
    private $idFormularioPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Seccion", inversedBy="formulariosPorCentroEducativoSeccionPonderacion")
     * @ORM\JoinColumn(name="ID_SECCION",referencedColumnName="ID_SECCION")
     */
    private $idSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="PONDERACION_GANADA", type="decimal", precision=6, scale=2)
     */
    private $ponderacionGanada;


    /**
     * Get idRespuestaPorFormularioPorCentroEducativo
     *
     * @return int
     */
    public function getIdRespuestaPorFormularioPorCentroEducativo()
    {
        return $this->idRespuestaPorFormularioPorCentroEducativo;
    }

    /**
     * Set idFormularioPorCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\FormularioPorCentroEducativo $idFormularioPorCentroEducativo
     * @return ViewFormularioPorCentroEducativoSeccionPonderacion
     */
    public function setIdFormularioPorCentroEducativo(\AcreditacionBundle\Entity\FormularioPorCentroEducativo $idFormularioPorCentroEducativo)
    {
        $this->idFormularioPorCentroEducativo = $idFormularioPorCentroEducativo;

        return $this;
    }

    /**
     * Get idFormularioPorCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\FormularioPorCentroEducativo
     */
    public function getIdFormularioPorCentroEducativo()
    {
        return $this->idFormularioPorCentroEducativo;
    }

    /**
     * Set idSeccion
     *
     * @param  \AcreditacionBundle\Entity\Seccion $idSeccion
     * @return ViewFormularioPorCentroEducativoSeccionPonderacion
     */
    public function setIdSeccion(\AcreditacionBundle\Entity\Seccion $idSeccion)
    {
        $this->idSeccion = $idSeccion;

        return $this;
    }

    /**
     * Get idSeccion
     *
     * @return \AcreditacionBundle\Entity\Seccion
     */
    public function getIdSeccion()
    {
        return $this->idSeccion;
    }

    /**
     * Set ponderacionGanada
     *
     * @param string $ponderacionGanada
     *
     * @return ViewFormularioPorCentroEducativoSeccionPonderacion
     */
    public function setPonderacionGanada($ponderacionGanada)
    {
        $this->ponderacionGanada = $ponderacionGanada;

        return $this;
    }

    /**
     * Get ponderacionGanada
     *
     * @return string
     */
    public function getPonderacionGanada()
    {
        return $this->ponderacionGanada;
    }
}
