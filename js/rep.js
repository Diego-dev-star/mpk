jQuery(document).ready(function ($) {

    var $companyInfo = $('.mpk-info');

    // Добавляет бокс с вводом адреса фирмы
    $('.add-company-address', $companyInfo).click(function () {
        var $list = $('.company-address-list');
        $item = $list.find('.item-address').first().clone();

        $item.find('input').val(''); // чистим знанчение

        $list.append($item);
    });

    // Удаляет бокс
    $companyInfo.on('click', '.remove-company-address', function () {
        if ($('.item-address').length > 1) {
            $(this).closest('.item-address').remove();
        }
        else {
            $(this).closest('.item-address').find('input').val('');
        }
    });





});