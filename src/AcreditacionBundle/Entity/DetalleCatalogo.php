<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DetalleCatalogo
 *
 * @ORM\Table(name="DETALLE_CATALOGO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\DetalleCatalogoRepository")
 */
class DetalleCatalogo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_DETALLE_CATALOGO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idDetalleCatalogo;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Catalogo", inversedBy="detalleCatalogo")
     * @ORM\JoinColumn(name="ID_CATALOGO",referencedColumnName="ID_CATALOGO")
     */
    private $idCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_DETALLE_CATALOGO", type="string", length=20)
     */
    private $codDetalleCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_DETALLE_CATALOGO", type="string", length=100)
     */
    private $nbrDetalleCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_DETALLE_CATALOGO", type="string", length=255, nullable=true)
     */
    private $descripcionDetalleCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;


    /**
     * Get idDetalleCatalogo
     *
     * @return integer 
     */
    public function getIdDetalleCatalogo()
    {
        return $this->idDetalleCatalogo;
    }

    /**
     * Set idCatalogo
     *
     * @param  \AcreditacionBundle\Entity\Catalogo $idCatalogo
     * @return DetalleCatalogo
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
     * Set codDetalleCatalogo
     *
     * @param string $codDetalleCatalogo
     * @return DetalleCatalogo
     */
    public function setCodDetalleCatalogo($codDetalleCatalogo)
    {
        $this->codDetalleCatalogo = $codDetalleCatalogo;

        return $this;
    }

    /**
     * Get codDetalleCatalogo
     *
     * @return string 
     */
    public function getCodDetalleCatalogo()
    {
        return $this->codDetalleCatalogo;
    }

    /**
     * Set nbrDetalleCatalogo
     *
     * @param string $nbrDetalleCatalogo
     * @return DetalleCatalogo
     */
    public function setNbrDetalleCatalogo($nbrDetalleCatalogo)
    {
        $this->nbrDetalleCatalogo = $nbrDetalleCatalogo;

        return $this;
    }

    /**
     * Get nbrDetalleCatalogo
     *
     * @return string 
     */
    public function getNbrDetalleCatalogo()
    {
        return $this->nbrDetalleCatalogo;
    }

    /**
     * Set descripcionDetalleCatalogo
     *
     * @param string $descripcionDetalleCatalogo
     * @return DetalleCatalogo
     */
    public function setDescripcionDetalleCatalogo($descripcionDetalleCatalogo)
    {
        $this->descripcionDetalleCatalogo = $descripcionDetalleCatalogo;

        return $this;
    }

    /**
     * Get descripcionDetalleCatalogo
     *
     * @return string 
     */
    public function getDescripcionDetalleCatalogo()
    {
        return $this->descripcionDetalleCatalogo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return DetalleCatalogo
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
