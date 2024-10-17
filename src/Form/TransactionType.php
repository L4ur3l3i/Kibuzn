<?php

namespace Kibuzn\Form;

use Kibuzn\Entity\OperationType;
use Kibuzn\Entity\RecurringTransaction;
use Kibuzn\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transaction_date', null, [
                'widget' => 'single_text',
            ])
            ->add('type', EntityType::class, [
                'class' => OperationType::class,
                'choice_label' => 'name',
            ])
            ->add('description')
            ->add('amount')
//            ->add('recurrent')
//            ->add('recurrence_number')
//            ->add('created_at', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updated_at', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('deleted_at', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('recurring_transaction_id', EntityType::class, [
//                'class' => RecurringTransaction::class,
//                'choice_label' => 'id',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
