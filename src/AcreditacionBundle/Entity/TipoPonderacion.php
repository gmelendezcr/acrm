<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TipoPonderacion
 *
 * @ORM\Table(name="TIPO_PONDERACION")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\TipoPonderacionRepository")
 */
class TipoPonderacion
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_TIPO_PONDERACION", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idTipoPonderacion;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_TIPO_PONDERACION", type="string", length=2, unique=true, options={"fixed":true})
     */
    private $codTipoPonderacion;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_TIPO_PONDERACION", type="string", length=100)
     */
    private $nbrTipoPonderacion;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta",mappedBy="idTipoPonderacion")
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
     * Get idTipoPonderacion
     *
     * @return integer 
     */
    public function getIdTipoPonderacion()
    {
        return $this->idTipoPonderacion;
    }

    /**
     * Set codTipoPonderacion
     *
     * @param string $codTipoPonderacion
     * @return TipoPonderacion
     */
    public function setCodTipoPonderacion($codTipoPonderacion)
    {
        $this->codTipoPonderacion = $codTipoPonderacion;

        return $this;
    }

    /**
     * Get codTipoPonderacion
     *
     * @return string 
     */
    public function getCodTipoPonderacion()
    {
        return $this->codTipoPonderacion;
    }

    /**
     * Set nbrTipoPonderacion
     *
     * @param string $nbrTipoPonderacion
     * @return TipoPonderacion
     */
    public function setNbrTipoPonderacion($nbrTipoPonderacion)
    {
        $this->nbrTipoPonderacion = $nbrTipoPonderacion;

        return $this;
    }

    /**
     * Get nbrTipoPonderacion
     *
     * @return string 
     */
    public function getNbrTipoPonderacion()
    {
        return $this->nbrTipoPonderacion;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return TipoPonderacion
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
     * @return TipoPonderacion
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
