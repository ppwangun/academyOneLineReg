<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Deliberation
 *
 * @ORM\Table(name="deliberation")
 * @ORM\Entity
 */
class Deliberation
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
     * @ORM\Column(name="label", type="string", length=255, nullable=false)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="delibCondition", type="string", length=10000, nullable=false)
     */
    private $delibcondition;



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
     * Set label.
     *
     * @param string $label
     *
     * @return Deliberation
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set delibcondition.
     *
     * @param string $delibcondition
     *
     * @return Deliberation
     */
    public function setDelibcondition($delibcondition)
    {
        $this->delibcondition = $delibcondition;

        return $this;
    }

    /**
     * Get delibcondition.
     *
     * @return string
     */
    public function getDelibcondition()
    {
        return $this->delibcondition;
    }
}
