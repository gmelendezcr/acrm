<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Departamento
 *
 * @ORM\Table(name="DEPARTAMENTO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\DepartamentoRepository")
 */
class Departamento
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_DEPARTAMENTO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idDepartamento;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_DEPARTAMENTO", type="string", length=20, unique=true)
     */
    private $codDepartamento;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_DEPARTAMENTO", type="string", length=100)
     */
    private $nbrDepartamento;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="ZonaGeografica", inversedBy="departamentos")
     * @ORM\JoinColumn(name="ID_ZONA_GEOGRAFICA",referencedColumnName="ID_ZONA_GEOGRAFICA")
     */
    private $idZonaGeografica;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Municipio",mappedBy="idDepartamento")
     */
    private $municipios;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->municipios = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idDepartamento
     *
     * @return integer 
     */
    public function getIdDepartamento()
    {
        return $this->idDepartamento;
    }

    /**
     * Set codDepartamento
     *
     * @param string $codDepartamento
     * @return Departamento
     */
    public function setCodDepartamento($codDepartamento)
    {
        $this->codDepartamento = $codDepartamento;

        return $this;
    }

    /**
     * Get codDepartamento
     *
     * @return string 
     */
    public function getCodDepartamento()
    {
        return $this->codDepartamento;
    }

    /**
     * Set nbrDepartamento
     *
     * @param string $nbrDepartamento
     * @return Departamento
     */
    public function setNbrDepartamento($nbrDepartamento)
    {
        $this->nbrDepartamento = $nbrDepartamento;

        return $this;
    }

    /**
     * Get nbrDepartamento
     *
     * @return string 
     */
    public function getNbrDepartamento()
    {
        return $this->nbrDepartamento;
    }

    /**
     * Set idZonaGeografica
     *
     * @param  \AcreditacionBundle\Entity\ZonaGeografica $idZonaGeografica
     * @return Departamento
     */
    public function setIdZonaGeografica(\AcreditacionBundle\Entity\ZonaGeografica $idZonaGeografica)
    {
        $this->idZonaGeografica = $idZonaGeografica;

        return $this;
    }

    /**
     * Get idZonaGeografica
     *
     * @return \AcreditacionBundle\Entity\ZonaGeografica
     */
    public function getIdZonaGeografica()
    {
        return $this->idZonaGeografica;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Departamento
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
     * Add municipios
     *
     * @param \AcreditacionBundle\Entity\Municipio $municipios
     * @return Departamento
     */
    public function addMunicipios(\AcreditacionBundle\Entity\Municipio $municipios)
    {
        $this->municipios[] = $municipios;

        return $this;
    }

    /**
     * Remove municipios
     *
     * @param \AcreditacionBundle\Entity\Municipio $municipios
     */
    public function removeMunicipios(\AcreditacionBundle\Entity\Municipio $municipios)
    {
        $this->municipios->removeElement($municipios);
    }

    /**
     * Get municipios
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMunicipios()
    {
        return $this->municipios;
    }

    public function __toString(){
        return $this->nbrDepartamento;
    }
}
