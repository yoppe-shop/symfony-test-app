<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Product;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvents;

class ProductType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('model')
            ->add('name')
            ->add('created', DateType::class, [
                'widget' => 'choice',
            ])
            ->add('order', TextType::class, [
                'mapped' => false,
            ])
            ->add('productAttributes', CollectionType::class, [
                'entry_type' => ProductAttributeType::class,
                'allow_add' => true,
                'by_reference' => false,
            ])
            ->add('Speichern', SubmitType::class, [
                'label' => 'Speichere Produkt'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
