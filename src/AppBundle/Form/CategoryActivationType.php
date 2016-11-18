<?php
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CategoryActivationType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('category', IntegerType::class, ['mapped' => false]);
  }

  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(['data_class' =>
                                'AppBundle\Entity\CategoryActivation',
                            'csrf_protection' => false]);
  }
}
?>
