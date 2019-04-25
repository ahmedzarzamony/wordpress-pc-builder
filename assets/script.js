jQuery(function(){
    jQuery('.pcbuilder_gen .button').click(function(){
        var name = jQuery(this).attr('data-name');
        var count = jQuery(this).closest('.pcbuilder_gen').find('.pcg_item').length-1;
        var form = jQuery(this).closest('.pcbuilder_gen').find('.pcbuilder_store').html();
        form = $('<div />',{html:form});
        if(jQuery(this).closest('.pcbuilder_gen').hasClass('pcbuilder_pgen')){
            jQuery(form).find('input').attr('name', name+'['+count+'][price]');
            jQuery(form).find('select').attr('name', name+'['+count+'][country]');
        }else{
            jQuery(form).find('input').attr('name', name+'['+count+'][title]');
            jQuery(form).find('textarea').attr('name', name+'['+count+'][content]');
        }
        jQuery(this).closest('.pcbuilder_gen').find('.pcbuilder_genco').append(form)
    })

    jQuery(document).on('click', '.pcg_item > i', function(){
        jQuery(this).closest('.pcg_item').remove();
    });
    
})