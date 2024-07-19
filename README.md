# rest_consumer

Drupal module that consumes &amp; displays posts from WordPress JSON endpoints

## The Easy Way

Created page and block to display WordPress posts. Cached to 15 mins max age.

## The Views Way

Add EntityType for RemotePost with appropriate fields

Add a View to display N most recent RemotePost entities

Cache view to 15 mins max age.

Hook view view, so when cache is missed,

  1. Delete all RemotePost entities
  2. Hit the rest endpoint
  3. Generate a RemotePost for each post returned

## Other methods

Drupal Migrate + plugin to load posts from REST endpoint. This would require special attention to ensure that posts that no longer appear from the REST side are removed.