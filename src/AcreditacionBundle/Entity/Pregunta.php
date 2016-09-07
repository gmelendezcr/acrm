<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pregunta
 *
 * @ORM\Table(name="PREGUNTA")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\PreguntaRepository")
 */
class Pregunta
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_PREGUNTA", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idPregunta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Seccion", inversedBy="preguntas")
     * @ORM\JoinColumn(name="ID_SECCION",referencedColumnName="ID_SECCION")
     */
    private $idSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_PREGUNTA", type="string", length=20)
     */
    private $codPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_PREGUNTA", type="string", length=1000)
     */
    private $descripcionPregunta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="TipoPregunta", inversedBy="preguntas")
     * @ORM\JoinColumn(name="ID_TIPO_PREGUNTA",referencedColumnName="ID_TIPO_PREGUNTA")
     */
    private $idTipoPregunta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Catalogo", inversedBy="preguntas")
     * @ORM\JoinColumn(name="ID_CATALOGO",referencedColumnName="ID_CATALOGO")
     */
    private $idCatalogo;

    /**
     * @var int
     *
     * @ORM\Column(name="TEXTO_LONGITUD_MAXIMA", type="integer", nullable=true)
     */
    private $textoLongitudMaxima;

    /**
     * @var string
     *
     * @ORM\Column(name="TEXTO_MASCARA", type="string", length=255, nullable=true)
     */
    private $textoMascara;

    /**
     * @var string
     *
     * @ORM\Column(name="VALOR_MINIMO_RESPUESTA", type="string", length=10, nullable=true)
     */
    private $valorMinimoRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="VALOR_MAXIMO_RESPUESTA", type="string", length=10, nullable=true)
     */
    private $valorMaximoRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_OTRO", type="string", length=1, nullable=true, columnDefinition="CHAR(1) NULL CHECK (OPCION_OTRO IN ('S','N'))")
     */
    private $opcionOtro;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_OTRO_TEXTO", type="string", length=255, nullable=true)
     */
    private $opcionOtroTexto;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_OTRO_CAMPO_TEXTO", type="string", length=1, nullable=true, columnDefinition="CHAR(1) NULL CHECK (OPCION_OTRO IN ('S','N'))")
     */
    private $opcionOtroCampoTexto;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_NO_APLICA", type="string", length=1, nullable=true, columnDefinition="CHAR(1) NULL CHECK (OPCION_NO_APLICA IN ('S','N'))")
     */
    private $opcionNoAplica;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_NO_APLICA_TEXTO", type="string", length=255, nullable=true)
     */
    private $opcionNoAplicaTexto;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Pregunta", inversedBy="subPreguntas")
     * @ORM\JoinColumn(name="ID_PREGUNTA_PADRE",referencedColumnName="ID_PREGUNTA")
     */
    private $idPreguntaPadre;

    /**
     * @var string
     *
     * @ORM\Column(name="ENCABEZADO_SUB_PREGUNTAS", type="string", length=255, nullable=true)
     */
    private $encabezadoSubPreguntas;

    /**
     * @var string
     *
     * @ORM\Column(name="NUMERAR_SUB_PREGUNTAS", type="string", length=1, nullable=true)
     */
    private $numerarSubPreguntas;

    /**
     * @var int
     *
     * @ORM\Column(name="CANTIDAD_COLUMNAS_SUB_PREGUNTAS", type="integer", nullable=true)
     */
    private $cantidadColumnasSubPreguntas;

    /**
     * @var string
     *
     * @ORM\Column(name="PADRE_COMO_PREGUNTA", type="string", length=1, columnDefinition="CHAR(1) NULL CHECK (ACTIVO IN ('S','N'))")
     */
    private $padreComoPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="TOTALES_FILAS", type="string", length=1, nullable=true)
     */
    private $totalesFilas;

    /**
     * @var string
     *
     * @ORM\Column(name="TOTALES_COLUMNAS", type="string", length=1, nullable=true)
     */
    private $totalesColumnas;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="OpcionRespuesta",mappedBy="idPregunta")
     */
    private $opcionesRespuesta;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta",mappedBy="idPreguntaPadre")
     */
    private $subPreguntas;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->opcionesRespuesta = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subPreguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idPregunta
     *
     * @return integer 
     */
    public function getIdPregunta()
    {
        return $this->idPregunta;
    }

    /**
     * Set idSeccion
     *
     * @param  \AcreditacionBundle\Entity\Seccion $idSeccion
     * @return Pregunta
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
     * Set codPregunta
     *
     * @param string $codPregunta
     * @return Pregunta
     */
    public function setCodPregunta($codPregunta)
    {
        $this->codPregunta = $codPregunta;

        return $this;
    }

    /**
     * Get codPregunta
     *
     * @return string 
     */
    public function getCodPregunta()
    {
        return $this->codPregunta;
    }

    /**
     * Set descripcionPregunta
     *
     * @param string $descripcionPregunta
     * @return Pregunta
     */
    public function setDescripcionPregunta($descripcionPregunta)
    {
        $this->descripcionPregunta = $descripcionPregunta;

        return $this;
    }

    /**
     * Get descripcionPregunta
     *
     * @return string 
     */
    public function getDescripcionPregunta()
    {
        return $this->descripcionPregunta;
    }

    /**
     * Set idTipoPregunta
     *
     * @param  \AcreditacionBundle\Entity\TipoPregunta $idTipoPregunta
     * @return Pregunta
     */
    public function setIdTipoPregunta(\AcreditacionBundle\Entity\TipoPregunta $idTipoPregunta)
    {
        $this->idTipoPregunta = $idTipoPregunta;

        return $this;
    }

    /**
     * Get idTipoPregunta
     *
     * @return \AcreditacionBundle\Entity\TipoPregunta
     */
    public function getIdTipoPregunta()
    {
        return $this->idTipoPregunta;
    }


    /**
     * Set idCatalogo
     *
     * @param  \AcreditacionBundle\Entity\Catalogo $idCatalogo
     * @return Pregunta
     */
    public function setIdCatalogo(\AcreditacionBundle\Entity\Catalogo $idCatalogo)
    {
        $this->idCatalogo = $idCatalogo;

        return $this;
    }

    /**
     * Get idCatalogo
     *
     * @return \AcreditacionBundle\Entity\Catalogo
     */
    public function getIdCatalogo()
    {
        return $this->idCatalogo;
    }

    /**
     * Set textoLongitudMaxima
     *
     * @param integer $textoLongitudMaxima
     * @return Pregunta
     */
    public function setTextoLongitudMaxima($textoLongitudMaxima)
    {
        $this->textoLongitudMaxima = $textoLongitudMaxima;

        return $this;
    }

    /**
     * Get textoLongitudMaxima
     *
     * @return integer 
     */
    public function getTextoLongitudMaxima()
    {
        return $this->textoLongitudMaxima;
    }

    /**
     * Set textoMascara
     *
     * @param string $textoMascara
     * @return Pregunta
     */
    public function setTextoMascara($textoMascara)
    {
        $this->textoMascara = $textoMascara;

        return $this;
    }

    /**
     * Get textoMascara
     *
     * @return string 
     */
    public function getTextoMascara()
    {
        return $this->textoMascara;
    }

    /**
     * Set valorMinimoRespuesta
     *
     * @param string $valorMinimoRespuesta
     * @return Pregunta
     */
    public function setValorMinimoRespuesta($valorMinimoRespuesta)
    {
        $this->valorMinimoRespuesta = $valorMinimoRespuesta;

        return $this;
    }

    /**
     * Get valorMinimoRespuesta
     *
     * @return string 
     */
    public function getValorMinimoRespuesta()
    {
        return $this->valorMinimoRespuesta;
    }

    /**
     * Set valorMaximoRespuesta
     *
     * @param string $valorMaximoRespuesta
     * @return Pregunta
     */
    public function setValorMaximoRespuesta($valorMaximoRespuesta)
    {
        $this->valorMaximoRespuesta = $valorMaximoRespuesta;

        return $this;
    }

    /**
     * Get valorMaximoRespuesta
     *
     * @return string 
     */
    public function getValorMaximoRespuesta()
    {
        return $this->valorMaximoRespuesta;
    }

    /**
     * Set opcionOtro
     *
     * @param string $opcionOtro
     * @return Pregunta
     */
    public function setOpcionOtro($opcionOtro)
    {
        $this->opcionOtro = $opcionOtro;

        return $this;
    }

    /**
     * Get opcionOtro
     *
     * @return string 
     */
    public function getOpcionOtro()
    {
        return $this->opcionOtro;
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
     * Set opcionOtroCampoTexto
     *
     * @param string $opcionOtroCampoTexto
     * @return Pregunta
     */
    public function setOpcionOtroCampoTexto($opcionOtroCampoTexto)
    {
        $this->opcionOtroCampoTexto = $opcionOtroCampoTexto;

        return $this;
    }

    /**
     * Get opcionOtroCampoTexto
     *
     * @return string
     */
    public function getOpcionOtroCampoTexto()
    {
        return $this->opcionOtroCampoTexto;
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
     * Set opcionNoAplicaTexto
     *
     * @param string $opcionNoAplicaTexto
     * @return Pregunta
     */
    public function setOpcionNoAplicaTexto($opcionNoAplicaTexto)
    {
        $this->opcionNoAplicaTexto = $opcionNoAplicaTexto;

        return $this;
    }

    /**
     * Get opcionNoAplicaTexto
     *
     * @return string 
     */
    public function getOpcionNoAplicaTexto()
    {
        return $this->opcionNoAplicaTexto;
    }

    /**
     * Set idPreguntaPadre
     *
     * @param  \AcreditacionBundle\Entity\Pregunta $idPreguntaPadre
     * @return Pregunta
     */
    public function setIdPreguntaPadre(\AcreditacionBundle\Entity\Pregunta $idPreguntaPadre)
    {
        $this->idPreguntaPadre = $idPreguntaPadre;

        return $this;
    }

    /**
     * Get idPreguntaPadre
     *
     * @return \AcreditacionBundle\Entity\Pregunta
     */
    public function getIdPreguntaPadre()
    {
        return $this->idPreguntaPadre;
    }

    /**
     * Set encabezadoSubPreguntas
     *
     * @param string $encabezadoSubPreguntas
     * @return Pregunta
     */
    public function setEncabezadoSubPreguntas($encabezadoSubPreguntas)
    {
        $this->encabezadoSubPreguntas = $encabezadoSubPreguntas;

        return $this;
    }

    /**
     * Get encabezadoSubPreguntas
     *
     * @return string 
     */
    public function getEncabezadoSubPreguntas()
    {
        return $this->encabezadoSubPreguntas;
    }

    /**
     * Set numerarSubPreguntas
     *
     * @param string $numerarSubPreguntas
     * @return Pregunta
     */
    public function setNumerarSubPreguntas($numerarSubPreguntas)
    {
        $this->numerarSubPreguntas = $numerarSubPreguntas;

        return $this;
    }

    /**
     * Get numerarSubPreguntas
     *
     * @return string 
     */
    public function getNumerarSubPreguntas()
    {
        return $this->numerarSubPreguntas;
    }

    /**
     * Set cantidadColumnasSubPreguntas
     *
     * @param integer $cantidadColumnasSubPreguntas
     * @return Pregunta
     */
    public function setCantidadColumnasSubPreguntas($cantidadColumnasSubPreguntas)
    {
        $this->cantidadColumnasSubPreguntas = $cantidadColumnasSubPreguntas;

        return $this;
    }

    /**
     * Get cantidadColumnasSubPreguntas
     *
     * @return integer 
     */
    public function getCantidadColumnasSubPreguntas()
    {
        return $this->cantidadColumnasSubPreguntas;
    }

    /**
     * Set padreComoPregunta
     *
     * @param integer $padreComoPregunta
     * @return Pregunta
     */
    public function setPadreComoPregunta($padreComoPregunta)
    {
        $this->padreComoPregunta = $padreComoPregunta;

        return $this;
    }

    /**
     * Get padreComoPregunta
     *
     * @return integer
     */
    public function getPadreComoPregunta()
    {
        return $this->padreComoPregunta;
    }

    /**
     * Set totalesFilas
     *
     * @param string $totalesFilas
     * @return Pregunta
     */
    public function setTotalesFilas($totalesFilas)
    {
        $this->totalesFilas = $totalesFilas;

        return $this;
    }

    /**
     * Get totalesFilas
     *
     * @return string 
     */
    public function getTotalesFilas()
    {
        return $this->totalesFilas;
    }

    /**
     * Set totalesColumnas
     *
     * @param string $totalesColumnas
     * @return Pregunta
     */
    public function setTotalesColumnas($totalesColumnas)
    {
        $this->totalesColumnas = $totalesColumnas;

        return $this;
    }

    /**
     * Get totalesColumnas
     *
     * @return string 
     */
    public function getTotalesColumnas()
    {
        return $this->totalesColumnas;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Pregunta
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
     * Add opcionesRespuesta
     *
     * @param \AcreditacionBundle\Entity\OpcionRespuesta $opcionesRespuesta
     * @return Pregunta
     */
    public function addOpcionesRespuesta(\AcreditacionBundle\Entity\OpcionRespuesta $opcionesRespuesta)
    {
        $this->opcionesRespuesta[] = $opcionesRespuesta;

        return $this;
    }

    /**
     * Remove opcionesRespuesta
     *
     * @param \AcreditacionBundle\Entity\OpcionRespuesta $opcionesRespuesta
     */
    public function removeOpcionesRespuesta(\AcreditacionBundle\Entity\OpcionRespuesta $opcionesRespuesta)
    {
        $this->opcionesRespuesta->removeElement($opcionesRespuesta);
    }

    /**
     * Get opcionesRespuesta
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOpcionesRespuesta()
    {
        return $this->opcionesRespuesta;
    }

    /**
     * Add subPreguntas
     *
     * @param \AcreditacionBundle\Entity\Pregunta $subPreguntas
     * @return Pregunta
     */
    public function addSubPreguntas(\AcreditacionBundle\Entity\Pregunta $subPreguntas)
    {
        $this->subPreguntas[] = $subPreguntas;

        return $this;
    }

    /**
     * Remove subPreguntas
     *
     * @param \AcreditacionBundle\Entity\Pregunta $subPreguntas
     */
    public function removeSubPreguntas(\AcreditacionBundle\Entity\Pregunta $subPreguntas)
    {
        $this->subPreguntas->removeElement($subPreguntas);
    }

    /**
     * Get subPreguntas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSubPreguntas()
    {
        return $this->subPreguntas;
    }
}
