<?php

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
 * Implements hook_civicrm_thems().
 */
function delayedbilling_civicrm_themes(&$themes) {
  _delayedbilling_civix_civicrm_themes($themes);
}

function delayedbilling_civicrm_buildForm($formName, &$form) {
  if ($formName === 'CRM_Contribute_Form_Contribution_Main' || $formName === 'CRM_Contribute_Form_Contribution_Confirm') {
    $formId = $form->getVar('id');
    if (in_array($formId, Civi::settings()->get('delayedbilling_active_contributionforms'))) {
      $form->addElement('advcheckbox', 'partial_payment', E::ts('Do you want to split your payments over the course of the year?'));
      $form->add('select2', 'partial_payment_frequency', E::ts('How would you like to portion your payments?'), [2 => E::ts('In half'), 4 => E::ts('In Quarters')], FALSE, ['placeholder' => E::ts('- select -'), 'class' => 'big crm-select2']),
      $form->setDefaults(['partial_payment' => 0]);
    }
  }
}

function delayedbilling_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName === 'CRM_Contribute_Form_Contribution_Main' && !empty($fields['partial_payment']) && empty($fields['partial_payment_frequency'])) {
    $errors['partial_payment_frequency'] = E::ts('You must specify a split for your payments');
  }
}

function delayedbilling_civicrm_postProcess($formName, $form) {
  if ($formName === 'CRM_Contribute_Form_Contribution_Main') {
    \Civi::log()->debug('Form Params', ['params' => $form->_params]);
  }
}

function delayedbilling_civicrm_alterPaymentProcessorParams($paymentObj,&$rawParams, &$cookedParams) {
  if ($paymentObj instanceOf CRM_Core_Payment_Moneris && !empty($rawParams['partial_payment'])) {
    // We set is_recur to be true here so that the token is created in Moneris and in CiviCRM for future payments.
    $rawParams['is_recur'] = 1;
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
//function delayedbilling_civicrm_navigationMenu(&$menu) {
//  _delayedbilling_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _delayedbilling_civix_navigationMenu($menu);
//}
