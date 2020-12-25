<script>
$(document).ready(function () {
	public_loadFunction();
	var url_string =window.location.href;
    var array_url = url_string.split("/");
    console.log(array_url);
	@if($type_page=='create_classic')
		get_current_sub_eldwry('subeldwry_groupEldwry',array_url[6]);	
	@elseif($type_page=='create_head')
		get_current_sub_eldwry('subeldwry_groupEldwry',array_url[6]);
	@elseif($type_page == 'create_done')
		get_last_group_eldwry(array_url[6]);
	@elseif($type_page == 'admin')
		get_current_sub_eldwry('subeldwry_groupEldwry',array_url[6]);
		setting_admin_group_eldwry('',array_url[6]);	
	@elseif($type_page == 'group')
		get_current_sub_eldwry('subeldwry_groupEldwry',array_url[6],array_url[7],'<option value="">'+'overAll'+'</option>');
		get_data_group_eldwry(array_url[7],'',array_url[6]);
	@elseif($type_page == 'invite')
    	$('body').find('.add_join_group_eldwry').attr('data-type',array_url[6]);
		setting_invite_group_eldwry(array_url[7],array_url[6]);		
	@elseif($type_page == 'accept_invite')
    	$('body').find('.add_joinLink_group_eldwry').attr('data-type',array_url[6]);
    	$('body').find('.add_joinLink_group_eldwry').attr('data-link',array_url[7]);//add_join_group_eldwry
		setting_invite_group_eldwry(array_url[7],array_url[6]);		
	@endif
});
</script>