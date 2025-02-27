jQuery(document).ready(function ($) {
  $('.switch_options').each(function () {
    //This object
    var obj = $(this);

    var enb = obj.children('.switch_enable'); //cache first element, this is equal to ON
    var dsb = obj.children('.switch_disable'); //cache first element, this is equal to OFF
    var input = obj.children('input'); //cache the element where we must set the value
    var input_val = obj.children('input').val(); //cache the element where we must set the value

    /* Check selected */
    if (0 == input_val) {
      dsb.addClass('selected');
    } else if (1 == input_val) {
      enb.addClass('selected');
    }

    //Action on user's click(ON)
    enb.on('click', function () {
      $(dsb).removeClass('selected'); //remove "selected" from other elements in this object class(OFF)
      $(this).addClass('selected'); //add "selected" to the element which was just clicked in this object class(ON)
      $(input).val(1).change(); //Finally change the value to 1
    });

    //Action on user's click(OFF)
    dsb.on('click', function () {
      $(enb).removeClass('selected'); //remove "selected" from other elements in this object class(ON)
      $(this).addClass('selected'); //add "selected" to the element which was just clicked in this object class(OFF)
      $(input).val(0).change(); // //Finally change the value to 0
    });
  });
});

// enableTinyMCE with Lists only

jQuery(document).ready(function ($) {
  function enableTinyMCE() {
    if (typeof tinymce !== 'undefined') {
      tinymce.init({
        selector:
          '#customize-control-boo_purchase_flow_field_portfolio_desc_list textarea, ' +
          '#customize-control-boo_purchase_flow_field_variable_desc_list textarea, ' +
          '#customize-control-boo_purchase_flow_field_fixed_desc_list textarea, ' +
          '#customize-control-boo_purchase_flow_field_portfolio_desc_list_business textarea, ' +
          '#customize-control-boo_purchase_flow_field_variable_desc_list_business textarea, ' +
          '#customize-control-boo_purchase_flow_field_fixed_desc_list_business textarea',

        menubar: false,
        toolbar: 'bullist', // Only allow bullet lists
        forced_root_block: 'li', // Force <ul> as the root block
        valid_elements: 'li', // Only allow <ul> and <li>
        setup: function (editor) {
          editor.on('change', function () {
            editor.save();
            $(editor.targetElm).trigger('change');
          });
        }
      });
    }
  }

  enableTinyMCE();

  // Reinitialize when clicking anywhere in the customizer
  $(document).on('click', function () {
    enableTinyMCE();
  });
});
