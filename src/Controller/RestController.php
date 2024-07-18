<?php

/**
 * @file
 * Provide site administrators with a list of posts from the configured endpoint
 * to allow for debugging.
 */

namespace Drupal\rest_consumer\Controller;

use Drupal\Component\Serialization\Json;
use Drupal\Core\Controller\ControllerBase;

class RestController extends ControllerBase {

  /**
   * Load posts via the REST endpoint configured.
   */
  protected function load() {
    // Load endpoint url from config
    $rest_config = \Drupal::config('rest_consumer.settings');
    $endpoint_url = $rest_config->get('rest_endpoint');
    //\Drupal::messenger()->addStatus($endpoint_url);
    try {
      $request = \Drupal::httpClient()->get($endpoint_url, ['verify' => FALSE]);
      // $request = \Drupal::httpClient()->get('http://httpbin.org/get');
      // var_dump($request->getStatusCode());
      $response = $request->getBody()->getContents();
      // var_dump($response);
      
      return $response; //->getContents();
    } catch(\Exception $e) {
      \Drupal::messenger()->addStatus(
        t('Unable to query the REST endpoint. Please check the URL is valid and try again.')
      );
      $logger = \Drupal::logger('rest_consumer');
      \Drupal\Core\Utility\Error::logException($logger, $e); 

      return NULL;
    }
    
  }

  /**
   * Load posts and display on a page.
   */
  public function page() {
    $content = [];

    $rest_config = \Drupal::config('rest_consumer.settings');
    $endpoint_url = $rest_config->get('rest_endpoint');

    // $content['header'] = [
    //   '#markup' => t('REST Endpoint Admin Page')
    // ];

    $content['endpoint info'] = [
      '#markup' => t('<p>Endpoint: @endpoint</p>', ['@endpoint' => $endpoint_url])
    ];

    $data = $this->load();
    //\Drupal::messenger()->addStatus($data);

    if ($data) {
      $data_array = Json::decode($data);
      // var_dump($data_array);
      if (array_key_exists('posts', $data_array)) {
        $posts = $data_array['posts'];

        $content['posts header'] = [
          '#markup' => t('<p>Number of posts returned: @posts</p>', ['@posts' => sizeof($posts)])
        ];
        $column_headers = [
          t('ID'),
          t('Title'),
          t('Post Date'),
        ];

        $rows = [];

        foreach ($posts as $post) {
          $rows[] = [
            'ID:' => $post['ID'],
            'Title' => $post['post_title'],
            'Post Date' => $post['post_date'],
          ];
      }

        $content['post table'] = [
          '#type' => 'table',
          '#header' => $column_headers,
          '#rows' => $rows,
          '#empty' => t('No posts found'),
        ];

      }
    }
    

    return $content;
  }

}