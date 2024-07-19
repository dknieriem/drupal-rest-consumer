<?php

/**
 * @file
 * Creates a block which displays posts from the rest_consumer
 */

 namespace Drupal\rest_consumer\Plugin\Block;

 use Drupal\Core\Block\BlockBase;
 use Drupal\Core\Block\Attribute\Block;
 use Drupal\Core\Session\AccountInterface;
 use Drupal\Core\Access\AccessResult;
 use Drupal\Core\StringTranslation\TranslatableMarkup;
 use Drupal\rest_consumer\Controller\RestController;
 /**
  * Provides the RSVP main block.
  */
  #[Block(
    id: "rest_consumer_block",
    admin_label: new TranslatableMarkup("Recent Posts from rest_consumer")
  )]
 class RestBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(){  
    $controller = new RestController;

    return $controller->block();
  }

  /**
   * {@inheritdoc}
   */
  public function blockAccess(AccountInterface $account) {

    $rest_config = \Drupal::config('rest_consumer.settings');
    $block_enabled = $rest_config->get('rest_block_enabled');

    if ($block_enabled) {
      return AccessResult::allowed();
    }

    return AccessResult::forbidden();
  }
 }