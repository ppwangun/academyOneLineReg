<?php

namespace User\Form;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilter;
use Laminas\Form\Form;


/**
 * This form is used to collect user feedback data like user E-mail, 
 * message subject and text.
 */
class LoginForm extends Form
{
  // Constructor.   
  public function __construct()
  {
    // Define form name
    parent::__construct('login-form');

    // Set POST method for this form
    $this->setAttribute('method','post');
    $this->setAttribute('id', 'loginform');
        	
    // Add form elements
    $this->addElements();  
    $this->addInputFilter();
  }
    
  // This method adds elements to form (input fields and 
  // submit button).
  private function addElements() 
  {
    // Add "email" field
    $this->add([
	    'type'  => 'text',
            'name' => 'email',
            'options' => [
                'label' => 'Login',
            ],
        ]);
    $this->add([
	    'type'  => 'password',
            'name' => 'password',
            'attributes' => [                
                'class'=>'form-control', 
                'placeholder'=>'Password',
                'required' => true,
            ],
            'options' => [
                'label' => 'Mot de passe',
            ],
        ]);
        $this->add([            
            'type'  => 'checkbox',
            'name' => 'remember_me',
            'options' => [
                'label' => 'Vous souvenir de moi',
            ],
        ]);
            
        $this->add([            
            'type'  => 'hidden',
            'name' => 'redirect_url'
        ]);
        
        // Add the CSRF field
        $this->add([
            'type' => 'csrf',
            'name' => 'csrf',
            'options' => [
                'csrf_options' => [
                'timeout' => 600
                ]
            ],
        ]);    
    
// Add the submit button
    $this->add([
            'type'  => 'submit',
            'name' => 'login-btn',
            'attributes' => [ 
                'id'=>'login-btn',
                'value' => 'Connectez vous',
                'class'=>'btn btn-primary btn-lg btn-block'
            ],
        ]);
    
    }
    
 /**
     * This method creates input filter (used for form filtering/validation).
     */
     private function addInputFilter() 
    {
        // Create main input filter
        $inputFilter = new InputFilter();        
        $this->setInputFilter($inputFilter);
                
        // Add input for "email" field
        $inputFilter->add([
                'name'     => 'email',
                'required' => true,
                'filters'  => [
                    ['name' => 'StringTrim'],                    
                ],                
                'validators' => [
                    [
                        'name' => 'EmailAddress',
                        'options' => [
                            'allow' => \Laminas\Validator\Hostname::ALLOW_DNS,
                            'useMxCheck' => false,                            
                        ],
                    ],
                ],
            ]);     
        
        // Add input for "password" field
        $inputFilter->add([
                'name'     => 'password',
                'required' => true,
                'filters'  => [                    
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 6,
                            'max' => 64
                        ],
                    ],
                ],
            ]);     
        
        // Add input for "remember_me" field
        $inputFilter->add([
                'name'     => 'remember_me',
                'required' => false,
                'filters'  => [                    
                ],                
                'validators' => [
                    [
                        'name'    => 'InArray',
                        'options' => [
                            'haystack' => [0, 1],
                        ]
                    ],
                ],
            ]);
        
        // Add input for "redirect_url" field
        $inputFilter->add([
                'name'     => 'redirect_url',
                'required' => false,
                'filters'  => [
                    ['name'=>'StringTrim']
                ],                
                'validators' => [
                    [
                        'name'    => 'StringLength',
                        'options' => [
                            'min' => 0,
                            'max' => 2048
                        ]
                    ],
                ],
            ]);
    }        
}

