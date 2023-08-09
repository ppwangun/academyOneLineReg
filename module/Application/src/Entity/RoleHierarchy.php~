<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * RoleHierarchy
 *
 * @ORM\Table(name="role_hierarchy", indexes={@ORM\Index(name="fk_role_hierarchy_role1_idx", columns={"role_id"})})
 * @ORM\Entity
 */
class RoleHierarchy
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="parent_role_id", type="integer", nullable=true)
     */
    private $parentRoleId;

    /**
     * @var integer
     *
     * @ORM\Column(name="child_role_id", type="integer", nullable=true)
     */
    private $childRoleId;

    /**
     * @var \Role
     *
     * @ORM\ManyToOne(targetEntity="Role")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     * })
     */
    private $role;



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
     * Set parentRoleId
     *
     * @param integer $parentRoleId
     *
     * @return RoleHierarchy
     */
    public function setParentRoleId($parentRoleId)
    {
        $this->parentRoleId = $parentRoleId;

        return $this;
    }

    /**
     * Get parentRoleId
     *
     * @return integer
     */
    public function getParentRoleId()
    {
        return $this->parentRoleId;
    }

    /**
     * Set childRoleId
     *
     * @param integer $childRoleId
     *
     * @return RoleHierarchy
     */
    public function setChildRoleId($childRoleId)
    {
        $this->childRoleId = $childRoleId;

        return $this;
    }

    /**
     * Get childRoleId
     *
     * @return integer
     */
    public function getChildRoleId()
    {
        return $this->childRoleId;
    }

    /**
     * Set role
     *
     * @param \Role $role
     *
     * @return RoleHierarchy
     */
    public function setRole(\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \Role
     */
    public function getRole()
    {
        return $this->role;
    }
}
