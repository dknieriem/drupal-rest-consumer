<?php

/**
 * @file 
 * rest_consumer views hooks.
 */

/**
* Implement hook_views_data
*/
function rest_consumer_views_data() {
  $data = [];

  // Table definition:
  $data['rest_consumer']['table']['group'] = t('Recent Posts via REST endpoint');
  $data['rest_consumer']['table']['base'] = [
    'title' => t('Recent posts'),
    'help' => t('Recent posts provided by rest_consumer endpoint'),
    'query_id' => 'rest_consumer',
  ];
  
  // Field definitions:
  $data['rest_consumer']['id'] = [
    'title' => t('id'),
    'help' => t('Post ID'),
    'field' => [
      'id' => 'numeric',
    ],
  ];

  $data['rest_consumer']['title'] = [
    'title' => t('Title'),
    'help' => t('Post Title'),
    'field' => [
      'id' => 'standard',
    ],
  ];

  $data['rest_consumer']['date'] = [
    'title' => t('date'),
    'help' => t('Post Date'),
    'field' => [
      'id' => 'date',
    ],
  ];

  $data['rest_consumer']['url'] = [
    'title' => t('url'),
    'help' => t('Post URL'),
    'field' => [
      'id' => 'url',
    ],
  ];

  $data['rest_consumer']['body'] = [
    'title' => t('body'),
    'help' => t('Post Body'),
    'field' => [
      'id' => 'markup',
    ],
  ];

  return $data;
}