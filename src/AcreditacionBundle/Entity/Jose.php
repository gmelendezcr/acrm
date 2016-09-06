<?php

namespace AcreditacionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jose
 *
 * @ORM\Table(name="jose")
 * @ORM\Entity(repositoryClass="AcreditacionBundle\Repository\JoseRepository")
 */
class Jose
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="x", type="string", length=30, nullable=true)
     */
    private $x;

    /**
     * @var string
     *
     * @ORM\Column(name="y", type="string", length=255)
     */
    private $y;

    /**
     * @var int
     *
     * @ORM\Column(name="z", type="integer", nullable=true, unique=true)
     */
    private $z;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set x
     *
     * @param string $x
     * @return Jose
     */
    public function setX($x)
    {
        $this->x = $x;

        return $this;
    }

    /**
     * Get x
     *
     * @return string 
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * Set y
     *
     * @param string $y
     * @return Jose
     */
    public function setY($y)
    {
        $this->y = $y;

        return $this;
    }

    /**
     * Get y
     *
     * @return string 
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * Set z
     *
     * @param integer $z
     * @return Jose
     */
    public function setZ($z)
    {
        $this->z = $z;

        return $this;
    }

    /**
     * Get z
     *
     * @return integer 
     */
    public function getZ()
    {
        return $this->z;
    }
}
