<?php

/**
 * @file
 * Contains the settings for administering the REST Consumer
 */

namespace Drupal\rest_consumer\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class RestSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'rest_consumer_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'rest_consumer.settings'
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $types = node_type_get_names();
    $config = $this->config('rest_consumer.settings');
    $form['rest_endpoint'] = [
      '#type' => 'url',
      '#title' => $this->t('The URL of the REST endpoint to load posts from'),
      '#default_value' => $config->get('rest_endpoint'),
      '#description' => $this->t('Posts will be loaded via the REST endpoint at this URL. An invalid or inaccessible URL will result in errors in your dblog. Check /admin/'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $new_endpoint = array_filter($form_state->getValue('rest_endpoint'));

    $this->config('rest_consumer.settings')
      ->set('rest_endpoint', $new_endpoint)
      ->save();

    parent::submitForm($form, $form_state);
  }
}