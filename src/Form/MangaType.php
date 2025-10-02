<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Editor;
use App\Entity\Manga;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\MangaGenre;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MangaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
            ])
            ->add('cover', UrlType::class, [
                'label' => 'Couverture'
            ])
            ->add('Plot', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('pageNumber', NumberType::class, [
                'label' => 'Nombre de pages'
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => MangaGenre::cases(), 
                'choice_label' => fn (MangaGenre $genre) => $genre->value, 
                'choice_value' => fn (?MangaGenre $genre) => $genre?->value, 
                'placeholder' => 'Choisir un genre',
            ])
            ->add('Prix', TextType::class, [
                'label' => 'Prix'
            ])
            ->add('authors', EntityType::class, [
                'class' => Author::class,
                'label' => 'Auteur',
                'choice_label' => 'name',
                'multiple' => true,
                'by_reference' => false,
            ])
            ->add('editor', EntityType::class, [
                'class' => Editor::class,
                'label' => 'Editeur',
                'choice_label' => 'name',
                'placeholder' => 'Choisir un éditeur',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Manga::class,
        ]);
    }
}
