<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\ThumbGenerator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Class ThumbGeneratorType
 * @package App\Form
 */
class ThumbGeneratorType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(ThumbGeneratorTypeConst::FIELD_IMAGE_KEY,FileType::class, [
                'label' => ThumbGeneratorTypeConst::FIELD_IMAGE_LABEL,
                'multiple' => false,
                'required' => true,
                'constraints' => [
                    new File([
                        'maxSize' => ThumbGeneratorTypeConst::FIELD_IMAGE_MAX_SIZE,
                        'maxSizeMessage' => ThumbGeneratorTypeConst::FIELD_IMAGE_MAX_SIZE_MESSAGE,
                        'mimeTypes' => ThumbGeneratorTypeConst::FIELD_IMAGE_MIME_TYPES,
                        'mimeTypesMessage' => ThumbGeneratorTypeConst::FIELD_IMAGE_MIME_TYPES_MESSAGE,
                    ])
                ],
            ])
            ->add(ThumbGeneratorTypeConst::FIELD_SAVE_LOCATION_KEY, ChoiceType::class, [
                'label' => ThumbGeneratorTypeConst::FIELD_SAVE_LOCATION_LABEL,
                'required' => true,
                'choices' => array_flip(ThumbGeneratorTypeConst::FIELD_SAVE_LOCATIONS)
            ])
            ->add(ThumbGeneratorTypeConst::FIELD_GENERATE_AND_SEND_KEY, SubmitType::class, [
                'label' => ThumbGeneratorTypeConst::FIELD_GENERATE_AND_SEND_LABEL
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ThumbGenerator::class,
        ]);
    }
}
