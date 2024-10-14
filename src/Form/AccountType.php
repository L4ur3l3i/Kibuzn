<?php

namespace Kibuzn\Form;

use Kibuzn\Entity\Account;
use Kibuzn\Entity\Bank;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bank', EntityType::class, [
                'class' => Bank::class,
                'choice_label' => 'brand',
            ])
        ;

        if ($options['is_edit']) {
            $builder->add('name');
            $builder->add('main');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
            'is_edit' => false,
        ]);
    }
}
