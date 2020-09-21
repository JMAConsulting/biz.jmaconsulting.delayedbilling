<?php

use CRM_Delayedbilling_ExtensionUtil as E;

/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */
return array(
  'delayedbilling_send_failure_noticiation' => [
    'group_name' => 'Delayed Billing Settings',
    'group' => 'delayedbilling',
    'name' => 'delayedbilling_send_failure_noticiation',
    'type' => 'Boolean',
    'title' => E::ts('Send Notiifcation on failed recurring payment'),
    'default' => 1,
    'add' => '5.20',
    'html_type' => 'checkbox',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('Send a notification to office staff when recurring contribution fails'),
    'settings_pages' => ['delayedbilling' => ['weight' => 10]],
  ],
  'delayedbilling_frequencies' => [
    'group_name' => 'Delayed Billing Settings',
    'group' => 'delayedbilling',
    'name' => 'delayedbilling_frequencies',
    'type' => 'String',
    'title' => E::ts('What permitted Frequencies are allowed'),
    'default' => ['6', '3'],
    'add' => '5.20',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => 'What potnetial frequencies will we allow end users to split their payments by',
    'html_type' => 'checkboxes',
    'pseudoconstant' => ['callback' => 'frequencyOptions'],
    'serialize' => CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND,
    'settings_pages' => ['delayedbilling' => ['weight' => 20]],
  ],
  'delayedbilling_active_contributionforms' => [
    'group_name' => 'Delayed Billing Settings',
    'group' => 'delayedbilling',
    'name' => 'delayedbilling_active_contributionforms',
    'type' => 'String',
    'title' => E::ts('What Contribution pages allow for delayed billing'),
    'default' => '',
    'add' => '5.20',
    'is_domain' => 1,
    'is_contact' => 0,
    'description' => E::ts('What contribution pages allow for payments to be delayed'),
    'html_type' => 'checkboxes',
    'pseudoconstant' => [
      'table' => 'civicrm_contribution_page',
      'keyColumn' => 'id',
      'labelColumn' => 'title',
    ],
    'serialize' => CRM_Core_DAO::SERIALIZE_SEPARATOR_BOOKEND,
    'settings_pages' => ['delayedbilling' => ['weight' => 20]],
  ],
);