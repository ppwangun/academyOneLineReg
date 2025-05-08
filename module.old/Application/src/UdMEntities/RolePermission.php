<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * RolePermission
 *
 * @ORM\Table(name="role_permission", indexes={@ORM\Index(name="fk_role_permission_permission1_idx", columns={"permission_id"}), @ORM\Index(name="fk_role_permission_role1_idx", columns={"role_id"})})
 * @ORM\Entity
 */
class RolePermission
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
     * @var string|null
     *
     * @ORM\Column(name="role_permission", type="string", length=45, nullable=true)
     */
    private $rolePermission;

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
     * @var \Permission
     *
     * @ORM\ManyToOne(targetEntity="Permission")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="permission_id", referencedColumnName="id")
     * })
     */
    private $permission;



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
     * Set rolePermission.
     *
     * @param string|null $rolePermission
     *
     * @return RolePermission
     */
    public function setRolePermission($rolePermission = null)
    {
        $this->rolePermission = $rolePermission;

        return $this;
    }

    /**
     * Get rolePermission.
     *
     * @return string|null
     */
    public function getRolePermission()
    {
        return $this->rolePermission;
    }

    /**
     * Set role.
     *
     * @param \Role|null $role
     *
     * @return RolePermission
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

    /**
     * Set permission.
     *
     * @param \Permission|null $permission
     *
     * @return RolePermission
     */
    public function setPermission(\Permission $permission = null)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Get permission.
     *
     * @return \Permission|null
     */
    public function getPermission()
    {
        return $this->permission;
    }
}
