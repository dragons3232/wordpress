<?php
function get_all_posts($data)
{
  $posts = get_posts();

  if (empty($posts)) {
    return null;
  }

  return $posts;
}

function handleUpload(\WP_REST_Request $req)
{
  $id = $req->get_params()['userID'];
  $img = $req->get_file_params()['img'];

  $url = "/uploads/" . $id . "_" . $img['name'];
  $fileLoc = WP_CONTENT_DIR . $url;

  if (copy($img['tmp_name'], $fileLoc)) {
    return [
      "ok" => true,
      "url" => $url
    ];
  } else {
    return [
      "ok" => false,
      "msg" => "File Copy Error:" . error_get_last()
    ];
  }
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

  register_rest_route(
    'wp/v2',
    '/upload',
    array(
      'methods' => 'Post',
      'callback' => 'handleUpload',
      'args' => array(
        'userID' => array(
          'required' => true,
          'type' => 'number',
          'description' => 'User Id',
        ),
        'img' => array(
          'type' => 'file',
          'description' => 'Image file',
        ),
      ),
    )
  );
});
