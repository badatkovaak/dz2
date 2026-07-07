<?php

namespace App\Form;

use App\Entity\Link;
use App\Enum\LinkExpirationType as LEType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            /* ->add('shortUrl') */
            ->add('longUrl', UrlType::class)
            /* ->add('creationTime') */
            /* ->add('lastUseTime') */
            /* ->add('useCount') */
            ->add('expiryDate', DateTimeType::class, ['required' => false])
            ->add('expirationType', EnumType::class, ['class' => LEType::class]);
        /* ->add('owner', EntityType::class, [ */
        /* 'class' => User::class, */
        /* 'choice_label' => 'id', */
        /* ]) */
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Link::class,
            'csrf_protection' => false,
        ]);
    }
}
