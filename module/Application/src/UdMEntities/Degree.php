<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Degree
 *
 * @ORM\Table(name="degree", uniqueConstraints={@ORM\UniqueConstraint(name="course_code_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_degree_speciality_option1_idx", columns={"speciality_option_id"}), @ORM\Index(name="fk_degree_speciality1_idx", columns={"speciality_id"}), @ORM\Index(name="fk_degree_field_of_study1_idx", columns={"field_study_id"})})
 * @ORM\Entity
 */
class Degree
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
     * @ORM\Column(name="code", type="string", length=45, nullable=true)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isCoreCurriculum", type="boolean", nullable=true)
     */
    private $iscorecurriculum;

    /**
     * @var \FieldOfStudy
     *
     * @ORM\ManyToOne(targetEntity="FieldOfStudy")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="field_study_id", referencedColumnName="id")
     * })
     */
    private $fieldStudy;

    /**
     * @var \Speciality
     *
     * @ORM\ManyToOne(targetEntity="Speciality")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="speciality_id", referencedColumnName="id")
     * })
     */
    private $speciality;

    /**
     * @var \SpecialityOption
     *
     * @ORM\ManyToOne(targetEntity="SpecialityOption")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="speciality_option_id", referencedColumnName="id")
     * })
     */
    private $specialityOption;



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
     * Set code.
     *
     * @param string|null $code
     *
     * @return Degree
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name.
     *
     * @param string|null $name
     *
     * @return Degree
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
     * Set status.
     *
     * @param bool|null $status
     *
     * @return Degree
     */
    public function setStatus($status = null)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set iscorecurriculum.
     *
     * @param bool|null $iscorecurriculum
     *
     * @return Degree
     */
    public function setIscorecurriculum($iscorecurriculum = null)
    {
        $this->iscorecurriculum = $iscorecurriculum;

        return $this;
    }

    /**
     * Get iscorecurriculum.
     *
     * @return bool|null
     */
    public function getIscorecurriculum()
    {
        return $this->iscorecurriculum;
    }

    /**
     * Set fieldStudy.
     *
     * @param \FieldOfStudy|null $fieldStudy
     *
     * @return Degree
     */
    public function setFieldStudy(\FieldOfStudy $fieldStudy = null)
    {
        $this->fieldStudy = $fieldStudy;

        return $this;
    }

    /**
     * Get fieldStudy.
     *
     * @return \FieldOfStudy|null
     */
    public function getFieldStudy()
    {
        return $this->fieldStudy;
    }

    /**
     * Set speciality.
     *
     * @param \Speciality|null $speciality
     *
     * @return Degree
     */
    public function setSpeciality(\Speciality $speciality = null)
    {
        $this->speciality = $speciality;

        return $this;
    }

    /**
     * Get speciality.
     *
     * @return \Speciality|null
     */
    public function getSpeciality()
    {
        return $this->speciality;
    }

    /**
     * Set specialityOption.
     *
     * @param \SpecialityOption|null $specialityOption
     *
     * @return Degree
     */
    public function setSpecialityOption(\SpecialityOption $specialityOption = null)
    {
        $this->specialityOption = $specialityOption;

        return $this;
    }

    /**
     * Get specialityOption.
     *
     * @return \SpecialityOption|null
     */
    public function getSpecialityOption()
    {
        return $this->specialityOption;
    }
}
