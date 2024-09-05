<?php
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User 
{
    // User status constants.
    const STATUS_ACTIVE       = 1; // Active user.
    const STATUS_RETIRED      = 2; // Retired user.
    
    const USER_CONNECTED = 1;
    const USER_DISCONNECTED = 0;
    
    /**
     * @ORM\Id
     * @ORM\Column(name="id")
     * @ORM\GeneratedValue
     */
    protected $id;

    /** 
     * @ORM\Column(name="email")  
     */
    protected $email;
    
    /** 
     * @ORM\Column(name="nom")  
     */
    protected $nom;
    
    /** 
     * @ORM\Column(name="prenom")  
     */
    protected $prenom;    

    /** 
     * @ORM\Column(name="password")  
     */
    protected $password;

    /** 
     * @ORM\Column(name="status")  
     */
    protected $status;
    
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    private $dateCreated;
        
    /**
     * @ORM\Column(name="pwd_reset_token")  
     */
    protected $passwordResetToken;
    
    /**
     * @ORM\Column(name="pwd_reset_token_creation_date")  
     */
    protected $passwordResetTokenCreationDate;
    
     /**
     * @var bool|null
     *
     * @ORM\Column(name="first_connection", type="boolean", nullable=true, options={"default"="1"})
     */
    private $firstConnection = true;
    
    /**
     * @var bool|null
     *
     * @ORM\Column(name="connected_status", type="boolean", nullable=true)
     */
    private $connectedStatus = '0';
    
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_connected_date", type="datetime", nullable=true)
     */
    private $lastConnectedDate;    
    
    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\Role")
     * @ORM\JoinTable(name="user_role",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     *      )
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="Application\Entity\ClassOfStudy")
     * @ORM\JoinTable(name="user_manages_class_of_study",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="class_of_study_id", referencedColumnName="id")}
     *      )
     */
    private $classes;
    
    /**
     * Constructor.
     */
    public function __construct() 
    {
        $this->roles = new ArrayCollection();
    }
    
    /**
     * Returns user ID.
     * @return integer
     */
    public function getId() 
    {
        return $this->id;
    }

    /**
     * Sets user ID. 
     * @param int $id    
     */
    public function setId($id) 
    {
        $this->id = $id;
    }

    /**
     * Returns email.     
     * @return string
     */
    public function getEmail() 
    {
        return $this->email;
    }

    /**
     * Sets email.     
     * @param string $email
     */
    public function setEmail($email) 
    {
        $this->email = $email;
    }
    
    /**
     * Returns nom.
     * @return string     
     */
    public function getNom() 
    {
        return $this->nom;
    }       

    /**
     * Sets nom.     
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    
    /**
     * Returns prenom.
     * @return string     
     */
    public function getPrenom() 
    {
        return $this->prenom;
    }     
    
    /**
     * Sets prenom
     * @param string $prenom
     */
    public function setPrenom($prenom) 
    {
        $this->prenom = $prenom;
    }
    
    /**
     * Returns status.
     * @return int     
     */
    public function getStatus() 
    {
        return $this->status;
    }

    /**
     * Returns possible statuses as array.
     * @return array
     */
    public static function getStatusList() 
    {
        return [
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_RETIRED => 'Retired'
        ];
    }    
    
    /**
     * Returns user status as string.
     * @return string
     */
    public function getStatusAsString()
    {
        $list = self::getStatusList();
        if (isset($list[$this->status]))
            return $list[$this->status];
        
        return 'Unknown';
    }    
    
    /**
     * Sets status.
     * @param int $status     
     */
    public function setStatus($status) 
    {
        $this->status = $status;
    }   
    
    /**
     * Returns password.
     * @return string
     */
    public function getPassword() 
    {
       return $this->password; 
    }
    
    /**
     * Sets password.     
     * @param string $password
     */
    public function setPassword($password) 
    {
        $this->password = $password;
    }
    
    /**
     * Set dateCreated.
     *
     * @param \DateTime|null $dateCreated
     *
     * @return User
     */
    public function setDateCreated($dateCreated = null)
    {
        $this->dateCreated = $dateCreated;
    
        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime|null
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }    
    
    /**
     * Returns password reset token.
     * @return string
     */
    public function getResetPasswordToken()
    {
        return $this->passwordResetToken;
    }
    
    /**
     * Sets password reset token.
     * @param string $token
     */
    public function setPasswordResetToken($token) 
    {
        $this->passwordResetToken = $token;
    }
    
    /**
     * Returns password reset token's creation date.
     * @return string
     */
    public function getPasswordResetTokenCreationDate()
    {
        return $this->passwordResetTokenCreationDate;
    }
    
    /**
     * Sets password reset token's creation date.
     * @param string $date
     */
    public function setPasswordResetTokenCreationDate($date) 
    {
        $this->passwordResetTokenCreationDate = $date;
    }
    
    /**
     * Set firstConnection.
     *
     * @param bool|null $firstConnection
     *
     * @return User
     */
    public function setFirstConnection($firstConnection = null)
    {
        $this->firstConnection = $firstConnection;
    
        return $this;
    }

    /**
     * Get firstConnection.
     *
     * @return bool|null
     */
    public function getFirstConnection()
    {
        return $this->firstConnection;
    } 
    
    /**
     * Set connectedStatus.
     *
     * @param bool|null $connectedStatus
     *
     * @return User
     */
    public function setConnectedStatus($connectedStatus = null)
    {
        $this->connectedStatus = $connectedStatus;
    
        return $this;
    }

    /**
     * Get connectedStatus.
     *
     * @return bool|null
     */
    public function getConnectedStatus()
    {
        return $this->connectedStatus;
    } 
    
    /**
     * Set lastConnectedDate.
     *
     * @param \DateTime|null $lastConnectedDate
     *
     * @return User
     */
    public function setLastConnectedDate($lastConnectedDate = null)
    {
        $this->lastConnectedDate = $lastConnectedDate;
    
        return $this;
    }

    /**
     * Get lastConnectedDate.
     *
     * @return \DateTime|null
     */
    public function getLastConnectedDate()
    {
        return $this->lastConnectedDate;
    }    
    
    /**
     * Returns the array of classes assigned to this user.
     * @return array
     */
    public function getClasses()
    {
        return $this->classes;
    }
    
    /**
     * Returns the string of assigned class names.
     */
    public function getClassesAsString()
    {
        $classeList = '';
        
        $count = count($this->classes);
        $i = 0;
        foreach ($this->classes as $classe) {
            $classeList .= $classe->getName();
            if ($i<$count-1)
                $classeList .= ', ';
            $i++;
        }
        
        return $classeList;
    }
    
    /**
     * Assigns a class to user.
     */
    public function addClasse($classe)
    {
        $this->classes->add($classe);
    }
    
    
    /**
     * Returns the array of roles assigned to this user.
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * Returns the string of assigned role names.
     */
    public function getRolesAsString()
    {
        $roleList = '';
        
        $count = count($this->roles);
        $i = 0;
        foreach ($this->roles as $role) {
            $roleList .= $role->getName();
            if ($i<$count-1)
                $roleList .= ', ';
            $i++;
        }
        
        return $roleList;
    }
    
    /**
     * Assigns a role to user.
     */
    public function addRole($role)
    {
        $this->roles->add($role);
    }
}