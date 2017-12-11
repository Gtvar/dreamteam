<?php

namespace AppBundle\Exception;

use Symfony\Component\Form\FormInterface;

/**
 * Class FormNotValidException
 */
class FormNotValidException extends \Exception
{
    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * FormAwareException constructor.
     *
     * @param FormInterface $message
     * @param integer       $code
     * @param \Exception    $previous
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        $this->form = $message;

        parent::__construct("Form is not valid", $code, $previous);
    }

    /**
     * Get form
     *
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
