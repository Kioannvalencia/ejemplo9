<?php

declare(strict_types=1);

namespace Application\Controller;

use Application\Form\FormularioForm;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        // Initialize the form
        $formulario = new FormularioForm();
        
        // Capture the request
        $request = $this->getRequest();
        
        // Check if the request is POST
        if ($request->isPost()) {
            // Get POST data
            $dataPost = $this->params()->fromPost();
            
            // Populate the form with POST data
            $formulario->setData($dataPost);
            
            // Validate the form data
            if ($formulario->isValid()) {
                // Form is valid; process the data (e.g., save to database)
                // Redirect or provide success feedback here
                // Example: return $this->redirect()->toRoute('successRoute');
            } else {
                // Handle invalid form submission (e.g., display errors)
                // Example: you might want to pass errors to the view
                $formulario->getMessages(); // This gets validation messages
            }
        }
        
        // Return the form to the view
        return new ViewModel([
            'miFormulario' => $formulario
        ]);
    }
}
