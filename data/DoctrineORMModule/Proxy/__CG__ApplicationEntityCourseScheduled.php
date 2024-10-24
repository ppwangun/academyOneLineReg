<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity;


/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class CourseScheduled extends \Application\Entity\CourseScheduled implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'dateScheduled', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'startingTime', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'endingTime', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'scheduleType', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'resource', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'semester', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'subject', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'teachingUnit', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'classOfStudy', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'teacher'];
        }

        return ['__isInitialized__', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'id', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'dateScheduled', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'startingTime', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'endingTime', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'scheduleType', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'resource', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'semester', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'subject', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'teachingUnit', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'classOfStudy', '' . "\0" . 'Application\\Entity\\CourseScheduled' . "\0" . 'teacher'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (CourseScheduled $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load(): void
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized(): bool
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized): void
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(?\Closure $initializer = null): void
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer(): ?\Closure
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(?\Closure $cloner = null): void
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner(): ?\Closure
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties(): array
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateScheduled($dateScheduled)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateScheduled', [$dateScheduled]);

        return parent::setDateScheduled($dateScheduled);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateScheduled()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateScheduled', []);

        return parent::getDateScheduled();
    }

    /**
     * {@inheritDoc}
     */
    public function setStartingTime($startingTime = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStartingTime', [$startingTime]);

        return parent::setStartingTime($startingTime);
    }

    /**
     * {@inheritDoc}
     */
    public function getStartingTime()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStartingTime', []);

        return parent::getStartingTime();
    }

    /**
     * {@inheritDoc}
     */
    public function setEndingTime($endingTime = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEndingTime', [$endingTime]);

        return parent::setEndingTime($endingTime);
    }

    /**
     * {@inheritDoc}
     */
    public function getEndingTime()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEndingTime', []);

        return parent::getEndingTime();
    }

    /**
     * {@inheritDoc}
     */
    public function setScheduleType($scheduleType = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setScheduleType', [$scheduleType]);

        return parent::setScheduleType($scheduleType);
    }

    /**
     * {@inheritDoc}
     */
    public function getScheduleType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getScheduleType', []);

        return parent::getScheduleType();
    }

    /**
     * {@inheritDoc}
     */
    public function setResource(\Application\Entity\Resource $resource = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setResource', [$resource]);

        return parent::setResource($resource);
    }

    /**
     * {@inheritDoc}
     */
    public function getResource()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResource', []);

        return parent::getResource();
    }

    /**
     * {@inheritDoc}
     */
    public function setSemester(\Application\Entity\Semester $semester = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSemester', [$semester]);

        return parent::setSemester($semester);
    }

    /**
     * {@inheritDoc}
     */
    public function getSemester()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSemester', []);

        return parent::getSemester();
    }

    /**
     * {@inheritDoc}
     */
    public function setSubject(\Application\Entity\Subject $subject = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSubject', [$subject]);

        return parent::setSubject($subject);
    }

    /**
     * {@inheritDoc}
     */
    public function getSubject()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubject', []);

        return parent::getSubject();
    }

    /**
     * {@inheritDoc}
     */
    public function setTeachingUnit(\Application\Entity\TeachingUnit $teachingUnit = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTeachingUnit', [$teachingUnit]);

        return parent::setTeachingUnit($teachingUnit);
    }

    /**
     * {@inheritDoc}
     */
    public function getTeachingUnit()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeachingUnit', []);

        return parent::getTeachingUnit();
    }

    /**
     * {@inheritDoc}
     */
    public function setClassOfStudy(\Application\Entity\ClassOfStudy $classOfStudy = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setClassOfStudy', [$classOfStudy]);

        return parent::setClassOfStudy($classOfStudy);
    }

    /**
     * {@inheritDoc}
     */
    public function getClassOfStudy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getClassOfStudy', []);

        return parent::getClassOfStudy();
    }

    /**
     * {@inheritDoc}
     */
    public function setTeacher(\Application\Entity\Teacher $teacher = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTeacher', [$teacher]);

        return parent::setTeacher($teacher);
    }

    /**
     * {@inheritDoc}
     */
    public function getTeacher()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTeacher', []);

        return parent::getTeacher();
    }

}
