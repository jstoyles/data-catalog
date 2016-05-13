<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Define relationships among datasets
 *
 * @ORM\Entity
 * @ORM\Table(name="dataset_relationships")
 */
class DatasetRelationship {
  /**
   * @ORM\Column(type="integer",name="relationship_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $relationship_attributes;

  /**
   * @ORM\Column(type="string",length=512, nullable=true)
   */
  protected $relationship_notes;

  /**
   * @ORM\Column(type="integer")
   */
  protected $related_dataset_uid;

  /**
   * @ORM\ManyToOne(targetEntity="Dataset",inversedBy="related_datasets")
   * @ORM\JoinColumn(name="parent_dataset_uid",referencedColumnName="dataset_uid")
   */
  protected $parent_dataset_uid;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set relationship_attributes
     *
     * @param string $relationshipAttributes
     * @return DatasetRelationship
     */
    public function setRelationshipAttributes($relationshipAttributes)
    {
        $this->relationship_attributes = $relationshipAttributes;

        return $this;
    }

    /**
     * Get relationship_attributes
     *
     * @return string 
     */
    public function getRelationshipAttributes()
    {
        return $this->relationship_attributes;
    }

    /**
     * Set relationship_notes
     *
     * @param string $relationshipNotes
     * @return DatasetRelationship
     */
    public function setRelationshipNotes($relationshipNotes)
    {
        $this->relationship_notes = $relationshipNotes;

        return $this;
    }

    /**
     * Get relationship_notes
     *
     * @return string 
     */
    public function getRelationshipNotes()
    {
        return $this->relationship_notes;
    }

    /**
     * Set related_dataset_uid
     *
     * @param integer $relatedDatasetUid
     * @return DatasetRelationship
     */
    public function setRelatedDatasetUid($relatedDatasetUid)
    {
        $this->related_dataset_uid = $relatedDatasetUid;

        return $this;
    }

    /**
     * Get related_dataset_uid
     *
     * @return integer 
     */
    public function getRelatedDatasetUid()
    {
        return $this->related_dataset_uid;
    }

    /**
     * Set parent_dataset_uid
     *
     * @param \AppBundle\Entity\Dataset $parentDatasetUid
     * @return DatasetRelationship
     */
    public function setParentDatasetUid(\AppBundle\Entity\Dataset $parentDatasetUid = null)
    {
        $this->parent_dataset_uid = $parentDatasetUid;

        return $this;
    }

    /**
     * Get parent_dataset_uid
     *
     * @return \AppBundle\Entity\Dataset 
     */
    public function getParentDatasetUid()
    {
        return $this->parent_dataset_uid;
    }
}
