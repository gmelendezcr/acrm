<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Catalogo
 *
 * @ORM\Table(name="CATALOGO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\CatalogoRepository")
 */
class Catalogo
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_CATALOGO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_CATALOGO", type="string", length=100)
     */
    private $nbrCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="DESCRIPCION_CATALOGO", type="string", length=255, nullable=true)
     */
    private $descripcionCatalogo;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="DetalleCatalogo",mappedBy="idCatalogo")
     */
    private $detallesCatalogo;

    /**
     * @ORM\OneToMany(targetEntity="Pregunta",mappedBy="idCatalogo")
     */
    private $preguntas;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->detallesCatalogo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->preguntas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->idCatalogo;
    }

    /**
     * Set nbrCatalogo
     *
     * @param string $nbrCatalogo
     * @return Catalogo
     */
    public function setNbrCatalogo($nbrCatalogo)
    {
        $this->nbrCatalogo = $nbrCatalogo;

        return $this;
    }

    /**
     * Get nbrCatalogo
     *
     * @return string 
     */
    public function getNbrCatalogo()
    {
        return $this->nbrCatalogo;
    }

    /**
     * Set descripcionCatalogo
     *
     * @param string $descripcionCatalogo
     * @return Catalogo
     */
    public function setDescripcionCatalogo($descripcionCatalogo)
    {
        $this->descripcionCatalogo = $descripcionCatalogo;

        return $this;
    }

    /**
     * Get descripcionCatalogo
     *
     * @return string 
     */
    public function getDescripcionCatalogo()
    {
        return $this->descripcionCatalogo;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Catalogo
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
     * Add detallesCatalogo
     *
     * @param \AcreditacionBundle\Entity\DetalleCatalogo $detallesCatalogo
     * @return Catalogo
     */
    public function addDetallesCatalogo(\AcreditacionBundle\Entity\DetalleCatalogo $detallesCatalogo)
    {
        $this->detallesCatalogo[] = $detallesCatalogo;

        return $this;
    }

    /**
     * Remove detallesCatalogo
     *
     * @param \AcreditacionBundle\Entity\DetalleCatalogo $detallesCatalogo
     */
    public function removeDetallesCatalogo(\AcreditacionBundle\Entity\DetalleCatalogo $detallesCatalogo)
    {
        $this->detallesCatalogo->removeElement($detallesCatalogo);
    }

    /**
     * Get detallesCatalogo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDetallesCatalogo()
    {
        return $this->detallesCatalogo;
    }

    /**
     * Add preguntas
     *
     * @param \AcreditacionBundle\Entity\Pregunta $preguntas
     * @return Catalogo
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
