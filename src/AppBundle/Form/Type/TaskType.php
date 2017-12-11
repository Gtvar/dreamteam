<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

/**
 * Class TaskType
 */
class TaskType extends AbstractType implements AutoConvertibleInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class' => Task::class,
            'allow_extra_fields' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
