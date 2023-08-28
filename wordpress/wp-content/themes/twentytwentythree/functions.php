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

function get_all_categories($data)
{
  $posts = get_posts([
    'post_type' => 'category'
  ]);

  foreach ($posts as $post) {
    $fields = get_fields($post);
    $post->acf = $fields;
  }

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

  register_rest_route(
    'wp/v2',
    '/allcategories',
    array(
      'methods' => 'GET',
      'callback' => 'get_all_categories',
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

function register_category_post_type()
{
  $labels = array(
    'name'               =>  'Categories', 'post type general name',
    'singular_name'      =>  'Category', 'post type singular name',
    'menu_name'          =>  'Categories', 'admin menu',
    'name_admin_bar'     =>  'Category', 'add new on admin bar',
    'add_new'            =>  'Add New', 'Category',
    'add_new_item'       => 'Add New Category',
    'new_item'           => 'New Category',
    'edit_item'          => 'Edit Category',
    'view_item'          => 'View Category',
    'all_items'          => 'All Categories',
    'search_items'       => 'Search Categories',
    'parent_item_colon'  => 'Parent Categories:',
    'not_found'          => 'No Categories found.',
    'not_found_in_trash' => 'No Categories found in Trash.'
  );

  $args = array(
    'labels'             => $labels,
    'description'        => 'Description.',
    'public'             => true,
    'publicly_queryable' => true,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'query_var'          => true,
    'rewrite'            => array('slug' => 'Category'),
    'capability_type'    => 'post',
    'has_archive'        => true,
    'hierarchical'       => false,
    'menu_position'      => null,
    'menu_icon'       => 'dashicons-category',
    'supports'           => array('title', 'author'),
    'show_in_rest'      => true,
    //'rest_base' 		 => 'Categories',
  );

  register_post_type('category', $args);
}

register_category_post_type();

function scHello($atts)
{
  $a = shortcode_atts(array(
    'name' => 'Netpower', // default value
  ), $atts);

  return '<h1 style="color: #fa0">Hello ' . $a['name'] . '! I am a shortcode</h1>';
}

function scDoShortcode($atts)
{
  $a = shortcode_atts(array(
    'name' => 'Newton', // default value
  ), $atts);

  return do_shortcode('[hello name="' . $a['name'] . '"]');
}
add_shortcode('hello', 'scHello');
add_shortcode('doSC', 'scDoShortcode');


function sayGoodbye()
{
  die('Bye! I am dead!');
}
add_action('sayGoodbye', 'sayGoodbye');

do_action('sayGoodbye');
