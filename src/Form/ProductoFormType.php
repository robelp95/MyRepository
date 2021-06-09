<?php

namespace App\Form;

use App\Entity\Producto;
use App\Form\Model\ProductoDto;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProductoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class)
            ->add('descripcion',TextType::class)
            ->add('precio',TextType::class)
            ->add('categoria',TextType::class)
            ->add('base64Image',TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductoDto::class,
        ]);
    }
}
