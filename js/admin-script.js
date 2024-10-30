jQuery( document ).ready(function() {
	jQuery(".addnewfielcfqjm").click(function(){
		jQuery(".showpopmain").show();
	  return false;
	});	
	jQuery(".editfield_pop").click(function(){
		jQuery(".showpopmain").show();
	  return false;
	});	
	jQuery(".closeicond").click(function(){
		jQuery(".showpopmain").hide();
	  return false;
	});	
	jQuery(".field_type_cfedd").change(function(){
		
		var field_type_cfedd = jQuery(this).val();
		if (field_type_cfedd=='select') {
			jQuery(".cfedd_option").show();
		}else{
			jQuery(".cfedd_option").hide();
		}
	  return false;
	});			
});