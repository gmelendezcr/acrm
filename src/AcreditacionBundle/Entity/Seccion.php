<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Seccion
 *
 * @ORM\Table(name="SECCION")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\SeccionRepository")
 */
class Seccion
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_SECCION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idSeccion;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Formulario", inversedBy="secciones")
     * @ORM\JoinColumn(name="ID_FORMULARIO",referencedColumnName="ID_FORMULARIO")
     */
    private $idFormulario;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_SECCION", type="string", length=20)
     */
    private $codSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_SECCION", type="string", length=100)
     */
    private $nbrSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_SECCION", type="string", length=1000, nullable=true)
     */
    private $descripcionSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta",mappedBy="idSeccion")
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
     * Get idSeccion
     *
     * @return integer 
     */
    public function getIdSeccion()
    {
        return $this->idSeccion;
    }

    /**
     * Set idFormulario
     *
     * @param  \AcreditacionBundle\Entity\Formulario $idFormulario
     * @return Seccion
     */
    public function setIdFormulario(\AcreditacionBundle\Entity\Formulario $idFormulario)
    {
        $this->idFormulario = $idFormulario;

        return $this;
    }

    /**
     * Get idFormulario
     *
     * @return \AcreditacionBundle\Entity\Formulario
     */
    public function getIdFormulario()
    {
        return $this->idFormulario;
    }

    /**
     * Set codSeccion
     *
     * @param string $codSeccion
     * @return Seccion
     */
    public function setCodSeccion($codSeccion)
    {
        $this->codSeccion = $codSeccion;

        return $this;
    }

    /**
     * Get codSeccion
     *
     * @return string 
     */
    public function getCodSeccion()
    {
        return $this->codSeccion;
    }

    /**
     * Set nbrSeccion
     *
     * @param string $nbrSeccion
     * @return Seccion
     */
    public function setNbrSeccion($nbrSeccion)
    {
        $this->nbrSeccion = $nbrSeccion;

        return $this;
    }

    /**
     * Get nbrSeccion
     *
     * @return string 
     */
    public function getNbrSeccion()
    {
        return $this->nbrSeccion;
    }

    /**
     * Set descripcionSeccion
     *
     * @param string $descripcionSeccion
     * @return Seccion
     */
    public function setDescripcionSeccion($descripcionSeccion)
    {
        $this->descripcionSeccion = $descripcionSeccion;

        return $this;
    }

    /**
     * Get descripcionSeccion
     *
     * @return string 
     */
    public function getDescripcionSeccion()
    {
        return $this->descripcionSeccion;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Seccion
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
     * @return Seccion
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
