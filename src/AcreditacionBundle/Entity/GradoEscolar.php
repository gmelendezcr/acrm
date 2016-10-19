<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GradoEscolar
 *
 * @ORM\Table(name="GRADO_ESCOLAR")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\GradoEscolarRepository")
 */
class GradoEscolar
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_GRADO_ESCOLAR", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idGradoEscolar;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_GRADO_ESCOLAR", type="string", length=20, unique=true)
     */
    private $codGradoEscolar;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_GRADO_ESCOLAR", type="string", length=100)
     */
    private $nbrGradoEscolar;

    /**
     * @var string
     *
     * @ORM\Column(name="OPCION_GRADO_ESCOLAR", type="string", length=100, nullable=true)
     */
    private $opcionGradoEscolar;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="NivelEducativo", inversedBy="gradosEscolares")
     * @ORM\JoinColumn(name="ID_NIVEL_EDUCATIVO",referencedColumnName="ID_NIVEL_EDUCATIVO")
     */
    private $idNivelEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="GradoEscolarPorCentroEducativo",mappedBy="idGradoEscolar")
     */
    private $gradosEscolaresPorCentroEducativo;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gradosEscolaresPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getIdGradoEscolar()
    {
        return $this->idGradoEscolar;
    }

    /**
     * Set codGradoEscolar
     *
     * @param string $codGradoEscolar
     * @return GradoEscolar
     */
    public function setCodGradoEscolar($codGradoEscolar)
    {
        $this->codGradoEscolar = $codGradoEscolar;

        return $this;
    }

    /**
     * Get codGradoEscolar
     *
     * @return string 
     */
    public function getCodGradoEscolar()
    {
        return $this->codGradoEscolar;
    }

    /**
     * Set nbrGradoEscolar
     *
     * @param string $nbrGradoEscolar
     * @return GradoEscolar
     */
    public function setNbrGradoEscolar($nbrGradoEscolar)
    {
        $this->nbrGradoEscolar = $nbrGradoEscolar;

        return $this;
    }

    /**
     * Get nbrGradoEscolar
     *
     * @return string 
     */
    public function getNbrGradoEscolar()
    {
        return $this->nbrGradoEscolar;
    }

    /**
     * Set opcionGradoEscolar
     *
     * @param string $opcionGradoEscolar
     * @return GradoEscolar
     */
    public function setOpcionGradoEscolar($opcionGradoEscolar)
    {
        $this->opcionGradoEscolar = $opcionGradoEscolar;

        return $this;
    }

    /**
     * Get opcionGradoEscolar
     *
     * @return string 
     */
    public function getOpcionGradoEscolar()
    {
        return $this->opcionGradoEscolar;
    }

    /**
     * Set idNivelEducativo
     *
     * @param  \AcreditacionBundle\Entity\NivelEducativo $idNivelEducativo
     * @return GradoEscolar
     */
    public function setIdNivelEducativo(\AcreditacionBundle\Entity\NivelEducativo $idNivelEducativo)
    {
        $this->idNivelEducativo = $idNivelEducativo;

        return $this;
    }

    /**
     * Get idNivelEducativo
     *
     * @return \AcreditacionBundle\Entity\NivelEducativo
     */
    public function getIdNivelEducativo()
    {
        return $this->idNivelEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return GradoEscolar
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
     * Add gradosEscolaresPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo
     * @return GradoEscolar
     */
    public function addGradosEscolaresPorCentroEducativo(\AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo)
    {
        $this->gradosEscolaresPorCentroEducativo[] = $gradosEscolaresPorCentroEducativo;

        return $this;
    }

    /**
     * Remove gradosEscolaresPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo
     */
    public function removeGradosEscolaresPorCentroEducativo(\AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $gradosEscolaresPorCentroEducativo)
    {
        $this->gradosEscolaresPorCentroEducativo->removeElement($gradosEscolaresPorCentroEducativo);
    }

    /**
     * Get gradosEscolaresPorCentroEducativo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGradosEscolaresPorCentroEducativo()
    {
        return $this->gradosEscolaresPorCentroEducativo;
    }
     public function __toString(){
        return $this->nbrGradoEscolar;
        //return $this->idNivelEducativo->getNbrNivelEducativo() . ' / ' . $this->nbrGradoEscolar;
    }
}
