<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class AdType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Tapez un titre pour votre annonce'))
            ->add('slug', TextType::class, $this->getConfiguration('Chaîne URL', 'Adresse web', [
                'required' => false,
                ])
            )
            ->add('coverImage', UrlType::class, $this->getConfiguration('URL de l\'image principale', 'Donnez l\'adresse d\'une image qui donne vraiment envie'))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Donnez une description générale de l\'annonce'))
            ->add('contenu', TextareaType::class, $this->getConfiguration('Description détaillée', 'Donnez une description détaillée de l\annonce'))
            ->add('rooms', IntegerType::class, $this->getConfiguration('Nombre de chambre', 'Indiquez le nombre de chambre disponibles'))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit', 'Indiquez le prix que vous voulez par nuit'))
            ->add('images', CollectionType::class,
            [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
