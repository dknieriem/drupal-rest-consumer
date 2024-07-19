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
      
      if ($response) {
        $data_array = Json::decode($response);
        if (array_key_exists('posts', $data_array)) {
          return $data_array['posts'];
        }
      } 
      return NULL;
      
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
   * Load posts and display on admin page.
   */
  public function admin_page() {
    $content = [];

    $posts = $this->load();

    if ($posts) {
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
    
    return $content;
  }

  /**
   * Load posts and display on a public page.
   */
  public function rest_page() {
    $content = [ //      
      '#theme' => 'rest_consumer_page',
      '#cache' => [
        'max-age' => 900,
      ],
    ];

    $posts = $this->load();
    $renderable_posts = [];
    if($posts){
      foreach ($posts as $post) {
        $renderable_posts[] = [ //
          '#theme' => 'rest_consumer_post',
          '#id' => $post['ID'],
          '#date' => $post['post_date'],
          '#title' => $post['post_title'],
          '#url' => $post['guid'],
        ];
      }
    }
    
  
    $content['#posts'] = $renderable_posts;
    return $content;
  }

  /**
   * Load posts and display on a block.
   */
  public function rest_block() {
    $content = [];

    $posts = $this->load();

    if ($posts) {
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

    return $content;
  }
}