# rest_consumer

Drupal module that consumes &amp; displays posts from WordPress JSON endpoints

## The Easy Way

Created page and block to display WordPress posts. Cached to 15 mins max age.

Viewable at /recentposts

## The Views Way

Add View plugin for remote post data with appropriate fields

Add a View to display N most recent remote post 'entities'

Cache view to 15 mins max age.

Viewable at /viewrecentposts

Remaining to be done: 
1. Implement access check for both pages based on config checkbox
2. Confirm view cache settings
3. Verify module install/uninstall properly sets and removes view

## Other methods

Drupal Migrate + plugin to load posts from REST endpoint. This would require special attention to ensure that posts that no longer appear from the REST side are removed.
