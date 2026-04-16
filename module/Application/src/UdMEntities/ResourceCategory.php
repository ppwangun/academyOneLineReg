<?php


use Doctrine\ORM\Mapping as ORM;

/**
 * ResourceCategory
 *
 * @ORM\Table(name="resource", indexes={@ORM\Index(name="fk_resource_resource1_idx", columns={"resource_id"})})
 * @ORM\Entity
 */
class ResourceCategory
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="ResourceCategory", inversedBy="children")
     * @ORM\JoinColumn(name="resource_id", referencedColumnName="id", nullable=true)
     */
    protected $parent;

    /**
     * @ORM(targetEntity="Category", mappedBy="parent")
     */
    protected $children;
    

    

}
