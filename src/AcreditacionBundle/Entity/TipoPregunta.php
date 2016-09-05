<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoPregunta
 *
 * @ORM\Table(name="TIPO_PREGUNTA")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\TipoPreguntaRepository")
 */
class TipoPregunta
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_TIPO_PREGUNTA", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idTipoPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_TIPO_PREGUNTA", type="string", length=2, unique=true, options={"fixed":true})
     */
    private $codTipoPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_TIPO_PREGUNTA", type="string", length=100)
     */
    private $nbrTipoPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_TIPO_PREGUNTA", type="string", length=255, nullable=true)
     */
    private $descripcionTipoPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta",mappedBy="idTipoPregunta")
     */
    private $preguntas;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idTipoPregunta
     *
     * @return integer 
     */
    public function getIdTipoPregunta()
    {
        return $this->idTipoPregunta;
    }

    /**
     * Set codTipoPregunta
     *
     * @param string $codTipoPregunta
     * @return TipoPregunta
     */
    public function setCodTipoPregunta($codTipoPregunta)
    {
        $this->codTipoPregunta = $codTipoPregunta;

        return $this;
    }

    /**
     * Get codTipoPregunta
     *
     * @return string 
     */
    public function getCodTipoPregunta()
    {
        return $this->codTipoPregunta;
    }

    /**
     * Set nbrTipoPregunta
     *
     * @param string $nbrTipoPregunta
     * @return TipoPregunta
     */
    public function setNbrTipoPregunta($nbrTipoPregunta)
    {
        $this->nbrTipoPregunta = $nbrTipoPregunta;

        return $this;
    }

    /**
     * Get nbrTipoPregunta
     *
     * @return string 
     */
    public function getNbrTipoPregunta()
    {
        return $this->nbrTipoPregunta;
    }

    /**
     * Set descripcionTipoPregunta
     *
     * @param string $descripcionTipoPregunta
     * @return TipoPregunta
     */
    public function setDescripcionTipoPregunta($descripcionTipoPregunta)
    {
        $this->descripcionTipoPregunta = $descripcionTipoPregunta;

        return $this;
    }

    /**
     * Get descripcionTipoPregunta
     *
     * @return string 
     */
    public function getDescripcionTipoPregunta()
    {
        return $this->descripcionTipoPregunta;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return TipoPregunta
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
     * @return TipoPregunta
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
     * Get preguntas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPreguntas()
    {
        return $this->preguntas;
    }
}
