<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form builder for Subject Geographic Area Detail entry
 */
class SubjectGeographicAreaDetailType extends AbstractType {

  /**
   * Build form
   *
   * @param FormBuilderInterface
   * @param array $options
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('geographic_area_detail_name');
    $builder->add('geographic_area_detail_authority');
    $builder->add('save','submit',array('label'=>'Submit'));
  }

  /**
   * Set defaults
   *
   * @param OptionsResolverInterface
   */
  public function setDefaultOptions(OptionsResolverInterface $resolver) {
    $resolver->setDefaults(array(
      'data_class' => 'AppBundle\Entity\SubjectGeographicAreaDetail'
    ));
  }

  public function getName() {
    return 'subjectGeographicAreaDetail';
  }

}

