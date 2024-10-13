<?php

namespace Kibuzn\Form;

use Kibuzn\Entity\Bank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BankType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url');  // This field is always available

        // Conditionally add 'brand' and 'logo' fields if this is the "edit" mode
        if ($options['is_edit']) {
            $builder
                ->add('brand')
                ->add('logo', FileType::class, [
                    'mapped' => false,
                    'required' => false,
                ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bank::class,
            'is_edit' => false,  // By default, assume this is not the "edit" mode
        ]);
    }
}

