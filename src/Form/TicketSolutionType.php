<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\TicketSolutions;
use App\Repository\TicketRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketSolutionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => [
                'class' => 'form-control',
                'minlength' => '5',
                'maxlength' => '255'
            ],
            'label' => 'Nom',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [
                new Assert\NotBlank()
            ]
            ])


        ->add('description', TextareaType::class, [
            'attr' => [
                'class' => 'form-control',

            ],
            'label' => 'Description',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            'constraints' => [

                new Assert\NotBlank()
            ]
        ])
            // ->add('createdAt')
            // ->add('updatedAt')
         /**
             * appel a l'entity ticket (EntityType) pour recuperer les donnes avec une query 
             */
            ->add('ticket', EntityType::class, [
                'class' => Ticket::class,
                'query_builder' => function (TicketRepository $rep) {
                    return $rep->createQueryBuilder('c')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => "name",
                'multiple' => false,
            
                'attr' =>[
                    'class' => 'form-select'
                ],

                'label' => 'Ticket Nom',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ]

                )

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'envoyer'
            ])
            
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => TicketSolutions::class,
        ]);
    }
}
