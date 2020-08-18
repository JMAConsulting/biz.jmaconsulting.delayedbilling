<?php

define('PAYMENT_NOTIFICATION', 76);

use CRM_Delayedbilling_ExtensionUtil as E;

/**
 * Job.SendNotification API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Sendnotification_spec(&$spec) {
  $spec['days']['api.required'] = 1;
}

/**
 * Job.SendNotification API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_Sendnotification($params) {
  $days = CRM_Utils_Array::value('days', $params, 1);

  // Get all payments which will be processed tomorrow.
  $sql = "SELECT cr.contact_id, c.contribution_page_id 
    FROM civicrm_contribution_recur cr 
    INNER JOIN civicrm_payment_processor pp ON cr.payment_processor_id = pp.id
    INNER JOIN civicrm_contribution c ON c.contribution_recur_id = cr.id
    WHERE cr.contribution_status_id = 5
    AND pp.name = 'Moneris'
    AND DATE(cr.next_sched_contribution_date) = DATE(DATE_ADD(NOW(), INTERVAL $days DAY))";
  $dao = CRM_Core_DAO::executeQuery($sql)->fetchAll();
  $cids = $errors = [];
  foreach ($dao as $result) {
    if (_checkDelayedPayment($result['contribution_page_id'])) {
      // This contribution is part of a delayed billing series. Send a notification that payment is due tomorrow.
      try {
        civicrm_api3('Email', 'send', [
          'contact_id' => $result['contact_id'],
          'template_id' => PAYMENT_NOTIFICATION,
        ]);
      }
      catch (CiviCRM_API3_Exception $e) {
        $errors[] = $e->getMessage();
      }
      $cids[] = $result['contact_id'];
    }
  }

  if (count($errors) > 0) {
    return civicrm_api3_create_error(
      ts("Completed, but with %1 errors. %2 records processed.",
        array(
          1 => count($errors),
          2 => count($cids),
        )
      ) . "<br />" . implode("<br />", $errors)
    );
  }
  else {
    return civicrm_api3_create_success(ts("Completed, %1 records processed.",
        array(
          1 => count($cids),
        )
      ) . "<br />" . implode("<br />", $cids), $params, 'Job', 'SendNotification');
  }
}
