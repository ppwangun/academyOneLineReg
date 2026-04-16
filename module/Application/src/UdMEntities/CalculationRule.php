<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CalculationRule
 *
 * @ORM\Table(name="calculation_rule", indexes={@ORM\Index(name="fk_claculation_rule_class_of_study_has_semester1_idx", columns={"class_of_study_has_semester_id"})})
 * @ORM\Entity
 */
class CalculationRule
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
     * @ORM\Column(name="combination", type="string", length=45, nullable=true)
     */
    private $combination;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="is_default", type="boolean", nullable=true)
     */
    private $isDefault = '0';

    /**
     * @var \ClassOfStudyHasSemester
     *
     * @ORM\ManyToOne(targetEntity="ClassOfStudyHasSemester")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="class_of_study_has_semester_id", referencedColumnName="id")
     * })
     */
    private $classOfStudyHasSemester;



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
     * Set combination.
     *
     * @param string|null $combination
     *
     * @return CalculationRule
     */
    public function setCombination($combination = null)
    {
        $this->combination = $combination;

        return $this;
    }

    /**
     * Get combination.
     *
     * @return string|null
     */
    public function getCombination()
    {
        return $this->combination;
    }

    /**
     * Set isDefault.
     *
     * @param bool|null $isDefault
     *
     * @return CalculationRule
     */
    public function setIsDefault($isDefault = null)
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * Get isDefault.
     *
     * @return bool|null
     */
    public function getIsDefault()
    {
        return $this->isDefault;
    }

    /**
     * Set classOfStudyHasSemester.
     *
     * @param \ClassOfStudyHasSemester|null $classOfStudyHasSemester
     *
     * @return CalculationRule
     */
    public function setClassOfStudyHasSemester(\ClassOfStudyHasSemester $classOfStudyHasSemester = null)
    {
        $this->classOfStudyHasSemester = $classOfStudyHasSemester;

        return $this;
    }

    /**
     * Get classOfStudyHasSemester.
     *
     * @return \ClassOfStudyHasSemester|null
     */
    public function getClassOfStudyHasSemester()
    {
        return $this->classOfStudyHasSemester;
    }
}
