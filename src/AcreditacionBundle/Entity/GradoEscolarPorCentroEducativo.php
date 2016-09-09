<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GradoEscolarPorCentroEducativo
 *
 * @ORM\Table(name="GRADO_ESCOLAR_POR_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\GradoEscolarPorCentroEducativoRepository")
 */
class GradoEscolarPorCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_GRADO_ESCOLAR_POR_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idGradoEscolarPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="CentroEducativo", inversedBy="gradosEscolaresPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_CENTRO_EDUCATIVO",referencedColumnName="ID_CENTRO_EDUCATIVO")
     */
    private $idCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="GradoEscolar", inversedBy="gradosEscolaresPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_GRADO_ESCOLAR",referencedColumnName="ID_GRADO_ESCOLAR")
     */
    private $idGradoEscolar;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="CuotaAnualPorGradoEscolarPorCentroEducativo",mappedBy="idGradoEscolarPorCentroEducativo")
     */
    private $cuotasAnualesPorGradoEscolarPorCentroEducativo;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cuotasAnualesPorGradoEscolarPorCentroEducativo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idGradoEscolarPorCentroEducativo
     *
     * @return integer 
     */
    public function getIdGradoEscolarPorCentroEducativo()
    {
        return $this->idGradoEscolarPorCentroEducativo;
    }

    /**
     * Set idCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\CentroEducativo $idCentroEducativo
     * @return GradoEscolarPorCentroEducativo
     */
    public function setIdCentroEducativo(\AcreditacionBundle\Entity\CentroEducativo $idCentroEducativo)
    {
        $this->idCentroEducativo = $idCentroEducativo;

        return $this;
    }

    /**
     * Get idCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\CentroEducativo
     */
    public function getIdCentroEducativo()
    {
        return $this->idCentroEducativo;
    }

    /**
     * Set idGradoEscolar
     *
     * @param  \AcreditacionBundle\Entity\GradoEscolar $idGradoEscolar
     * @return GradoEscolarPorCentroEducativo
     */
    public function setIdGradoEscolar(\AcreditacionBundle\Entity\GradoEscolar $idGradoEscolar)
    {
        $this->idGradoEscolar = $idGradoEscolar;

        return $this;
    }

    /**
     * Get idGradoEscolar
     *
     * @return \AcreditacionBundle\Entity\GradoEscolar
     */
    public function getIdGradoEscolar()
    {
        return $this->idGradoEscolar;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return GradoEscolarPorCentroEducativo
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
     * Add cuotasAnualesPorGradoEscolarPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\CuotaAnualPorGradoEscolarPorCentroEducativo $cuotasAnualesPorGradoEscolarPorCentroEducativo
     * @return GradoEscolarPorCentroEducativo
     */
    public function addCuotasAnualesPorGradoEscolarPorCentroEducativo(\AcreditacionBundle\Entity\CuotaAnualPorGradoEscolarPorCentroEducativo $cuotasAnualesPorGradoEscolarPorCentroEducativo)
    {
        $this->cuotasAnualesPorGradoEscolarPorCentroEducativo[] = $cuotasAnualesPorGradoEscolarPorCentroEducativo;

        return $this;
    }

    /**
     * Remove cuotasAnualesPorGradoEscolarPorCentroEducativo
     *
     * @param \AcreditacionBundle\Entity\CuotaAnualPorGradoEscolarPorCentroEducativo $cuotasAnualesPorGradoEscolarPorCentroEducativo
     */
    public function removeCuotasAnualesPorGradoEscolarPorCentroEducativo(\AcreditacionBundle\Entity\CuotaAnualPorGradoEscolarPorCentroEducativo $cuotasAnualesPorGradoEscolarPorCentroEducativo)
    {
        $this->cuotasAnualesPorGradoEscolarPorCentroEducativo->removeElement($cuotasAnualesPorGradoEscolarPorCentroEducativo);
    }

    /**
     * Get cuotasAnualesPorGradoEscolarPorCentroEducativo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCuotasAnualesPorGradoEscolarPorCentroEducativo()
    {
        return $this->cuotasAnualesPorGradoEscolarPorCentroEducativo;
    }
}
