<?php
/**
 * @file
 * Contains \Drupal\resume\Form\DeleteForm.
 */
namespace Drupal\resume\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Url;
use Drupal\Core\Render\Element;

/**
 * 
 */
class DeleteForm extends ConfirmFormBase
{
    
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'delete_form';
    }
    
    public function getQuestion()
    {
        return t('Do you want to delete %id?', array(
            '%id' => $this->id
        ));
    }
    
    public function getCancelUrl()
    {
        return new Url('resume.view');
    }
    
    public function getDescription()
    {
        return t('Only do this if you are sure!');
    }
    /**
     * {@inheritdoc}
     */
    public function getConfirmText()
    {
        return t('Delete it!');
    }
    /**
     * {@inheritdoc}
     */
    public function getCancelText()
    {
        return t('Cancel');
    }
    
    public function buildForm(array $form, FormStateInterface $form_state, $id = NULL)
    {
        $this->id = $id;
        return parent::buildForm($form, $form_state);
    }
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $query = \Drupal::database()->delete('resume');
        $query->condition('fe_id', $this->id);
        $query->execute();
        drupal_set_message("succesfully deleted");
        $form_state->setRedirect('resume.view');
    }
    
    
}