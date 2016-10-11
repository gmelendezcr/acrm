<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ViewFormularioPorCentroEducativoIndicadorPonderacion
 *
 * @ORM\Table(name="VIEW_FORMULARIO_POR_CENTRO_EDUCATIVO_INDICADOR_PONDERACION")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\ViewFormularioPorCentroEducativoIndicadorPonderacionRepository")
 */
class ViewFormularioPorCentroEducativoIndicadorPonderacion
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
     * @ORM\ManyToOne(targetEntity="FormularioPorCentroEducativo", inversedBy="formulariosPorCentroEducativoIndicadorPonderacion")
     * @ORM\JoinColumn(name="ID_FORMULARIO_POR_CENTRO_EDUCATIVO",referencedColumnName="ID_FORMULARIO_POR_CENTRO_EDUCATIVO")
     */
    private $idFormularioPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Indicador", inversedBy="formulariosPorCentroEducativoIndicadorPonderacion")
     * @ORM\JoinColumn(name="ID_INDICADOR",referencedColumnName="ID_INDICADOR")
     */
    private $idIndicador;

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
     * @param integer $idFormularioPorCentroEducativo
     *
     * @return ViewFormularioPorCentroEducativoIndicadorPonderacion
     */
    public function setIdFormularioPorCentroEducativo($idFormularioPorCentroEducativo)
    {
        $this->idFormularioPorCentroEducativo = $idFormularioPorCentroEducativo;

        return $this;
    }

    /**
     * Get idFormularioPorCentroEducativo
     *
     * @return int
     */
    public function getIdFormularioPorCentroEducativo()
    {
        return $this->idFormularioPorCentroEducativo;
    }

    /**
     * Set idIndicador
     *
     * @param  \AcreditacionBundle\Entity\Indicador $idIndicador
     * @return ViewFormularioPorCentroEducativoIndicadorPonderacion
     */
    public function setIdIndicador(\AcreditacionBundle\Entity\Indicador $idIndicador)
    {
        $this->idIndicador = $idIndicador;

        return $this;
    }

    /**
     * Get idIndicador
     *
     * @return \AcreditacionBundle\Entity\Indicador
     */
    public function getIdIndicador()
    {
        return $this->idIndicador;
    }

    /**
     * Set ponderacionGanada
     *
     * @param string $ponderacionGanada
     *
     * @return ViewFormularioPorCentroEducativoIndicadorPonderacion
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
