# rest_consumer

Drupal module that consumes &amp; displays posts from WordPress JSON endpoints

## The Easy Way

Created page and block to display WordPress posts. Cached to 15 mins max age.

## The Views Way

Add View plugin for remote post data with appropriate fields

Add a View to display N most recent remote post 'entities'

Cache view to 15 mins max age.

## Other methods

Drupal Migrate + plugin to load posts from REST endpoint. This would require special attention to ensure that posts that no longer appear from the REST side are removed.