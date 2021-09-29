<?php
/**
 * Created by PhpStorm.
 * User: Виталий
 * Date: 19.04.2021
 * Time: 12:08
 */
require plugin_dir_path(__FILE__) . 'option-view.php';

?>


<h3><?php _e('MPK fields', 'your_domain'); ?></h3>
<table class="form-table mpk-info">

    <tr>
        <th>
            <label for="mpk"><?php _e('Enter MPK', 'local'); ?>
            </label> <span class="dashicons dashicons-plus-alt add-company-address"></span>
        </th>
        <td class="company-address-list">
            <?php
            $addresses = get_user_meta($user->ID, $metakey, true);
            if (is_array($addresses)) {
                foreach (array_filter($addresses) as $addr) { ?>
                    <span class="item-address">
                    <select name="mpk[]" id="mpk" class="item-address">

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
                                <?php if ($addr == $elements): ?>
                                    <option value="<?php echo $addr; ?>" selected="selected">
                                    <?php echo $addr; ?>
                                </option>
                                <?php else: ?>
                                    <option value="<?php echo $elements; ?>">
                                    <?php echo $elements; ?>
                                </option>
                                    <?php
                                endif;
                                wp_reset_postdata();
                            endforeach;

                            ?>

                        <?php endif; ?>
                    </select>
        <span class="dashicons dashicons-trash remove-company-address"></span>
                    </span><?php
                }
            } else {
                printf(get_view_option($user), '');
            }
            ?>
            <?print_r($addresses)?>
        </td>
    </tr>
</table>
