<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Indicador
 *
 * @ORM\Table(name="INDICADOR")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\IndicadorRepository")
 */
class Indicador
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_INDICADOR", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idIndicador;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_INDICADOR", type="string", length=20, unique=true)
     */
    private $codIndicador;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_INDICADOR", type="string", length=200)
     */
    private $nbrIndicador;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta",mappedBy="idIndicador")
     */
    private $preguntas;

    /**
     * @ORM\OneToMany(targetEntity="ViewFormularioPorCentroEducativoIndicadorPonderacion",mappedBy="idIndicador")
     */
    private $formulariosPorCentroEducativoIndicadorPonderacion;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formulariosPorCentroEducativoSeccionPonderacion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idIndicador
     *
     * @return integer 
     */
    public function getIdIndicador()
    {
        return $this->idIndicador;
    }

    /**
     * Set codIndicador
     *
     * @param string $codIndicador
     * @return Indicador
     */
    public function setCodIndicador($codIndicador)
    {
        $this->codIndicador = $codIndicador;

        return $this;
    }

    /**
     * Get codIndicador
     *
     * @return string 
     */
    public function getCodIndicador()
    {
        return $this->codIndicador;
    }

    /**
     * Set nbrIndicador
     *
     * @param string $nbrIndicador
     * @return Indicador
     */
    public function setNbrIndicador($nbrIndicador)
    {
        $this->nbrIndicador = $nbrIndicador;

        return $this;
    }

    /**
     * Get nbrIndicador
     *
     * @return string 
     */
    public function getNbrIndicador()
    {
        return $this->nbrIndicador;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Indicador
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
     * Add preguntas
     *
     * @param \AcreditacionBundle\Entity\Pregunta $preguntas
     * @return Indicador
     */
    public function addPreguntas(\AcreditacionBundle\Entity\Pregunta $preguntas)
    {
        $this->preguntas[] = $preguntas;

        return $this;
    }

    /**
     * Remove preguntas
     *
     * @param \AcreditacionBundle\Entity\Pregunta $preguntas
     */
    public function removePreguntas(\AcreditacionBundle\Entity\Pregunta $preguntas)
    {
        $this->preguntas->removeElement($preguntas);
    }

    /**
     * Add formulariosPorCentroEducativoIndicadorPonderacion
     *
     * @param \AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion
     * @return Indicador
     */
    public function addFormulariosPorCentroEducativoIndicadorPonderacion(\AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion)
    {
        $this->formulariosPorCentroEducativoIndicadorPonderacion[] = $formulariosPorCentroEducativoIndicadorPonderacion;

        return $this;
    }

    /**
     * Remove formulariosPorCentroEducativoIndicadorPonderacion
     *
     * @param \AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion
     */
    public function removeFormulariosPorCentroEducativoIndicadorPonderacion(\AcreditacionBundle\Entity\ViewFormularioPorCentroEducativoIndicadorPonderacion $formulariosPorCentroEducativoIndicadorPonderacion)
    {
        $this->formulariosPorCentroEducativoIndicadorPonderacion->removeElement($formulariosPorCentroEducativoIndicadorPonderacion);
    }

    /**
     * Get formulariosPorCentroEducativoIndicadorPonderacion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormulariosPorCentroEducativoIndicadorPonderacion()
    {
        return $this->formulariosPorCentroEducativoIndicadorPonderacion;
    }
}
