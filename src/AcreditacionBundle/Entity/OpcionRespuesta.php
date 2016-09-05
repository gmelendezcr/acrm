<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OpcionRespuesta
 *
 * @ORM\Table(name="OPCION_RESPUESTA")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\OpcionRespuestaRepository")
 */
class OpcionRespuesta
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_OPCION_RESPUESTA", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idOpcionRespuesta;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Pregunta", inversedBy="opcionesRespuesta")
     * @ORM\JoinColumn(name="ID_PREGUNTA",referencedColumnName="ID_PREGUNTA")
     */
    private $idPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_OPCION_RESPUESTA", type="string", length=20)
     */
    private $codOpcionRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_OPCION_RESPUESTA", type="string", length=255, nullable=true)
     */
    private $descripcionOpcionRespuesta;

    /**
     * @var int
     *
     * @ORM\Column(name="ORDEN_OPCION_RESPUESTA", type="integer")
     */
    private $ordenOpcionRespuesta;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->idOpcionRespuesta;
    }

    /**
     * Set idPregunta
     *
     * @param  \AcreditacionBundle\Entity\Pregunta $idPregunta
     * @return OpcionRespuesta
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
     * Set codOpcionRespuesta
     *
     * @param string $codOpcionRespuesta
     * @return OpcionRespuesta
     */
    public function setCodOpcionRespuesta($codOpcionRespuesta)
    {
        $this->codOpcionRespuesta = $codOpcionRespuesta;

        return $this;
    }

    /**
     * Get codOpcionRespuesta
     *
     * @return string 
     */
    public function getCodOpcionRespuesta()
    {
        return $this->codOpcionRespuesta;
    }

    /**
     * Set descripcionOpcionRespuesta
     *
     * @param string $descripcionOpcionRespuesta
     * @return OpcionRespuesta
     */
    public function setDescripcionOpcionRespuesta($descripcionOpcionRespuesta)
    {
        $this->descripcionOpcionRespuesta = $descripcionOpcionRespuesta;

        return $this;
    }

    /**
     * Get descripcionOpcionRespuesta
     *
     * @return string 
     */
    public function getDescripcionOpcionRespuesta()
    {
        return $this->descripcionOpcionRespuesta;
    }

    /**
     * Set ordenOpcionRespuesta
     *
     * @param integer $ordenOpcionRespuesta
     * @return OpcionRespuesta
     */
    public function setOrdenOpcionRespuesta($ordenOpcionRespuesta)
    {
        $this->ordenOpcionRespuesta = $ordenOpcionRespuesta;

        return $this;
    }

    /**
     * Get ordenOpcionRespuesta
     *
     * @return integer 
     */
    public function getOrdenOpcionRespuesta()
    {
        return $this->ordenOpcionRespuesta;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return OpcionRespuesta
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
}
