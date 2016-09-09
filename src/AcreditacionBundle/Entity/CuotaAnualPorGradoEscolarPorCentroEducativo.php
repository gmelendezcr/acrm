<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CuotaAnualPorGradoEscolarPorCentroEducativo
 *
 * @ORM\Table(name="CUOTA_ANUAL_POR_GRADO_ESCOLAR_POR_CENTRO_EDUCATIVO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\CuotaAnualPorGradoEscolarPorCentroEducativoRepository")
 */
class CuotaAnualPorGradoEscolarPorCentroEducativo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_CUOTA_ANUAL_POR_GRADO_ESCOLAR_POR_CENTRO_EDUCATIVO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idCuotaAnualPorGradoEscolarPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="GradoEscolarPorCentroEducativo", inversedBy="cuotasAnualesPorGradoEscolarPorCentroEducativo")
     * @ORM\JoinColumn(name="ID_GRADO_ESCOLAR_POR_CENTRO_EDUCATIVO",referencedColumnName="ID_GRADO_ESCOLAR_POR_CENTRO_EDUCATIVO")
     */
    private $idGradoEscolarPorCentroEducativo;

    /**
     * @var int
     *
     * @ORM\Column(name="ANNO", type="integer")
     */
    private $anno;

    /**
     * @var string
     *
     * @ORM\Column(name="MATRICULA", type="decimal", precision=10, scale=2)
     */
    private $matricula;

    /**
     * @var string
     *
     * @ORM\Column(name="MONTO", type="decimal", precision=10, scale=2)
     */
    private $monto;


    /**
     * Get idCuotaAnualPorGradoEscolarPorCentroEducativo
     *
     * @return integer 
     */
    public function getIdCuotaAnualPorGradoEscolarPorCentroEducativo()
    {
        return $this->idCuotaAnualPorGradoEscolarPorCentroEducativo;
    }

    /**
     * Set idGradoEscolarPorCentroEducativo
     *
     * @param  \AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $idGradoEscolarPorCentroEducativo
     * @return CuotaAnualPorGradoEscolarPorCentroEducativo
     */
    public function setIdGradoEscolarPorCentroEducativo(\AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo $idGradoEscolarPorCentroEducativo)
    {
        $this->idGradoEscolarPorCentroEducativo = $idGradoEscolarPorCentroEducativo;

        return $this;
    }

    /**
     * Get idGradoEscolarPorCentroEducativo
     *
     * @return \AcreditacionBundle\Entity\GradoEscolarPorCentroEducativo
     */
    public function getIdGradoEscolarPorCentroEducativo()
    {
        return $this->idGradoEscolarPorCentroEducativo;
    }

    /**
     * Set anno
     *
     * @param integer $anno
     * @return CuotaAnualPorGradoEscolarPorCentroEducativo
     */
    public function setAnno($anno)
    {
        $this->anno = $anno;

        return $this;
    }

    /**
     * Get anno
     *
     * @return integer 
     */
    public function getAnno()
    {
        return $this->anno;
    }

    /**
     * Set matricula
     *
     * @param string $matricula
     * @return CuotaAnualPorGradoEscolarPorCentroEducativo
     */
    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;

        return $this;
    }

    /**
     * Get matricula
     *
     * @return string 
     */
    public function getMatricula()
    {
        return $this->matricula;
    }

    /**
     * Set monto
     *
     * @param string $monto
     * @return CuotaAnualPorGradoEscolarPorCentroEducativo
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string 
     */
    public function getMonto()
    {
        return $this->monto;
    }
}
