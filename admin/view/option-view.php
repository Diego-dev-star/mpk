<?php

function get_view_option($user)
{

    ?>
    <span class="item-address">
<select name="mpk[]" id="mpk" class="item-address">

    <option value="<?php echo $addr[$key]; ?>" selected="selected"><?php echo $addr[$key]; ?></option>
<?php
$arr = array(
    'post_type' => 'mpk', // тип постов - записи
    'numberposts' => -1
);
$datas = get_posts($arr);

if (is_array($datas)):
    foreach (array_filter($datas) as $key => $item): setup_postdata($item);
        $kod = get_post_meta($item->ID, 'kod');
        $name = get_post_meta($item->ID, 'name');
        $addres = get_post_meta($item->ID, 'addres');
        $elements = 'MPK ' . $kod[0] . ' ( ' . $name[0] . ' ) ' . $addres[0];
        ?>
        <option value="<?php echo $elements; ?>">
            <?php echo $elements;?>
        </option>
        <?php
        wp_reset_postdata();
    endforeach;

?>

<?php endif;?>
 </select>
        <span class="dashicons dashicons-trash remove-company-address"></span>
    </span>
<?php }




