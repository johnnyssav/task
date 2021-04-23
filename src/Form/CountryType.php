<?php

namespace App\Form;

use App\Entity\Country;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Country name missing'
                    ])
                ]
            ])
            ->add('cname', TextType::class, [
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[a-z]+$/',
                        'match' => true,
                        'message' => 'Canonical name cannot contain uppercase or white space or any other symbols'
                    ]),
                    new NotBlank([
                        'message' => 'Canonical name cannot be blank'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Country::class,
        ]);
    }
}
