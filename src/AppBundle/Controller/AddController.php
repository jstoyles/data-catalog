<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Dataset;
use AppBundle\Form\Type\DatasetAsAdminType;
use AppBundle\Form\Type\DatasetAsUserType;
use AppBundle\Utils\Slugger;

/*
 * A controller to handle the addition of new datasets and other entities
 */
class AddController extends Controller {

  /**
   *  We have several pseudo-entities that all relate back to the Person
   *  entity. We'll check this array so we know if we encounter one of them.
   */
  public $personEntities = array(
     'Author',
     'LocalExpert',
     'CorrespondingAuthor',
  );

  /**
   * Build the form to add a new dataset
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/add/Dataset", name="add_dataset")
   */
  public function addAction(Request $request) {
    $dataset = new Dataset();
    $userIsAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    $em = $this->getDoctrine()->getManager();
    $datasetUid = $em->getRepository('AppBundle:Dataset')
                     ->getNewDatasetId();
    $dataset->setDatasetUid($datasetUid);

    if ($userIsAdmin) {
      $form = $this->createForm(new DatasetAsAdminType($userIsAdmin, $datasetUid), $dataset, array(
          'action' => $this->generateUrl('ingest_dataset')));
      return $this->render('default/add_dataset_admin.html.twig', array(
        'form'=> $form->createView(),
        'adminPage'=>true,
        'userIsAdmin'=>$userIsAdmin,
      ));
    } else {
      $form = $this->createForm(new DatasetAsUserType($userIsAdmin, $datasetUid), $dataset, array(
          'action' => $this->generateUrl('ingest_dataset')));
      return $this->render('default/add_dataset_user.html.twig', array(
        'form'=> $form->createView(),
        'adminPage'=>true,
        'userIsAdmin'=>$userIsAdmin,
      ));
    }
  

  }
  
  
  /**
   * Validate the form. Ingest if valid, send user back otherwise·
   *
   * @param Request The current HTTP request
   *
   * @return Response A Response instance
   *
   * @Route("/ingest_dataset", name="ingest_dataset")
   */
  public function ingestDataset(Request $request) {
    $dataset = new Dataset();
    $em = $this->getDoctrine()->getManager();
    $userIsAdmin = $this->get('security.context')->isGranted('ROLE_ADMIN');
    $datasetUid = $em->getRepository('AppBundle:Dataset')
                     ->getNewDatasetId();
    $dataset->setDatasetUid($datasetUid);
    
    if ($userIsAdmin) {
      $form = $this->createForm(new DatasetAsAdminType($userIsAdmin, $datasetUid), $dataset);
    } else {
      $form = $this->createForm(new DatasetAsUserType($userIsAdmin, $datasetUid), $dataset);
    }

    $form->handleRequest($request);

    if ($form->isValid()) {
      $dataset = $form->getData();
      
      $addedEntityName = $dataset->getTitle();
      $slug = Slugger::slugify($addedEntityName);
      $dataset->setSlug($slug);


      $em->persist($dataset);
      $em->flush();
      return $this->render('default/add_success.html.twig', array(
        'adminPage'=>true,
        'entityName'=>'Dataset',
        'displayName'=>'Dataset',
        'addedEntityName'=>$addedEntityName,
        'userIsAdmin'=>$userIsAdmin,
        'newSlug'=>$slug,
      ));
    }
    return $this->render('default/add_dataset.html.twig', array(
      'form' => $form->createView(),
      'userIsAdmin'=>$userIsAdmin,
      'entityName'=>'Dataset',
      'adminPage'=>true,));

  }
  
  
  /**
   * Create a form to add an instance of the entity specified in the URL.
   * Also validates and ingests the object.
   *·
   * @param string $entityName The name of the new entity
   * @param Request $request The current HTTP request
   *·
   * @return Response A Response instance
   *
   * @Route("/add/{entityName}", name="add_new_entity")
   */
  public function addNewEntity($entityName, Request $request) {
    //check if form will appear in a modal
    $modal = $request->get('modal', false);
    $addTemplate = 'add.html.twig';
    $successTemplate = 'add_success.html.twig';
    $action = '/add/'.$entityName;
    if ($modal) {
      $action . '?modal=true';
      $addTemplate = "modal_" . $addTemplate;
      $successTemplate = "modal_" . $successTemplate;
    }

    //make user-friendly name for display
    $entityTypeDisplayName = trim(preg_replace('/(?<!\ )[A-Z]/', ' $0', $entityName));
    
    //prefix with namespaces so it can be called dynamically
    if ($entityName == 'User') {
      $newEntity = 'AppBundle\Entity\Security\\' . $entityName;
    } elseif (in_array($entityName, $this->personEntities)) {
      $newEntity = 'AppBundle\Entity\\Person';
    } else {
      $newEntity = 'AppBundle\Entity\\' . $entityName;
    }
    $newEntityFormType = 'AppBundle\Form\Type\\' . $entityName . "Type";

    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(new $newEntityFormType(), 
                              new $newEntity(),
                              array(
                                'action'=>$action,
                                'method'=>'POST'));
    $form->handleRequest($request);
    if ($form->isValid()) {
      $entity = $form->getData();

      // Create a slug using each entity's getDisplayName method
      $addedEntityName = $entity->getDisplayName();
      $slug = Slugger::slugify($addedEntityName);
      $entity->setSlug($slug);
      
      $em->persist($entity);
      $em->flush();
      return $this->render('default/'.$successTemplate, array(
        'displayName'    => $entityTypeDisplayName,
        'adminPage'=>true,
        'newSlug'=>$slug,
        'entityName'=>$entityName,
        'addedEntityName'=> $addedEntityName));
    }
    return $this->render('default/'.$addTemplate, array(
      'form' => $form->createView(),
      'displayName' => $entityTypeDisplayName,
      'adminPage'=>true,
      'entityName' => $entityName));
      
  } 


}
