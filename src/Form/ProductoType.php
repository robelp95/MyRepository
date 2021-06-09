<?php

namespace App\Form;

use App\Entity\Producto;
use App\Form\Model\ProductoDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('descripcion')
            ->add('precio')
            ->add('base64Image')
            ->add('categoria')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductoDto::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return "";
    }

    public function getName(){
        return "";
    }
}
