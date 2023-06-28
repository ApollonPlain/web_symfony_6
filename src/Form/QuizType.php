<?php

namespace App\Form;

use App\Entity\Quiz;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question')
            ->add('answerA')
            ->add('isA')
            ->add('answerB')
            ->add('isB')
            ->add('answerC')
            ->add('isC')
            ->add('answerD')
            ->add('isD')
            ->add('answerE')
            ->add('isE')
            ->add('answerF')
            ->add('isF')
            ->add('answerG')
            ->add('category')
            ->add('isG')
            ->add('sources')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
