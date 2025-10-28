<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * FacultyHasResource
 *
 * @ORM\Table(name="faculty_has_resource", indexes={@ORM\Index(name="fk_faculty_has_resource_faculty1_idx", columns={"faculty_id"}), @ORM\Index(name="fk_faculty_has_resource_resource1_idx", columns={"resource_id"})})
 * @ORM\Entity
 */
class FacultyHasResource
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
     * @var \Faculty
     *
     * @ORM\ManyToOne(targetEntity="Faculty")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="faculty_id", referencedColumnName="id")
     * })
     */
    private $faculty;

    /**
     * @var \Resource
     *
     * @ORM\ManyToOne(targetEntity="Resource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="resource_id", referencedColumnName="id")
     * })
     */
    private $resource;



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
     * Set faculty.
     *
     * @param \Faculty|null $faculty
     *
     * @return FacultyHasResource
     */
    public function setFaculty(\Faculty $faculty = null)
    {
        $this->faculty = $faculty;

        return $this;
    }

    /**
     * Get faculty.
     *
     * @return \Faculty|null
     */
    public function getFaculty()
    {
        return $this->faculty;
    }

    /**
     * Set resource.
     *
     * @param \Resource|null $resource
     *
     * @return FacultyHasResource
     */
    public function setResource(\Resource $resource = null)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Get resource.
     *
     * @return \Resource|null
     */
    public function getResource()
    {
        return $this->resource;
    }
}
