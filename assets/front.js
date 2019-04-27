jQuery(function(){

    function pcbuilder_calcprice(){
        var sum = val = 0;
        jQuery('.pcbuilder-price-clac').each(function(){
            val = this.value.replace('USD', '')
            val = isNaN(val) ? 0 : val; 
            sum += parseFloat(val);
        });
        jQuery('.pcbuilder-end-price').text(sum + 'USD');
    };
    
    jQuery('.pcbuilder-cancel, .pcbuilder-table-add-btn').click(function(e){
        e.preventDefault();
        jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-table-form').fadeToggle(300);
        pcbuilder_calcprice();
    });

    jQuery('.pc-builder-budget input[type=range]').on('input', function(e){
        var val = jQuery(this).val()
        if(Number(val) >= 1000){
            jQuery(this).closest('.pcbuilder-table-container').find('select.pcbuilder-extra').attr('disabled', false)
        }else{
            jQuery(this).closest('.pcbuilder-table-container').find('select.pcbuilder-extra').attr('disabled', true)
        }    
    });
    
    jQuery('.pcbuilder-budget-cancel, .pcbuilder-budget-add-btn').click(function(e){
        e.preventDefault();
        jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-budget-form').fadeToggle(300);
        pcbuilder_calcprice();
    });
    jQuery('.pcbuilder-budget-addrow').click(function(e){
        e.preventDefault();
        var item = {};
        item.purpose = jQuery(this).closest('.pcbuilder-budget-form').find('.pcbuilder-purpose').val();
        item.cpu = jQuery(this).closest('.pcbuilder-budget-form').find('.pcbuilder-cpu').val();
        item.gpu = jQuery(this).closest('.pcbuilder-budget-form').find('.pcbuilder-gpu').val();
        item.price = jQuery(this).closest('.pcbuilder-budget-form').find('input[type=range]').val();
        item.extra = jQuery(this).closest('.pcbuilder-budget-form').find('.pcbuilder-extra').val();
        var data = {
            'action': 'call_budget_products',
            'data': item
        };
        var that = this;
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajax_object.ajax_url, data, function(response) {
            var markup = '';   
            if(response != 0){
                var data = JSON.parse(response);
                data.forEach(function(item){
                    console.table(item)
                    markup += '<div class="pcbuilder-table-row"><div class="pcbuilder-table-item pcbuilder-3"><i>&times;</i>'+item.component+'</div><div class="pcbuilder-table-item pcbuilder-6"><span>'+item.brand+'</span><a href="'+item.url+'" target="_blank">'+item.name+'</a></div><div class="pcbuilder-table-item pcbuilder-3"><input type="text" readonly class="pcbuilder-price-clac" value="'+parseFloat(item.price)+'USD"></div><!-- pcbuilder-table-item --></div>';
                });     
            }else{
                markup = '<p class="pc-builder-nodata">Sorry, No Data.</p>';
            }
            jQuery(that).closest('.pcbuilder-table-container').find('.pcbuilder-table-body').append(markup);
            jQuery(that).closest('.pcbuilder-table-container').find('.pcbuilder-budget-form').fadeToggle(300);
            pcbuilder_calcprice();
        });
    });

    jQuery('.pcbuilder-addrow').click(function(e){
        e.preventDefault();
        var item = {};
        item.comp = jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-component').val();
        item.product = jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-product').val();
        item.pbrand = jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-product option:selected').attr('data-brand');
        item.pprice = jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-product option:selected').attr('data-price');
        item.purl = jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-product option:selected').attr('data-url');
        if(item.product == null || item.product == '' || item.product == 'undefined'){
            jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-product').attr('style', 'border-color:#8c0b0b')
            return false;
        }
        item.pprice = (isNaN(item.pprice) || item.pprice == null || item.pprice == '') ? 0 : item.pprice;
        
        jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-product').attr('style', '')
        jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-table-body').append('<div class="pcbuilder-table-row"><div class="pcbuilder-table-item pcbuilder-3"><i>&times;</i>'+item.comp+'</div><div class="pcbuilder-table-item pcbuilder-6"><span>'+item.pbrand+'</span><a href="'+item.purl+'" target="_blank">'+item.product+'</a></div><div class="pcbuilder-table-item pcbuilder-3"><input type="text" readonly class="pcbuilder-price-clac" value="'+parseFloat(item.pprice)+'USD"></div><!-- pcbuilder-table-item --></div>');
        pcbuilder_calcprice();
        jQuery(this).closest('.pcbuilder-table-container').find('.pcbuilder-table-form').fadeToggle(300);
    });
    jQuery(document).on('click', '.pcbuilder-table-item i', function(e){
        jQuery(this).closest('.pcbuilder-table-row').remove();
        pcbuilder_calcprice();
    });
    jQuery(document).on('click', '.pcbuilder-print-btn', function(e){
        var print_style = '<link href="'+ajax_object.plugin_url+'assets/print.css" rel="stylesheet"><script src="'+ajax_object.plugin_url+'assets/print.js"></script>';
        var html  = jQuery(this).closest('.pcbuilder-table').html();
        var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
        WinPrint.document.write(html+print_style);
        WinPrint.document.close();
        WinPrint.focus();
        //WinPrint.print();
        //WinPrint.close();
    });

    jQuery('.pcbuilder-component').change(function(e){
        var comp = jQuery('.pcbuilder-component option:selected').val()
        var data = {
            'action': 'call_pc_products',
            'component': comp
        };
        var that = this;
        // We can also pass the url value separately from ajaxurl for front end AJAX implementations
        jQuery.post(ajax_object.ajax_url, data, function(response) {
            var markup = '';   
            var all_brands = [];  
            if(response != 0){
                var data = JSON.parse(response);
                data.forEach(function(brand){
                    if(jQuery.inArray( brand.brand, all_brands) == -1){
                        markup+= '<optgroup label="'+brand.brand+'">';
                        data.forEach(function(item){                    
                            if(brand.brand == item.brand){
                                markup+= '<option data-url="'+item.url+'" data-brand="'+item.brand+'" data-price="'+item.price+'" value="'+item.name+'">'+item.name+' ('+item.price+'USD)</option>';
                            }
                        })
                        markup+= '</optgroup>';
                        all_brands.push(brand.brand);
                    }
                });     
            }else{
                markup = '<option value="">Found No Products.</option>';
            }
            jQuery(that).closest('.pcbuilder-table-container').find('.pcbuilder-product').html(markup);
            

        });
    });
    pcbuilder_calcprice();
})