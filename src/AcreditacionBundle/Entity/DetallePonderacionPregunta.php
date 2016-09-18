<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetallePonderacionPregunta
 *
 * @ORM\Table(name="DETALLE_PONDERACION_PREGUNTA")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\DetallePonderacionPreguntaRepository")
 */
class DetallePonderacionPregunta
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_DETALLE_PONDERACION_PREGUNTA", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idDetallePonderacionPregunta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Pregunta", inversedBy="detallesPonderacionPregunta")
     * @ORM\JoinColumn(name="ID_PREGUNTA",referencedColumnName="ID_PREGUNTA")
     */
    private $idPregunta;

    /**
     * @var int
     *
     * @ORM\Column(name="VALOR_MINIMO_RANGO", type="integer")
     */
    private $valorMinimoRango;

    /**
     * @var int
     *
     * @ORM\Column(name="VALOR_MAXIMO_RANGO", type="integer")
     */
    private $valorMaximoRango;

    /**
     * @var string
     *
     * @ORM\Column(name="PONDERACION", type="decimal", precision=6, scale=2)
     */
    private $ponderacion;


    /**
     * Get idDetallePonderacionPregunta
     *
     * @return integer 
     */
    public function getIdDetallePonderacionPregunta()
    {
        return $this->idDetallePonderacionPregunta;
    }

    /**
     * Set idPregunta
     *
     * @param  \AcreditacionBundle\Entity\Pregunta $idPregunta
     * @return DetallePonderacionPregunta
     */
    public function setIdPregunta(\AcreditacionBundle\Entity\Pregunta $idPregunta)
    {
        $this->idPregunta = $idPregunta;

        return $this;
    }

    /**
     * Get idPregunta
     *
     * @return \AcreditacionBundle\Entity\Pregunta
     */
    public function getIdPregunta()
    {
        return $this->idPregunta;
    }

    /**
     * Set valorMinimoRango
     *
     * @param integer $valorMinimoRango
     * @return DetallePonderacionPregunta
     */
    public function setValorMinimoRango($valorMinimoRango)
    {
        $this->valorMinimoRango = $valorMinimoRango;

        return $this;
    }

    /**
     * Get valorMinimoRango
     *
     * @return integer 
     */
    public function getValorMinimoRango()
    {
        return $this->valorMinimoRango;
    }

    /**
     * Set valorMaximoRango
     *
     * @param integer $valorMaximoRango
     * @return DetallePonderacionPregunta
     */
    public function setValorMaximoRango($valorMaximoRango)
    {
        $this->valorMaximoRango = $valorMaximoRango;

        return $this;
    }

    /**
     * Get valorMaximoRango
     *
     * @return integer 
     */
    public function getValorMaximoRango()
    {
        return $this->valorMaximoRango;
    }

    /**
     * Set ponderacion
     *
     * @param string $ponderacion
     * @return DetallePonderacionPregunta
     */
    public function setPonderacion($ponderacion)
    {
        $this->ponderacion = $ponderacion;

        return $this;
    }

    /**
     * Get ponderacion
     *
     * @return string 
     */
    public function getPonderacion()
    {
        return $this->ponderacion;
    }
}
