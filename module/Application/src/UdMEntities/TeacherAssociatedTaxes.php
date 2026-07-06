<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TeacherAssociatedTaxes
 *
 * @ORM\Table(name="teacher_associated_taxes", indexes={@ORM\Index(name="fk_teacher_has_taxes_taxes1_idx", columns={"taxes_id"}), @ORM\Index(name="fk_teacher_has_taxes_teacher1_idx", columns={"teacher_id"})})
 * @ORM\Entity
 */
class TeacherAssociatedTaxes
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
     * @var \Teacher
     *
     * @ORM\ManyToOne(targetEntity="Teacher")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     * })
     */
    private $teacher;

    /**
     * @var \Taxes
     *
     * @ORM\ManyToOne(targetEntity="Taxes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="taxes_id", referencedColumnName="id")
     * })
     */
    private $taxes;



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
     * Set taxes.
     *
     * @param \Taxes|null $taxes
     *
     * @return TeacherAssociatedTaxes
     */
    public function setTaxes(\Taxes $taxes = null)
    {
        $this->taxes = $taxes;

        return $this;
    }

    /**
     * Get taxes.
     *
     * @return \Taxes|null
     */
    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * Set teacher.
     *
     * @param \Teacher|null $teacher
     *
     * @return TeacherAssociatedTaxes
     */
    public function setTeacher(\Teacher $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher.
     *
     * @return \Teacher|null
     */
    public function getTeacher()
    {
        return $this->teacher;
    }
}
