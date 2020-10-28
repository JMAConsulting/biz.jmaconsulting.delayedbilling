<?php

define('FAILED_NOTIFICATION', 77);

require_once 'delayedbilling.civix.php';
// phpcs:disable
use CRM_Delayedbilling_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function delayedbilling_civicrm_config(&$config) {
  _delayedbilling_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function delayedbilling_civicrm_xmlMenu(&$files) {
  _delayedbilling_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function delayedbilling_civicrm_install() {
  _delayedbilling_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function delayedbilling_civicrm_postInstall() {
  _delayedbilling_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function delayedbilling_civicrm_uninstall() {
  _delayedbilling_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function delayedbilling_civicrm_enable() {
  _delayedbilling_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function delayedbilling_civicrm_disable() {
  _delayedbilling_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function delayedbilling_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _delayedbilling_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function delayedbilling_civicrm_managed(&$entities) {
  _delayedbilling_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function delayedbilling_civicrm_caseTypes(&$caseTypes) {
  _delayedbilling_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function delayedbilling_civicrm_angularModules(&$angularModules) {
  _delayedbilling_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function delayedbilling_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _delayedbilling_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function delayedbilling_civicrm_entityTypes(&$entityTypes) {
  _delayedbilling_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_themes().
 */
function delayedbilling_civicrm_themes(&$themes) {
  _delayedbilling_civix_civicrm_themes($themes);
}

/**
 * Function to check if the contribution page allows delayed payments.
 *
 * @param $id
 * Contribution Page ID
 *
 * @return bool TRUE|FALSE
 */
function _checkDelayedPayment($id) {
  $contribForms = Civi::settings()->get('delayedbilling_active_contributionforms');
  $contribForms = CRM_Core_DAO::unSerializeField($contribForms, CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND);
  if (!empty($id) && (array_key_exists($id, $contribForms) && !empty($contribForms[$id])) || array_search($id, $contribForms) !== FALSE) {
    return TRUE;
  }
  return FALSE;
}

/**
 * Implements hook_civicrm_buildForm().
 */
function delayedbilling_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Contribute_Form_ContributionPage_Amount' && !empty($_GET['snippet'])) {
    $form->addYesNo('is_delayed', ts('Delayed Billing Active?'), TRUE);
    if (_checkDelayedPayment($form->getVar('_id'))) {
      $form->setDefaults(['is_delayed' => 1]);
    }
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => 'CRM/DelayedBillingSetting.tpl',
    ));
  }
  if ($formName === 'CRM_Contribute_Form_Contribution_Main' || $formName === 'CRM_Contribute_Form_Contribution_Confirm') {
    $formId = $form->getVar('_id');
    if (_checkDelayedPayment($formId)) {
      $frequencyOptions = Civi::settings()->get('delayedbilling_frequencies');
      $frequencyOptions = CRM_Core_DAO::unSerializeField($frequencyOptions, CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND);
      if (count($frequencyOptions) === 1) {
        $partialPaymentElement = $form->addElement('advcheckbox', 'partial_payment', E::ts('Would you like to pay %1', ['%1' => _getFrequencyLabel($frequencyOptions[0])]));
        $frequency = $form->addElement('hidden', 'partial_payment_frequency', $frequencyOptions[0]);
        $form->assign('delayedFields', ['partial_payment']);
      }
      elseif (count($frequencyOptions)) {
        $partialPaymentElement = $form->addElement('advcheckbox', 'partial_payment', E::ts('Do you want to split your payments over the course of the year?'));
        $frequencyQFOptions = frequencyOptions();
        foreach ($frequencyOptions as $key => $value) {
          if (!in_array($key, $frequencyOptions)) {
            unset($frequencyQFOptions[$key]);
          }
        }
        $frequency = $form->add('select', 'partial_payment_frequency', E::ts('How would you like to portion your payments?'), $frequencyQFOptions, FALSE, ['placeholder' => E::ts('- select -'), 'class' => 'crm-select2 big']);
        $form->assign('delayedFields', ['partial_payment', 'partial_payment_frequency']);
      }
      if ($formName === 'CRM_Contribute_Form_Contribution_Main' && count($frequencyOptions)) {
        $form->setDefaults(['partial_payment' => 0]);
      }
      elseif (count($frequencyOptions)) {
        $form->setDefaults([
          'partial_payment' => $form->_params['partial_payment'],
          'partial_payment_frequency' => $form->_params['partial_payment_frequency'],
        ]);
        $partialPaymentElement->freeze();
        $frequency->freeze();
      }
      $form->assign('checkPayment', 'NA');
      if (!empty($form->_paymentProcessors)) {
	foreach ($form->_paymentProcessors as $id => $processor) {
          if ($processor['payment_processor_type'] == 'Manual') {
            $form->assign('checkPayment', $id);
	  }
        }
      }
      
      $templatePath = realpath(dirname(__FILE__)."/templates");
      CRM_Core_Region::instance('form-body')->add(['template' => "{$templatePath}/CRM/Contribute/Form/DelayedFields.tpl"]);
    }
  }
}

/**
 * Implements hook_civicrm_validateForm().
 */
function delayedbilling_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  $frequencyOptions = Civi::settings()->get('delayedbilling_frequencies');
  $frequencyOptions = CRM_Core_DAO::unSerializeField($frequencyOptions, CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND);
  if ($formName === 'CRM_Contribute_Form_Contribution_Main' && count($frequencyOptions) > 1) {
    if (!empty($fields['partial_payment']) && empty($fields['partial_payment_frequency'])) {
      $errors['partial_payment_frequency'] = E::ts('You must specify a split for your payments');
    }
    if (empty($fields['partial_payment']) && !empty($fields['partial_payment_frequency'])) {
      $errors['partial_payment'] = E::ts('Please select this option if you intend to split payments');
    }
  }
}

/**
 * Implements hook_civicrm_pre().
 */
function delayedbilling_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName == 'Contribution' && $op == 'create') {
    // We need the recurring contribution created now since Moneris fetches the recurID for the contribution.
    if (!empty($_POST['partial_payment'])) {
      $frequency = $_POST['partial_payment_frequency'];
      $installments = 2;
      if ($frequency == 3) {
        $installments = 4;
      }
      elseif ($frequency == 1) {
        $installments = 12;
      }
      $nextDate = date('YmdHis', strtotime ("+$frequency month", time()));
      $recur = civicrm_api3('ContributionRecur', 'create', [
        'contact_id' => $params['contact_id'],
        'amount' => $params['total_amount'],
        'frequency_interval' => $frequency,
        'frequency_unit' => "month",
        'installments' => $installments,
        'next_sched_contribution_date' => $nextDate,
        'contribution_status_id' => "Pending",
      ]);
      if (!empty($recur['id'])) {
        $params['contribution_recur_id'] = $recur['id'];
        $params['contributionRecurID'] = $recur['id'];
      }
    }
  }
}

/**
 * Implements hook_civicrm_post().
 */
function delayedbilling_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  $failureNotification = Civi::settings()->get('delayedbilling_send_failure_noticiation');
  if ($objectName == 'Contribution' && $op == 'edit') {
    // Check to see if status == Failed.
    $failedStatus = CRM_Core_PseudoConstant::getKey('CRM_Contribute_BAO_Contribution', 'contribution_status_id', 'Failed');
    if ($objectRef->contribution_status_id == $failedStatus) {
      // Check if this was a delayed payment.
      if (_checkDelayedPayment($objectRef->contribution_page_id) && !empty($objectRef->contribution_recur_id) && $failureNotification) {
        // Send an email for failure of payment.
        try {
          civicrm_api3('Email', 'send', [
            'contact_id' => $objectRef->contact_id,
            'template_id' => FAILED_NOTIFICATION,
          ]);
        }
        catch (CiviCRM_API3_Exception $e) {
          // Log error message.
          CRM_Core_Error::debug_var('Error sending email for failed payment:', $e->getMessage());
        }
      }
    }
  }
}

/**
 * Implements hook_civicrm_postProcess().
 */
function delayedbilling_civicrm_postProcess($formName, $form) {
  if ($formName == 'CRM_Contribute_Form_ContributionPage_Amount' && !empty($form->_submitValues['is_delayed'])) {
    $contribForms = Civi::settings()->get('delayedbilling_active_contributionforms');
    $contribForms = CRM_Core_DAO::unSerializeField($contribForms, CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND);
    if (!in_array($form->getVar('_id'), $contribForms)) {
      $contribForms[] = $form->getVar('_id');
    }
    Civi::settings()->set('delayedbilling_active_contributionforms', $contribForms);
  }
  if ($formName === 'CRM_Contribute_Form_Contribution_Main') {
    // I'm not so sure about this, looks like the line items are the only thing that is being modified, but the total amount the not affected.
    if (!empty($form->_params['partial_payment'])) {
      $frequency = $form->_params['partial_payment_frequency'];
      $lineItems = $form->get('lineItem');
      $totalAmount = 0;
      $split = 2;
      if ($frequency == 3) {
        $split = 4;
      }
      elseif ($frequency == 1) {
        $split = 12;
      }
      foreach ($lineItems as $priceSetId => $priceFieldValues) {
        foreach ($priceFieldValues as $priceFieldValueId => $values) {
          $lineItems[$priceSetId][$priceFieldValueId]['qty'] = $values['qty'] / $split;
          $lineItems[$priceSetId][$priceFieldValueId]['line_total'] = $values['unit_price'] * $lineItems[$priceSetId][$priceFieldValueId]['qty'];
          $totalAmount = $totalAmount + $lineItems[$priceSetId][$priceFieldValueId]['line_total'];
        }
      }
      $form->set('lineItem', $lineItems);
      $form->_params['amount'] = $form->_params['separate_amount'] = $totalAmount;
      $form->set('amount', $totalAmount);
    }
  }
}

/**
 * Implements hook_civicrm_alterPaymentProcessorParams().
 */
function delayedbilling_civicrm_alterPaymentProcessorParams($paymentObj, &$rawParams, &$cookedParams) {
  if (!empty($rawParams['partial_payment'])) {
    if ($paymentObj instanceOf CRM_Core_Payment_Moneris || $paymentObj instanceOf CRM_Core_Payment_iATSService || $paymentObj instanceof CRM_Core_Payment_iATSServiceACHEFT) {
      // If we have made it this far but haven't set the ContributionRecurID value then lets set it here.
      if (empty($rawParams['ContributionRecurID']) && !empty($rawParams['contributionID'])) {
        $rawParams['ContributionRecurID'] = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_Contribution', $rawParams['contributionID'], 'contribution_recur_id');
      }
      // We set is_recur to be true here so that the token is created in Moneris and in CiviCRM for future payments.
      $installments = 2;
      if ($rawParams['partial_payment_frequency'] == 3) {
        $installments = 4;
      }
      elseif ($rawParmas['partial_payment_frequency'] == 1) {
        $installements = 12;
      }
      $rawParams['is_recur'] = 1;
      $rawParams['frequency_interval'] = $rawParams['partial_payment_frequency'];
      $rawParams['frequency_unit'] = 'month';
      $rawParams['installments'] = $installments;
    }
  }
}

function frequencyOptions() {
  return [
    6 => E::ts('In Half'),
    3 => E::ts('In Quarters'),
    1 => E::ts('Monthly'),
  ];
}

function _getFrequencyLabel($frequency) {
  switch ($frequency) {
    case 6:
      return 'Half Yearly';

    case 3:
      return 'Quarterly';

    case 1:
      return 'Monthly';

  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function delayedbilling_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function delayedbilling_civicrm_navigationMenu(&$menu) {
  _delayedbilling_civix_insert_navigation_menu($menu, 'Administer/CiviContribute', array(
    'label' => E::ts('Delayed Billing Settings'),
    'name' => 'delayed_billing_settings',
    'url' => 'civicrm/admin/setting/delayedbilling',
    'permission' => 'Administer CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _delayedbilling_civix_navigationMenu($menu);
}
