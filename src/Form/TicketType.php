<?php

namespace App\Form;

use App\Entity\Ticket;
use App\Entity\Category;
use App\Entity\Equipment;
use App\Repository\CategoryRepository;
use App\Repository\EquipmentRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TicketType extends AbstractType
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
            // ->add('createAt')
            // ->add('updateAt')
            ->add('priority', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 3
                ],
                'label' => '*Priorité (1= urgent 2=normal et 3= pas urgent)',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive()
                ]

            ])

            /**
             * appel a l'entity Category (EntityType) pour recuperer les donnes avec une query 
             */
            ->add(
                'category',
                EntityType::class,
                [
                    'class' => Category::class,
                    'query_builder' => function (CategoryRepository $rep) {
                        return $rep->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    },
                    'choice_label' => "name",
                    'multiple' => false,

                    'attr' => [
                        'class' => 'form-select'
                    ],

                    'label' => 'Categorie',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ]

            )

                        /**
             * appel a l'entity Category (EntityType) pour recuperer les donnes avec une query 
             */
            ->add(
                'equipment',
                EntityType::class,
                [
                    'class' => Equipment::class,
                    'query_builder' => function (EquipmentRepository $repo) {
                        return $repo->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    },
                    'choice_label' => "name",
                    'multiple' => false,

                    'attr' => [
                        'class' => 'form-select'
                    ],

                    'label' => 'Equipement',
                    'label_attr' => [
                        'class' => 'form-label mt-4'
                    ],
                ]

            )

            ->add('isOpen', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input',
                ],
                'required' => false,
                'label' => ' Ticket ouvert ? ',
                'label_attr' => [
                    'class' => 'form-check-label'
                ]
            ])


            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'envoyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
