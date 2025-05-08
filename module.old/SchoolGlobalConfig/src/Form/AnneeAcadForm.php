<?php

namespace SchoolGlobalConfig\Form;

use Laminas\Form\Fieldset;
use Laminas\InputFilter\InputFilter;
use Laminas\Form\Form;


/**
 * This form is used to collect user feedback data like user E-mail, 
 * message subject and text.
 */
class AnneeAcadForm extends Form
{
  // Constructor.   
  public function __construct()
  {
    // Define form name
    parent::__construct('anneeacad-form');

    // Set POST method for this form
    $this->setAttribute('method','post');
    $this->setAttribute('id', 'anneeacadform');
    $this->setAttribute('novalidate','true');
        	
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
            'name' => 'code',
            'attributes' => [                
                'class'=>'form-control', 
                'placeholder'=>'Code',
                'ng-model'=>'usr.code',
                'required' => true,
            ],
            'options' => [
                'label' => 'Code',
            ],
        ]);
    $this->add([
	    'type'  => 'text',
            'name' => 'nom',
            'attributes' => [                
                'class'=>'form-control', 
                'placeholder'=>'Nom',
                'ng-model'=>'usr.name',
                'required' => true,
            ],
            'options' => [
                'label' => 'Nom',
            ],
        ]);
        $this->add([            
            'type'  => 'checkbox',
            'name' => 'set_default',
            'options' => [
                'label' => 'Année par défaut',
            ],
        ]);
            
    $this->add([
            'type'  => 'text',
            'name' => 'date_debut',			
            'attributes' => [                
			  'id' => 'date_debut',
                'class'=>'form-control',
                
                'placeholder'=>'jj-mm-jjjj',
            ],
            'options' => [
                'label' => 'Date de début',
            ],
        ]);   

    $this->add([
            'type'  => 'text',
            'name' => 'date_fin',			
            'attributes' => [                
			  'id' => 'date_fin',
                'class'=>'form-control',
                
                'placeholder'=>'jj-mm-jjjj',
            ],
            'options' => [
                'label' => 'Date de fin *',
            ],
        ]);
// Add the submit button
    $button = new \Laminas\Form\Element\Button('submit');
    $button->setLabel('Enrégistrer')
       ->setValue('foo')
            ->setAttributes([
                'id'=>'login-btn',
                'value' => 'Enrégistrer',
                'class'=>'btn btn-danger'        
            ]);
    $this->add($button);
   /* $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [ 
                'id'=>'login-btn',
                'value' => 'Enrégistrer',
                'class'=>'btn btn-danger btn-lg btn-block'
            ],
        ]);*/
    
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

