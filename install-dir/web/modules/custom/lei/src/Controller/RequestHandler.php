<?php

namespace Drupal\lei\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBuilderInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestHandler extends ControllerBase {

  protected $entityTypeManager;
  protected $formBuilder;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, FormBuilderInterface $formBuilder) {
    $this->entityTypeManager = $entityTypeManager;
    $this->formBuilder = $formBuilder;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('form_builder')
    );
  }

  public function updateFormData($node) {
    $node = $this->entityTypeManager->getStorage('node')->load($node);
    
    if (!$node || $node->getType() !== 'application') {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException();
    }

    $form = $this->formBuilder->getForm('\Drupal\lei\Form\ApplicationForm', $node);

    return $form;
  }
}