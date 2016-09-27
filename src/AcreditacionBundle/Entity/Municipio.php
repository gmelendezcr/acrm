<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Municipio
 *
 * @ORM\Table(name="MUNICIPIO")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\MunicipioRepository")
 */
class Municipio
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_MUNICIPIO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_MUNICIPIO", type="string", length=20, unique=true)
     */
    private $codMunicipio;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_MUNICIPIO", type="string", length=100)
     */
    private $nbrMunicipio;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Departamento", inversedBy="municipios")
     * @ORM\JoinColumn(name="ID_DEPARTAMENTO",referencedColumnName="ID_DEPARTAMENTO")
     */
    private $idDepartamento;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="CentroEducativo",mappedBy="idMunicipio")
     */
    private $centrosEducativos;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->centrosEducativos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idMunicipio
     *
     * @return integer 
     */
    public function getIdMunicipio()
    {
        return $this->idMunicipio;
    }

    /**
     * Set codMunicipio
     *
     * @param string $codMunicipio
     * @return Municipio
     */
    public function setCodMunicipio($codMunicipio)
    {
        $this->codMunicipio = $codMunicipio;

        return $this;
    }

    /**
     * Get codMunicipio
     *
     * @return string 
     */
    public function getCodMunicipio()
    {
        return $this->codMunicipio;
    }

    /**
     * Set nbrMunicipio
     *
     * @param string $nbrMunicipio
     * @return Municipio
     */
    public function setNbrMunicipio($nbrMunicipio)
    {
        $this->nbrMunicipio = $nbrMunicipio;

        return $this;
    }

    /**
     * Get nbrMunicipio
     *
     * @return string 
     */
    public function getNbrMunicipio()
    {
        return $this->nbrMunicipio;
    }

    /**
     * Set idDepartamento
     *
     * @param  \AcreditacionBundle\Entity\Departamento $idDepartamento
     * @return Municipio
     */
    public function setIdDepartamento(\AcreditacionBundle\Entity\Departamento $idDepartamento)
    {
        $this->idDepartamento = $idDepartamento;

        return $this;
    }

    /**
     * Get idDepartamento
     *
     * @return \AcreditacionBundle\Entity\Departamento
     */
    public function getIdDepartamento()
    {
        return $this->idDepartamento;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return Municipio
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
     * Add centrosEducativos
     *
     * @param \AcreditacionBundle\Entity\CentroEducativo $centrosEducativos
     * @return Municipio
     */
    public function addCentrosEducativos(\AcreditacionBundle\Entity\CentroEducativo $centrosEducativos)
    {
        $this->centrosEducativos[] = $centrosEducativos;

        return $this;
    }

    /**
     * Remove centrosEducativos
     *
     * @param \AcreditacionBundle\Entity\CentroEducativo $centrosEducativos
     */
    public function removeCentrosEducativos(\AcreditacionBundle\Entity\CentroEducativo $centrosEducativos)
    {
        $this->centrosEducativos->removeElement($centrosEducativos);
    }

    /**
     * Get centrosEducativos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCentrosEducativos()
    {
        return $this->centrosEducativos;
    }
    
    public function __toString(){
        return $this->idDepartamento->getNbrDepartamento() . ' / ' . $this->nbrMunicipio;
    }
}
