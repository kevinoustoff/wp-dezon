(function($) {
   var first_time_vendor_change = true;
	$(document).ready(function() {
        
       $("#vendor_account_country").on( 'change',function(){
            
            if(first_time_vendor_change){
                first_time_vendor_change = false;
            }else{
                $("#vendor_account_region").val('');    
            }
       });
       
    });
    
})( jQuery );    
