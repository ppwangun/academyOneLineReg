<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * CalculationRulesWeight
 *
 * @ORM\Table(name="calculation_rules_weight", indexes={@ORM\Index(name="fk_claculation_rules_weight_exam_type1_idx", columns={"exam_type_id"}), @ORM\Index(name="fk_calculation_rules_weight_calculation_rule1_idx", columns={"calculation_rule_id"})})
 * @ORM\Entity
 */
class CalculationRulesWeight
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
     * @ORM\Column(name="ruleWeightValue", type="string", length=45, nullable=true)
     */
    private $ruleweightvalue;

    /**
     * @var \CalculationRule
     *
     * @ORM\ManyToOne(targetEntity="CalculationRule")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="calculation_rule_id", referencedColumnName="id")
     * })
     */
    private $calculationRule;

    /**
     * @var \ExamType
     *
     * @ORM\ManyToOne(targetEntity="ExamType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="exam_type_id", referencedColumnName="id")
     * })
     */
    private $examType;



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
     * Set ruleweightvalue.
     *
     * @param string|null $ruleweightvalue
     *
     * @return CalculationRulesWeight
     */
    public function setRuleweightvalue($ruleweightvalue = null)
    {
        $this->ruleweightvalue = $ruleweightvalue;

        return $this;
    }

    /**
     * Get ruleweightvalue.
     *
     * @return string|null
     */
    public function getRuleweightvalue()
    {
        return $this->ruleweightvalue;
    }

    /**
     * Set calculationRule.
     *
     * @param \CalculationRule|null $calculationRule
     *
     * @return CalculationRulesWeight
     */
    public function setCalculationRule(\CalculationRule $calculationRule = null)
    {
        $this->calculationRule = $calculationRule;

        return $this;
    }

    /**
     * Get calculationRule.
     *
     * @return \CalculationRule|null
     */
    public function getCalculationRule()
    {
        return $this->calculationRule;
    }

    /**
     * Set examType.
     *
     * @param \ExamType|null $examType
     *
     * @return CalculationRulesWeight
     */
    public function setExamType(\ExamType $examType = null)
    {
        $this->examType = $examType;

        return $this;
    }

    /**
     * Get examType.
     *
     * @return \ExamType|null
     */
    public function getExamType()
    {
        return $this->examType;
    }
}
