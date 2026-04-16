<?php
namespace Application\Entity;

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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $name;

     /**
     * @var ResourceCategory
     *
     * @ORM\ManyToOne(targetEntity="ResourceCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * })
     */
    protected $parent;

    /**
     * @var ResourceCategory
     * 
     * @ORM\OneToMany(targetEntity="ResourceCategory", mappedBy="parent")
     */
    protected $children;
    

  
   
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

   /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Resource
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Set resourceCategory.
     *
     * @param ResourceCategory|null $parent
     *
     * @return ResourceCategory
     */
    public function setParent(ResourceCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get resourceCategory.
     *
     * @return ResourceCategory|null
     */
    public function getParent()
    {
        return $this->parent;
    }    
    
    /**
     * Set children.
     *
     * @param string|null $name
     *
     * @return ResourceCategory
     */
    public function setChildren($children = null)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children.
     *
     * @return ResourceCategory
     */
    public function getChildren()
    {
        return $this->children;
    }     

}
