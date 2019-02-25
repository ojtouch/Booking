<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class BookingType extends ApplicationType
{
    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::class, $this->getConfiguration("Date d'arrivée", 'La date à laquelle vous allez arriver'))
            ->add('endDate', TextType::class, $this->getConfiguration('Date de départ', 'La date à laquelle vous allez quitter les lieux'))
            ->add('comment', TextareaType::class, $this->getConfiguration(false, 'Si vous avez un commentaire n\'hésitez pas à nous en faire part', [
                'required' => false,
            ]))
        ;
        //Pour transformer la date reçue en français en une date en anglais
        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
