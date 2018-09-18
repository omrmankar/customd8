<?php
/**
 * @file
 * Contains \Drupal\resume\Form\ResumeForm.
 */
namespace Drupal\resume\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Database;
use Drupal\resume\Form\Url;

class ResumeForm extends FormBase
{
    /**
     * {@inheritdoc}
     */
    public function getFormId()
    {
        return 'resume_form';
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state)
    {
        
        $conn   = Database::getConnection();
        $record = array();
        if (isset($_GET['num'])) {
            $query  = $conn->select('resume', 'm')->condition('fe_id', $_GET['num'])->fields('m');
            $record = $query->execute()->fetchAssoc();
        }
        
        $form['candidate_name']         = array(
            '#type' => 'textfield',
            '#title' => t('Candidate Name:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['candidate_name']) && $_GET['num']) ? $record['candidate_name'] : ''
        );
        $form['candidate_mail']         = array(
            '#type' => 'email',
            '#title' => t('Email ID:'),
            '#required' => TRUE,
            '#default_value' => (isset($record['candidate_mail']) && $_GET['num']) ? $record['candidate_mail'] : ''
        );
        $form['candidate_number']       = array(
            '#type' => 'tel',
            '#title' => t('Mobile no'),
            '#default_value' => (isset($record['candidate_number']) && $_GET['num']) ? $record['candidate_number'] : ''
        );
        $form['candidate_dob']          = array(
            '#type' => 'date',
            '#title' => t('DOB'),
            '#required' => TRUE,
            '#default_value' => (isset($record['candidate_dob']) && $_GET['num']) ? $record['candidate_dob'] : ''
        );
        $form['candidate_gender']       = array(
            '#type' => 'select',
            '#title' => ('Gender'),
            '#options' => array(
                'Female' => t('Female'),
                'male' => t('Male')
            ),
            '#default_value' => (isset($record['candidate_gender']) && $_GET['num']) ? $record['candidate_gender'] : ''
        );
        $form['candidate_confirmation'] = array(
            '#type' => 'radios',
            '#title' => ('Are you above 18 years old?'),
            '#options' => array(
                'Yes' => t('Yes'),
                'No' => t('No')
            ),
            '#default_value' => (isset($record['candidate_confirmation']) && $_GET['num']) ? $record['candidate_confirmation'] : ''
        );
        $form['candidate_copy']         = array(
            '#type' => 'checkbox',
            '#title' => t('Send me a copy of the application.'),
            '#default_value' => (isset($record['candidate_copy']) && $_GET['num']) ? $record['candidate_copy'] : ''
        );
        $form['actions']['#type']       = 'actions';
        $form['actions']['submitbtn']   = array(
            '#type' => 'submit',
            '#value' => $this->t('Save'),
            '#button_type' => 'primary'
        );
        return $form;
    }
    
    /**
     * {@inheritdoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state)
    {
        
        if (strlen($form_state->getValue('candidate_number')) < 10) {
            $form_state->setErrorByName('candidate_number', $this->t('Mobile number is too short.'));
        }
        
    }
    
    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $candidate_name         = $form_state->getValue('candidate_name');
        $candidate_mail         = $form_state->getValue('candidate_mail');
        $candidate_number       = $form_state->getValue('candidate_number');
        $candidate_dob          = $form_state->getValue('candidate_dob');
        $candidate_gender       = $form_state->getValue('candidate_gender');
        $candidate_confirmation = $form_state->getValue('candidate_confirmation');
        
        if (isset($_GET['num'])) {
            db_update('resume')->fields(array(
                'candidate_name' => $candidate_name,
                'candidate_mail' => $candidate_mail,
                'candidate_number' => $candidate_number,
                'candidate_dob' => $candidate_dob,
                'candidate_gender' => $candidate_gender,
                'candidate_confirmation' => $candidate_confirmation
            ))->condition('fe_id', $_GET['num'])->execute();
            
            drupal_set_message("Successfully Update Condidate Entry");
            $form_state->setRedirect('resume.view');
            
        } else {
            
            db_insert('resume')->fields(array(
                'candidate_name' => $candidate_name,
                'candidate_mail' => $candidate_mail,
                'candidate_number' => $candidate_number,
                'candidate_dob' => $candidate_dob,
                'candidate_gender' => $candidate_gender,
                'candidate_confirmation' => $candidate_confirmation
            ))->execute();
            
            drupal_set_message("Successfully Saved Condidate Entry");
            $form_state->setRedirect('resume.view');
        }
        
    }
    
}