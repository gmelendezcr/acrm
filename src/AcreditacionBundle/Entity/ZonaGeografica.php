<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ZonaGeografica
 *
 * @ORM\Table(name="ZONA_GEOGRAFICA")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\ZonaGeograficaRepository")
 */
class ZonaGeografica
{
    /**
     * @var int
     *
     * @ORM\Column(name="ID_ZONA_GEOGRAFICA", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $idZonaGeografica;

    /**
     * @var string
     *
     * @ORM\Column(name="COD_ZONA_GEOGRAFICA", type="string", length=20, unique=true)
     */
    private $codZonaGeografica;

    /**
     * @var string
     *
     * @ORM\Column(name="NBR_ZONA_GEOGRAFICA", type="string", length=100)
     */
    private $nbrZonaGeografica;

    /**
     * @var string
     *
     * @ORM\Column(name="ACTIVO", type="string", length=1, columnDefinition="CHAR(1) NOT NULL DEFAULT 'S' CHECK (ACTIVO IN ('S','N'))")
     */
    private $activo;

    /**
     * @ORM\OneToMany(targetEntity="Departamento",mappedBy="idZonaGeografica")
     */
    private $departamentos;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->departamentos = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get idZonaGeografica
     *
     * @return integer 
     */
    public function getIdZonaGeografica()
    {
        return $this->idZonaGeografica;
    }

    /**
     * Set codZonaGeografica
     *
     * @param string $codZonaGeografica
     * @return ZonaGeografica
     */
    public function setCodZonaGeografica($codZonaGeografica)
    {
        $this->codZonaGeografica = $codZonaGeografica;

        return $this;
    }

    /**
     * Get codZonaGeografica
     *
     * @return string 
     */
    public function getCodZonaGeografica()
    {
        return $this->codZonaGeografica;
    }

    /**
     * Set nbrZonaGeografica
     *
     * @param string $nbrZonaGeografica
     * @return ZonaGeografica
     */
    public function setNbrZonaGeografica($nbrZonaGeografica)
    {
        $this->nbrZonaGeografica = $nbrZonaGeografica;

        return $this;
    }

    /**
     * Get nbrZonaGeografica
     *
     * @return string 
     */
    public function getNbrZonaGeografica()
    {
        return $this->nbrZonaGeografica;
    }

    /**
     * Set activo
     *
     * @param string $activo
     * @return ZonaGeografica
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
     * Add departamentos
     *
     * @param \AcreditacionBundle\Entity\Departamento $departamentos
     * @return ZonaGeografica
     */
    public function addDepartamentos(\AcreditacionBundle\Entity\Departamento $departamentos)
    {
        $this->departamentos[] = $departamentos;

        return $this;
    }

    /**
     * Remove departamentos
     *
     * @param \AcreditacionBundle\Entity\Departamento $departamentos
     */
    public function removeDepartamentos(\AcreditacionBundle\Entity\Departamento $departamentos)
    {
        $this->departamentos->removeElement($departamentos);
    }

    /**
     * Get departamentos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDepartamentos()
    {
        return $this->departamentos;
    }
}
