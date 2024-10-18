<?php

namespace Kibuzn\Form;

use Kibuzn\Entity\OperationType;
use Kibuzn\Entity\RecurringTransaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class RecurringTransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', EntityType::class, [
                'class' => OperationType::class,
                'choice_label' => 'name',
            ])
            ->add('description')
            ->add('amount')
            ->add('start_date', null, [
                'widget' => 'single_text',
            ])
            ->add('duration', ChoiceType::class, [
                'mapped' => false,
                'choices' => [
                    'permanent'                 => 1,
                    'with end date'             => 2,
                    'with number of iterations' => 3,
                ],
                'attr' => [
                    'data-recurring-transaction-duration-target' => 'duration',
                ],
            ])

            ->add('iterations', null, [
                'required' => false,
                'attr' => [
                    'data-recurring-transaction-duration-target' => 'iterations',
                    'min' => 2,
                    'max' => 1000,
                ],
            ])

            ->add('end_date', null, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'data-recurring-transaction-duration-target' => 'endDate',
                ],
            ])

            ->add('interval_type', ChoiceType::class, [
                'choices' => [
                    'day'   => 'day',
                    'week'  => 'week',
                    'month' => 'month',
                    'year'  => 'year',
                ],
                'constraints' => [
                    new Choice([
                        'choices' => ['day', 'week', 'month', 'year'],
                    ]),
                ],
            ])
            ->add('interval_value', null, [
                'attr' => [
                    'value' => 1,
                    'min' => 1,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecurringTransaction::class,
        ]);
    }
}
