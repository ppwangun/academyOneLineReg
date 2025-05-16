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
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int|null
     *
     * @ORM\Column(name="parent_role_id", type="integer", nullable=true)
     */
    private $parentRoleId;

    /**
     * @var int|null
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
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set parentRoleId.
     *
     * @param int|null $parentRoleId
     *
     * @return RoleHierarchy
     */
    public function setParentRoleId($parentRoleId = null)
    {
        $this->parentRoleId = $parentRoleId;

        return $this;
    }

    /**
     * Get parentRoleId.
     *
     * @return int|null
     */
    public function getParentRoleId()
    {
        return $this->parentRoleId;
    }

    /**
     * Set childRoleId.
     *
     * @param int|null $childRoleId
     *
     * @return RoleHierarchy
     */
    public function setChildRoleId($childRoleId = null)
    {
        $this->childRoleId = $childRoleId;

        return $this;
    }

    /**
     * Get childRoleId.
     *
     * @return int|null
     */
    public function getChildRoleId()
    {
        return $this->childRoleId;
    }

    /**
     * Set role.
     *
     * @param \Role|null $role
     *
     * @return RoleHierarchy
     */
    public function setRole(\Role $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role.
     *
     * @return \Role|null
     */
    public function getRole()
    {
        return $this->role;
    }
}
