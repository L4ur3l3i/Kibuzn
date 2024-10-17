<?php

namespace Kibuzn\Form;

use Kibuzn\Entity\RecurringTransaction;
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
            ->add('start_date', null, [
                'widget' => 'single_text',
            ])
            ->add('amount')
            ->add('description')
            ->add('permanent')
            ->add('iterations')
            ->add('end_date', null, [
                'widget' => 'single_text',
            ])
            ->add('interval_type', ChoiceType::class, [
                'choices' => [
                    'day' => 'day',
                    'week' => 'week',
                    'month' => 'month',
                    'year' => 'year',
                ],
                'constraints' => [
                    new Choice([
                        'choices' => ['day', 'week', 'month', 'year'],
                    ]),
                ],
            ])
            ->add('interval_value')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => RecurringTransaction::class,
        ]);
    }
}
