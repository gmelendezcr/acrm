<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NivelEducativo
 *
 * @ORM\Table(name="NIVEL_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\NivelEducativoRepository")
 */
class NivelEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_NIVEL_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idNivelEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_NIVEL_EDUCATIVO", type="string", length=20, unique=true)
     */
    private $codNivelEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_NIVEL_EDUCATIVO", type="string", length=100)
     */
    private $nbrNivelEducativo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="GradoEscolar",mappedBy="idNivelEducativo")
     */
    private $gradosEscolares;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gradosEscolares = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idNivelEducativo
     *
     * @return integer 
     */
    public function getIdNivelEducativo()
    {
        return $this->idNivelEducativo;
    }

    /**
     * Set codNivelEducativo
     *
     * @param string $codNivelEducativo
     * @return NivelEducativo
     */
    public function setCodNivelEducativo($codNivelEducativo)
    {
        $this->codNivelEducativo = $codNivelEducativo;

        return $this;
    }

    /**
     * Get codNivelEducativo
     *
     * @return string 
     */
    public function getCodNivelEducativo()
    {
        return $this->codNivelEducativo;
    }

    /**
     * Set nbrNivelEducativo
     *
     * @param string $nbrNivelEducativo
     * @return NivelEducativo
     */
    public function setNbrNivelEducativo($nbrNivelEducativo)
    {
        $this->nbrNivelEducativo = $nbrNivelEducativo;

        return $this;
    }

    /**
     * Get nbrNivelEducativo
     *
     * @return string 
     */
    public function getNbrNivelEducativo()
    {
        return $this->nbrNivelEducativo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return NivelEducativo
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
     * Add gradosEscolares
     *
     * @param \AcreditacionBundle\Entity\GradoEscolar $gradosEscolares
     * @return NivelEducativo
     */
    public function addGradosEscolares(\AcreditacionBundle\Entity\GradoEscolar $gradosEscolares)
    {
        $this->gradosEscolares[] = $gradosEscolares;

        return $this;
    }

    /**
     * Remove gradosEscolares
     *
     * @param \AcreditacionBundle\Entity\GradoEscolar $gradosEscolares
     */
    public function removeGradosEscolares(\AcreditacionBundle\Entity\GradoEscolar $gradosEscolares)
    {
        $this->gradosEscolares->removeElement($gradosEscolares);
    }

    /**
     * Get gradosEscolares
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGradosEscolares()
    {
        return $this->gradosEscolares;
    }
}
