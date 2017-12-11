<?php

namespace AppBundle\Request\ParamConverter;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use AppBundle\Form\Type\AutoConvertibleInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FormTypeConverter
 */
class FormTypeConverter implements ParamConverterInterface
{
    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * FormTypeConverter constructor.
     *
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $typeClass = $configuration->getClass();

        $formData = $this->getFormData($request, $configuration);
        $form = $this->formFactory->create($typeClass, $formData);

        if ($request->isMethod('POST')) {
            $paramsBag = $request->request;
        } else {
            $paramsBag = $request->query;
        }

        $requestPayload = $paramsBag->all();
        $form->submit($requestPayload);

        $param = $configuration->getName();
        $request->attributes->set($param, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration)
    {
        if (is_null($configuration->getClass())) {
            return false;
        }

        $interfaces = class_implements($configuration->getClass());

        return array_key_exists(AutoConvertibleInterface::class, $interfaces);
    }

    /**
     * Get form data
     *
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return null|mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function getFormData(Request $request, ParamConverter $configuration)
    {
        if (!isset($configuration->getOptions()['formDataAttribute'])) {
            return null;
        }

        $formData = $request->attributes->get($configuration->getOptions()['formDataAttribute']);

        if (is_null($formData)) {
            throw new \InvalidArgumentException('Attribute is missing');
        }

        if (!is_object($formData)) {
            throw new \InvalidArgumentException('Attribute must be object. Use Doctrine param converter before');
        }

        return $formData;
    }
}
