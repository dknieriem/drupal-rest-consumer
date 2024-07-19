<?php

/**
 * @file 
 * Provide Views query type for rest consumer content.
 */


namespace Drupal\rest_consumer\Plugin\views\query;

use Drupal\Component\Serialization\Json;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\ResultRow;

/**
 * REST endpoint views query plugin enabling a view to pull data based on endpoint JSON
 *
 * @ViewsQuery(
 *   id = "rest_consumer",
 *   title = @Translation("REST Consumer"),
 *   help = @Translation("Query the rest_consumer configured REST endpoint url.")
 * )
 */
class RestConsumer extends QueryPluginBase {

  /**
   * {@inheritDoc}
   */
  public function ensureTable($table, $relationship = NULL) {
    return '';
  }

  /**
   * {@inheritDoc}
   */
  public function addField($table, $field, $alias = '', $params = []) {
    return $field;
  }

  /**
   * {@inheritDoc}
   */
  public function execute(ViewExecutable $view) {
    $rest_config = \Drupal::config('rest_consumer.settings');
    $endpoint_url = $rest_config->get('rest_endpoint');
    try {
      $request = \Drupal::httpClient()->get($endpoint_url, ['verify' => FALSE]);
      $response = $request->getBody()->getContents();

      if ($response) {
        $data_array = Json::decode($response);
        if (array_key_exists('posts', $data_array)) {
          $index = 0;
          foreach ( $data_array['posts'] as $post ){
            $row['id'] = $post['ID'];
            $row['title'] = $post['post_title'];
            $row['date'] = \DateTime::createFromFormat("Y-m-d H:i:s" , $post['post_date'])->getTimestamp();
            $row['url'] = $post['guid'];
            $row['body'] = $post['post_content'];
            $row['index'] = $index++;
            $view->result[] = new ResultRow($row);
          }
        }
      } 
      return;
      
    } catch(\Exception $e) {
      \Drupal::messenger()->addStatus(
        t('Unable to query the REST endpoint. Please check the URL is valid and try again.')
      );
      $logger = \Drupal::logger('rest_consumer');
      \Drupal\Core\Utility\Error::logException($logger, $e); 

      return;
    }
  }
}