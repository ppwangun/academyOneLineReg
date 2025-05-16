<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * TeachingUnit
 *
 * @ORM\Table(name="teaching_unit")
 * @ORM\Entity
 */
class TeachingUnit
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
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=45, nullable=false)
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var int|null
     *
     * @ORM\Column(name="number_of_subjects", type="integer", nullable=true)
     */
    private $numberOfSubjects = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isCompulsory", type="boolean", nullable=true)
     */
    private $iscompulsory = '0';

    /**
     * @var bool|null
     *
     * @ORM\Column(name="isCommonCore", type="boolean", nullable=true)
     */
    private $iscommoncore = '0';



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
     * @param string $code
     *
     * @return TeachingUnit
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
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
     * @return TeachingUnit
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
     * Set numberOfSubjects.
     *
     * @param int|null $numberOfSubjects
     *
     * @return TeachingUnit
     */
    public function setNumberOfSubjects($numberOfSubjects = null)
    {
        $this->numberOfSubjects = $numberOfSubjects;

        return $this;
    }

    /**
     * Get numberOfSubjects.
     *
     * @return int|null
     */
    public function getNumberOfSubjects()
    {
        return $this->numberOfSubjects;
    }

    /**
     * Set iscompulsory.
     *
     * @param bool|null $iscompulsory
     *
     * @return TeachingUnit
     */
    public function setIscompulsory($iscompulsory = null)
    {
        $this->iscompulsory = $iscompulsory;

        return $this;
    }

    /**
     * Get iscompulsory.
     *
     * @return bool|null
     */
    public function getIscompulsory()
    {
        return $this->iscompulsory;
    }

    /**
     * Set iscommoncore.
     *
     * @param bool|null $iscommoncore
     *
     * @return TeachingUnit
     */
    public function setIscommoncore($iscommoncore = null)
    {
        $this->iscommoncore = $iscommoncore;

        return $this;
    }

    /**
     * Get iscommoncore.
     *
     * @return bool|null
     */
    public function getIscommoncore()
    {
        return $this->iscommoncore;
    }
}
