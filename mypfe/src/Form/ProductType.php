<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product_name', TextType::class)
            ->add('product_description', TextType::class)
            ->add('price', MoneyType::class)
            ->add('qte', IntegerType::class, [
                'label' => 'Quantity',
                'required' => true,
                'attr' => [
                    'min' => 1, // Ensures at least one product is required
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('photoFile', FileType::class, [
                'label' => 'Image (JPEG or PNG file)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image',
                    ])
                ],
            ])

            ->add('discountPercentage', NumberType::class, [
                'required' => false,
                'scale' => 2,
                'label' => 'Discount Percentage (%)'
            ])
            ->add('promotionStartDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Promotion Start Date'
            ])
            ->add('promotionEndDate', DateTimeType::class, [
                'required' => false,
                'widget' => 'single_text',
                'label' => 'Promotion End Date'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            'validation_groups' => false, // Disable validation groups
        ]);
    }
}
