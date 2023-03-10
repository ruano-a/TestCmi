<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Comment;
use App\Validator\RecaptchaV3;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('text', TextType::class, [
                'attr' => ['minlength' => 5, 'maxlength' => 20, 'placeholder' => 'Your comment...']])
            ->add('article', EntityType::class, [
                'class' => Article::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                },
                'choice_label' => 'title',
            ])
            ->add('recaptcha', HiddenType::class, [
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new NotNull(),
                    new RecaptchaV3('post_comment')
                ]
            ])
            ->add('parentComment', EntityType::class, [
                'class' => Comment::class,
                'choice_label' => 'text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}