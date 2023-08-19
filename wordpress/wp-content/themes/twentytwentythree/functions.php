<?php
function get_all_posts($data)
{
  $posts = get_posts();

  if (empty($posts)) {
    return null;
  }

  return $posts;
}

add_action('rest_api_init', function () {
  register_rest_route(
    'wp/v2',
    '/allposts',
    array(
      'methods' => 'GET',
      'callback' => 'get_all_posts',
    )
  );
});
