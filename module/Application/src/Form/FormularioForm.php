<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator\StringLength;
use Laminas\Validator\EmailAddress;
use Laminas\Validator\NotEmpty;
use Laminas\Validator\InArray;
use Laminas\Validator\Date;
use Laminas\Validator\Callback;

class FormularioForm extends Form 
{
    public function __construct()
    {
        parent::__construct('Formulario');

        $this->setAttribute('method', 'post'); 
        $this->setAttribute('class', 'form-horizontal');
        $this->setAttribute('role', 'form'); 
        $this->setAttribute('id', 'Formulario');  
        
        $this->addElements(); 
        $this->addInputFilter(); 
    }

    protected function addElements()
    {
        $this->add([
            'type' => 'submit',
            'name' => 'enviar',
            'attributes' => [
                'class' => 'btn btn-primary',
                'value' => 'Enviar',
                'id' => 'submit'
            ]
        ]);
        
        $this->add([
            'type' => 'text',
            'name' => 'nombre',
            'options' => [
                'label' => 'Nombre:',
            ],
            'attributes' => [
                'id' => 'nombre',
                'class' => 'form-control',
                
            ],
        ]);

        $this->add([
            'type' => 'email',
            'name' => 'correo',
            'options' => [
                'label' => 'Correo Electrónico:',
            ],
            'attributes' => [
                'id' => 'correo',
                'class' => 'form-control',
                
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'destino',
            'options' => [
                'label' => 'Destino:',
                'empty_option' => '-Seleccione su Destino-',
                'value_options' => [
                    'MX' => 'México',
                    'USA' => 'Estados Unidos',
                    'ES' => 'España',
                    'CA' => 'Canadá',
                    'IT' => 'Italia',
                    'PL' => 'Playa del Carmen',
                    'LA' => 'Los Alpes',
                    'NY' => 'New York',
                    'AT' => 'Atenas'
                ],
            ],
            'attributes' => [
                'id' => 'destino',
                'class' => 'form-control',
                
            ],    
        ]);

        $this->add([
            'type' => 'date',
            'name' => 'fecha_salida',
            'options' => [
                'label' => 'Fecha de Salida:',
                'format' => 'Y-m-d',
            ],
            'attributes' => [
                'id' => 'fecha_salida',
                'class' => 'form-control',
                 
            ],
        ]);

        $this->add([
            'type' => 'date',
            'name' => 'fecha_regreso',
            'options' => [
                'label' => 'Fecha de Regreso:',
                'format' => 'Y-m-d',
            ],
            'attributes' => [
                'id' => 'fecha_regreso',
                'class' => 'form-control',
                
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'preferencias',
            'options' => [
                'label' => 'Preferencias Adicionales:',
            ],
            'attributes' => [
                'id' => 'preferencias',
                'class' => 'form-control',
                'rows' => 4,
                'placeholder' => 'Indica cualquier preferencia especial aquí',
            ],
        ]); 
    }

    public function addInputFilter()
    {
        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'nombre',
            'required' => true,
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 5,
                        'max' => 15,
                        'messages' => [
                            StringLength::TOO_SHORT => 'El campo debe tener entre 5 y 15 caracteres.',
                            StringLength::TOO_LONG => 'El campo debe tener entre 5 y 15 caracteres.',
                        ],
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'Campo obligatorio.',
                        ],
                    ],
                ],
            ],
        ]);

        // Validación para el campo 'correo'
        $inputFilter->add([
            'name' => 'correo',
            'required' => true,
            'validators' => [
                [
                    'name' => 'EmailAddress',
                    'options' => [
                        'messages' => [
                            EmailAddress::INVALID_FORMAT => 'El formato del correo electrónico no es válido.',
                            EmailAddress::INVALID_HOSTNAME => 'El nombre de dominio no es válido.',
                        ],
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'Campo obligatorio.',
                        ],
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'destino',
            'required' => true,
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => ['MX', 'USA', 'ES', 'CA', 'IT', 'PL', 'LA', 'NY', 'AT'],
                        'messages' => [
                            InArray::NOT_IN_ARRAY => 'Selección no válida.',
                        ],
                    ],
                ],
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'Campo obligatorio.',
                        ],
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'fecha_salida',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Date',
                    'options' => [
                        'format' => 'Y-m-d',
                        'messages' => [
                            Date::INVALID_DATE => 'La fecha de salida no es válida.',
                        ],
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'fecha_regreso',
            'required' => true,
            'validators' => [
                [
                    'name' => 'Date',
                    'options' => [
                        'format' => 'Y-m-d',
                        'messages' => [
                            Date::INVALID_DATE => 'La fecha de regreso no es válida.',
                        ],
                    ],
                ],
                [
                    'name' => 'Callback',
                    'options' => [
                        'callback' => function ($value, $context) {
                            // Asegúrate de que la fecha de regreso sea posterior a la fecha de salida
                            return $value > $context['fecha_salida'];
                        },
                        'messages' => [
                            Callback::INVALID_VALUE => 'La fecha de regreso debe ser posterior a la fecha de salida.',
                        ],
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'preferencias',
            'required' => true,
            'validators' => [
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'messages' => [
                            NotEmpty::IS_EMPTY => 'El campo preferencias no puede estar vacío.',
                        ],
                    ],
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 10,
                        'max' => 500,
                        'messages' => [
                            StringLength::TOO_SHORT => 'El comentario debe tener al menos 10 caracteres.',
                            StringLength::TOO_LONG => 'El comentario no puede tener más de 500 caracteres.',
                        ],
                    ],
                ],
            ],
        ]);
    }
}
