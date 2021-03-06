<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RespuestaPorFormularioPorCentroEducativo
 *
 * @ORM\Table(name="RESPUESTA_POR_FORMULARIO_POR_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\RespuestaPorFormularioPorCentroEducativoRepository")
 */
class RespuestaPorFormularioPorCentroEducativo
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
     * @ORM\ManyToOne(targetEntity="FormularioPorCentroEducativo", inversedBy="respuestasPorFormularioPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_FORMULARIO_POR_CENTRO_EDUCATIVO",referencedColumnName="ID_FORMULARIO_POR_CENTRO_EDUCATIVO")
     */
    private $idFormularioPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Pregunta", inversedBy="respuestasPorFormularioPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_PREGUNTA",referencedColumnName="ID_PREGUNTA")
     */
    private $idPregunta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="OpcionRespuesta", inversedBy="respuestasPorFormularioPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_OPCION_RESPUESTA",referencedColumnName="ID_OPCION_RESPUESTA")
     */
    private $idOpcionRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="VALOR_RESPUESTA", type="string", length=5000, nullable=true)
     */
    private $valorRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_NO_APLICA", type="string", length=1, nullable=true, columnDefinition="CHAR(1) NULL CHECK (OPCION_NO_APLICA IN ('S','N'))")
     */
    private $opcionNoAplica;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_OTRO_TEXTO", type="string", length=100, nullable=true)
     */
    private $opcionOtroTexto;

    /**
     * @var string
     *
     * @ORM\Column(name="REVISAR", type="string", length=1, nullable=true)
     */
    private $revisar;

    /**
     * @var string
     *
     * @ORM\Column(name="PONDERACION_GANADA", type="decimal", precision=6, scale=2, nullable=true)
     */
    private $ponderacionGanada;


    /**
     * Get idRespuestaPorFormularioPorCentroEducativo
     *
     * @return integer 
     */
    public function getIdRespuestaPorFormularioPorCentroEducativo()
    {
        return $this->id;
    }

    /**
     * Set idFormularioPorCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\FormularioPorCentroEducativo $idFormularioPorCentroEducativo
     * @return RespuestaPorFormularioPorCentroEducativo
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
     * Set idPregunta
     *
     * @param  \AcreditacionBundle\Entity\Pregunta $idPregunta
     * @return RespuestaPorFormularioPorCentroEducativo
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
     * Set idOpcionRespuesta
     *
     * @param  \AcreditacionBundle\Entity\OpcionRespuesta $idOpcionRespuesta
     * @return RespuestaPorFormularioPorCentroEducativo
     */
    public function setIdOpcionRespuesta(\AcreditacionBundle\Entity\OpcionRespuesta $idOpcionRespuesta)
    {
        $this->idOpcionRespuesta = $idOpcionRespuesta;

        return $this;
    }

    /**
     * Get idOpcionRespuesta
     *
     * @return \AcreditacionBundle\Entity\OpcionRespuesta
     */
    public function getIdOpcionRespuesta()
    {
        return $this->idOpcionRespuesta;
    }

    /**
     * Set valorRespuesta
     *
     * @param string $valorRespuesta
     * @return RespuestaPorFormularioPorCentroEducativo
     */
    public function setValorRespuesta($valorRespuesta)
    {
        $this->valorRespuesta = $valorRespuesta;

        return $this;
    }

    /**
     * Get valorRespuesta
     *
     * @return string 
     */
    public function getValorRespuesta()
    {
        return $this->valorRespuesta;
    }

    /**
     * Set opcionNoAplica
     *
     * @param string $opcionNoAplica
     * @return Pregunta
     */
    public function setOpcionNoAplica($opcionNoAplica)
    {
        $this->opcionNoAplica = $opcionNoAplica;

        return $this;
    }

    /**
     * Get opcionNoAplica
     *
     * @return string 
     */
    public function getOpcionNoAplica()
    {
        return $this->opcionNoAplica;
    }

    /**
     * Set opcionOtroTexto
     *
     * @param string $opcionOtroTexto
     * @return Pregunta
     */
    public function setOpcionOtroTexto($opcionOtroTexto)
    {
        $this->opcionOtroTexto = $opcionOtroTexto;

        return $this;
    }

    /**
     * Get opcionOtroTexto
     *
     * @return string 
     */
    public function getOpcionOtroTexto()
    {
        return $this->opcionOtroTexto;
    }

    /**
     * Set revisar
     *
     * @param string $revisar
     * @return RespuestaPorFormularioPorCentroEducativo
     */
    public function setRevisar($revisar)
    {
        $this->revisar = $revisar;

        return $this;
    }

    /**
     * Get revisar
     *
     * @return string 
     */
    public function getRevisar()
    {
        return $this->revisar;
    }

    /**
     * Set ponderacionGanada
     *
     * @param string $ponderacionGanada
     * @return DetallePonderacionPregunta
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

    public function vaciarPropiedades()
    {
        $detalle='';
        $detalle.='centro educativo: ' . mb_substr($this->getIdFormularioPorCentroEducativo()->getIdCentroEducativo()->__toString(),0,100) . "\n";
        $detalle.='formulario: ' . $this->getIdFormularioPorCentroEducativo()->__toString() . "\n";
        if($this->getIdOpcionRespuesta()){
            $detalle.='pregunta: ' . mb_substr($this->getIdOpcionRespuesta()->getIdPregunta()->getIdPreguntaPadre()->__toString(),0,100) . "\n";
            $detalle.='fila: ' . $this->getIdOpcionRespuesta()->__toString() . "\n";
            $detalle.='columna: ' . $this->getIdPregunta()->__toString() . "\n";
        }
        else{
            $detalle.='pregunta: ' . mb_substr($this->getIdPregunta()->__toString(),0,100) . "\n";
        }
        $detalle.='respuesta: ' . (mb_strlen($this->getValorRespuesta())>200?mb_substr($this->getValorRespuesta(),0,200) . '...':$this->getValorRespuesta()) . "\n";
        $detalle.='revisar: ' . ($this->getRevisar()=='S'?'Sí':'') . "\n";
        return $detalle;
    }
}
