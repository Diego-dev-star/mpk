<?php
/**
 * Created by PhpStorm.
 * User: Виталий
 * Date: 14.04.2021
 * Time: 11:53
 */
add_action( 'admin_enqueue_scripts', 'add_js' );
function add_js(){
    wp_enqueue_script('rep', plugins_url('/mpk-fields/js/rep.js'), array('jquery') );
}

function mpk_setting_page()
{
    $args = array(
        "label" => __("MPK base adress", "MPK"),
        "labels" => array(
            "name" => __("MPK base adress", "MPK"),
            "singular_name" => __("MPK base adress", "MPK"),
        ),
        "public" => true,
        "publicly_queryable" => true,
        "show_ui" => true,
        "show_in_rest" => false,
        "has_archive" => false,
        "show_in_menu" => true,
        "exclude_from_search" => false,
        "capability_type" => "post",
        'menu_icon' => 'dashicons-database-add',
        "map_meta_cap" => true,
        "hierarchical" => false,
        "rewrite" => array("slug" => "mpk", "with_front" => true),
        "query_var" => true,
        "supports" => array("title"),
        "taxonomies" => array("mpk"),
    );
    register_post_type("mpk", $args);
}

add_action('init', 'mpk_setting_page');



function my_extra_fields() {
    add_meta_box( 'extra_fields', __('Do new item','local'), 'extra_fields_box_func', 'mpk', 'normal', 'high'  );
}
add_action('add_meta_boxes', 'my_extra_fields', 1);

function extra_fields_box_func( $post )
{

        ?>
        <h4><?php _e('MPK dates') ?></h4>
        <p><label><input type="text" name="extra[kod]" placeholder="<?php _e('Write code') ?>"
                         value="<?php echo get_post_meta($post->ID, 'kod', 1); ?>"
                         style="width:25%"/> </label>
            <label><input type="text" name="extra[name]" placeholder="<?php _e('Write name') ?>"
                          value="<?php echo get_post_meta($post->ID, 'name', 1); ?>"
                          style="width:25%"/></label>
            <label><input type="text" name="extra[addres]" placeholder="<?php _e('Write addres') ?>"
                          value="<?php echo get_post_meta($post->ID, 'addres', 1); ?>"
                          style="width:45%"/></label>
        </p>

        <input type="hidden" name="extra_fields_nonce" value="<?php echo wp_create_nonce(__FILE__); ?>"/>
        <?php
    }

    add_action('save_post', 'my_extra_fields_update', 0);

## Сохраняем данные, при сохранении поста
    function my_extra_fields_update($post_id)
    {
        // базовая проверка
        if (
            empty($_POST['extra'])
            || !wp_verify_nonce($_POST['extra_fields_nonce'], __FILE__)
            || wp_is_post_autosave($post_id)
            || wp_is_post_revision($post_id)
        )
            return false;

        // Все ОК! Теперь, нужно сохранить/удалить данные
        $_POST['extra'] = array_map('sanitize_text_field', $_POST['extra']); // чистим все данные от пробелов по краям
        foreach ($_POST['extra'] as $key => $value) {
            if (empty($value)) {
                delete_post_meta($post_id, $key); // удаляем поле если значение пустое
                continue;
            }

            update_post_meta($post_id, $key, $value); // add_post_meta() работает автоматически
        }

        return $post_id;
    }




