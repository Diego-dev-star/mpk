<?php
/*
Plugin Name: mpk-fields
Description:  Turn  on  mpk fields
Author: PAGEART TM
Version: 0.4.0

*/
require plugin_dir_path(__FILE__) . '/admin/adm-func.php';

function mpk_field($user)
{
    $metakey = 'mpk';
    require plugin_dir_path(__FILE__) . '/admin/view/user-view.php';


}

add_action('show_user_profile', 'mpk_field');
add_action('edit_user_profile', 'mpk_field');
function save_mpk_fields($user_id)
{
    $metakey = 'mpk';
    if (isset($_POST[$metakey]) && is_array($_POST[$metakey])) {
        $addresses = $_POST[$metakey];
        $addresses = array_map('sanitize_text_field', $addresses); // очистка
        $addresses = array_filter($addresses); // уберем пустые адреса
        update_user_meta($user_id, $metakey, $addresses);
    }

}


add_action('show_user_profile', 'save_mpk_fields');
add_action('edit_user_profile', 'save_mpk_fields');
add_action('personal_options_update', 'save_mpk_fields');
add_action('edit_user_profile_update', 'save_mpk_fields');
//mpk list strat

function mpk_list($user)
{
    global $user_id;

    $meta_key = 'mpk';
    ?>
    <?php $list = get_user_meta($user_id, $meta_key, true); ?>
    <?php if ($list != null): ?>
    <h3><?php _e('MPK list tego użytkownika ', 'local'); ?></h3>
    <table width="100%">
    <thead>
    <tr class="table mpk">
        <th scope="col">#</th>
        <th scope="col" style="text-align: left;"><?php _e('Address', 'local') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($list as $key => $item): ?>
        <tr>
            <th scope="row"><?php echo $key; ?></th>
            <td><?php echo $item ?>
            </td>
        </tr>
    <?php endforeach; ?>
<?php endif; ?>
    </tbody>
    </table>

    <style>
        .table.mpk {
            color: #fff;
            background: #6691b9;
        }
    </style>
    <?php
}


add_action('show_user_profile', 'mpk_list');
add_action('edit_user_profile', 'mpk_list');

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

function custom_override_checkout_fields($fields)
{
    //unset($fields['billing']['billing_first_name']);// first name
    //unset($fields['billing']['billing_last_name']);// last name
    unset($fields['billing']['billing_company']); // company
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    //unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    //unset($fields['billing']['billing_phone']);
    //unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_email']);
    unset($fields['account']['account_username']);
    unset($fields['account']['account_password']);
    unset($fields['account']['account_password-2']);
    return $fields;
}

add_filter('woocommerce_default_address_fields', 'filter_default_address_fields', 20, 1);
function filter_default_address_fields($address_fields)
{
    // Only on checkout page
    if (!is_checkout()) return $address_fields;
    $key_fields = array('company', 'address_2', 'city', 'state', 'postcode');
    foreach ($key_fields as $key_field)
        $address_fields[$key_field]['required'] = false;
    return $address_fields;
}

add_action('woocommerce_checkout_update_order_meta', 'shipping_apartment_update_order_meta');
//customize address field

add_action('woocommerce_after_checkout_billing_form', 'wpbl_select_field');

// Сохраняем поля
//add_action('woocommerce_checkout_update_order_meta', 'wpbl_save_fields');
add_action('woocommerce_checkout_update_order_meta', 'wpbl_save_fields');
function wpbl_select_field($checkout)
{
    $meta_key = 'mpk';
    $user = get_current_user_id();
    $mpk = get_user_meta($user, $meta_key, true);
    $mpk_arr = [ str_replace(array('&nbsp', ''), '  ', $mpk)];
    $filtered = [];
    foreach ($mpk_arr as $key => $value):
        $filtered[$value] .= $value;
    endforeach;
    woocommerce_form_field('mpk', array(
        'type' => 'select',
        'required' => true,
        'class' => array('wpbl-field', 'form-row-wide'),
        'label' => __('Źródło finansowania'),
        'label_class' => '',
        'options' => $filtered
        // array loking must bee array( $mpk => $mpk )
    ),
        $checkout->get_value('mpk')


    );

    echo '
<p>' . __('Wybierz właściwe MPK lub Zlecenie', 'local') . '</p>
<style>
#mpk{padding:15px; background:#f1f1f1; border:none; }


.repeat.repeater-add-btn{
    color: #ffffff;
    background-color: #2196f3;
    border-color: transparent;
    padding: 5px 25px;
    cursor: pointer;}

</style>';


}


add_action('woocommerce_checkout_process', 'mpk_field_validation');

function mpk_field_validation()
{
    if (empty($_POST['mpk'])) {
        wc_add_notice(__('mpk field is  emperty'), 'error');
    }


}

function wpbl_save_fields($order_id)
{
    $meta_key = 'mpk';
    // Сохраняем select
    if (!empty($_POST['mpk'])) {
        update_post_meta($order_id, $meta_key, sanitize_text_field($_POST['mpk']));
    }


}

