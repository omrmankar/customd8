<?php

namespace Drupal\resume\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Component\Render\FormattableMarkup;
/**
 * Controller for the salutation message
 */
class ViewController extends ControllerBase
{     
    /*
     * View Candiadate Table
     *
     * @return string
     */
    public function view()
      {
        $host  = $GLOBALS['base_url']; 
        $query = db_select('resume', 'fe');
        $query->fields('fe', array(
            'fe_id',
            'candidate_name ',
            'candidate_mail',
            'candidate_number',
            'candidate_dob',
            'candidate_gender',
            'candidate_confirmation'
        ))->orderBy('fe.fe_id');
        // Execute query
        $results = $query->execute();
        $header  = array(
            t('Sr No'),
            t('UID'),
            t('Candidate Name'),
            t('Candidate Email'),
            t('Candidate Number'),
            t('DOB'),
            t('Gender'),
            t('Above 18+'),
            t('Action')
        );
        $rows    = array();
        $sq_id   = 1;
        foreach ($results as $result) {
            $fe_id                  = $result->fe_id;
            // Edit and Delete link for 
            $action                 = new FormattableMarkup('<a href="@host/resume/myform?num=@id" class="use-ajax"
  data-dialog-type="modal">Edit</a> |' . ' <a href="@host/resume/myform/delete/@id" class="use-ajax"
  data-dialog-type="modal">Delete</a>', array(
                '@id' => $fe_id,
                '@host' => $host
            ));
            $uid                    = $result->fe_id;
            $candidate_name         = $result->candidate_name;
            $candidate_mail         = $result->candidate_mail;
            $candidate_number       = $result->candidate_number;
            $candidate_dob          = $result->candidate_dob;
            $candidate_gender       = $result->candidate_gender;
            $candidate_confirmation = $result->candidate_confirmation;
            $rows[]                 = array(
                $sq_id,
                $uid,
                $candidate_name,
                $candidate_mail,
                $candidate_number,
                $candidate_dob,
                $candidate_gender,
                $candidate_confirmation,
                $action
            );
            $sq_id++;
        }
        // Markup link for add candidate form
        $link  = new FormattableMarkup('<a href="@hosts/resume/myform" class="use-ajax"
  data-dialog-type="modal">+ Add Candidate</a>', array(
            '@hosts' => $host
        ));

        $build['markup_link']        = array(
            '#type' => 'markup',
            '#markup' => $link
        );
       // render message using
        $build['markup_msg']        = array(
           '#theme' => 'resume',
           '#mge' => 'You can Add Candidate Using Above link',
         );

        $build['pager_table'] = array(
            '#theme' => 'table',
            '#header' => $header,
            '#rows' => $rows
        );

        $build['pager']       = array(
            '#type' => 'pager'
        );

        return $build;
    }
}