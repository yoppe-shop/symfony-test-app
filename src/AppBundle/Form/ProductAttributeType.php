<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\ProductAttribute;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductAttributeType extends AbstractType
{
    public function buildForm (FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('productOptionId')
            ->add('productOptionValueId')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductAttribute::class,
            'empty_data' => new ProductAttribute()
        ]);
    }
}
