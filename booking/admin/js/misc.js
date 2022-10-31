var hs = hs || {};

hs.manageProducts = function(el) {
    var obj = this;
    obj.el = el;
    obj.inputForm = obj.el.parents('.js-form');
    obj.visibleProducts = $(obj.inputForm).data('products');

    obj.init = function() {

        obj.el.on('click', function(e){

            e.preventDefault();

            ++obj.visibleProducts;

            obj.inputForm.children('.is-hidden').first().find('input').prop("disabled", false);

            obj.inputForm.children('.is-hidden').first().removeClass('is-hidden');

            if(obj.visibleProducts == 10) {
                obj.el.hide();
            }


        });

    };

    obj.init();
}

jQuery(document).ready(function($) {

    // if($('.js-newProduct').length) {
    //     hs.manageProducts($('.js-newProduct'));
    // }

    $('[data-toggle="datepicker"]').datepicker({
        format: 'dd.mm.yyyy',
        weekStart: 1
    });

});