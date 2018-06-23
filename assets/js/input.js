(function ($) {

    function showSelectedContent($field, $typeSelect) {
        let currentType = $typeSelect.val().split('/').map(function ($name) {
            return '[data-zelda-type="' + $name + '"]';
        }).join(' > ');
        // Hide everything...
        $field.find('.acf-field-zelda__fieldWrap').attr('hidden', true);
        // ...Then show the selected thing.
        $field.find(currentType).removeAttr('hidden');
    }

    /**
     *  initialize_field
     *
     *  This function will initialize the $field.
     *
     *  @date    30/11/17
     *  @since    5.6.5
     *
     *  @param    $field
     *  @return    n/a
     */

    function initialize_field($field) {

        const typeSelect = $field.find('.acf-field-zelda__typeSelect > select');

        typeSelect.change(function () {
            showSelectedContent($field, $(this));
        });

        // Also fire on load
        showSelectedContent($field, typeSelect);

    }


    if (typeof acf.add_action !== 'undefined') {

        /*
        *  ready & append (ACF5)
        *
        *  These two events are called when a field element is ready for initizliation.
        *  - ready: on page load similar to $(document).ready()
        *  - append: on new DOM elements appended via repeater field or other AJAX calls
        *
        *  @param	n/a
        *  @return	n/a
        */

        acf.add_action('ready_field/type=zelda', initialize_field);
        acf.add_action('append_field/type=zelda', initialize_field);


    }

})(jQuery);
