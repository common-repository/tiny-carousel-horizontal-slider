function tch_submit()
{
	if((document.tch_form.tch_width.value=="") || (isNaN(document.tch_form.tch_width.value)))
	{
		alert(tiny_carousel_adminscripts.tch_width);
		document.tch_form.tch_width.focus();
		return false;
	}
	else if((document.tch_form.tch_height.value=="") || (isNaN(document.tch_form.tch_height.value)))
	{
		alert(tiny_carousel_adminscripts.tch_height);
		document.tch_form.tch_height.focus();
		return false;
	}
	else if((document.tch_form.tch_display.value=="") || (isNaN(document.tch_form.tch_display.value)))
	{
		alert(tiny_carousel_adminscripts.tch_display);
		document.tch_form.tch_display.focus();
		return false;
	}
	else if((document.tch_form.tch_intervaltime.value=="") || (isNaN(document.tch_form.tch_intervaltime.value)))
	{
		alert(tiny_carousel_adminscripts.tch_intervaltime);
		document.tch_form.tch_intervaltime.focus();
		return false;
	}
	else if((document.tch_form.tch_duration.value=="") || (isNaN(document.tch_form.tch_duration.value)))
	{
		alert(tiny_carousel_adminscripts.tch_duration);
		document.tch_form.tch_duration.focus();
		return false;
	}
	else if(document.tch_form.tch_folder.value=="")
	{
		alert(tiny_carousel_adminscripts.tch_folder);
		document.tch_form.tch_folder.focus();
		return false;
	}
}

function tch_delete(id)
{
	if(confirm(tiny_carousel_adminscripts.tch_delete))
	{
		document.frm_tch_display.action="options-general.php?page=tiny-carousel-horizontal-slider&ac=del&did="+id;
		document.frm_tch_display.submit();
	}
}	

function tch_redirect()
{
	window.location = "options-general.php?page=tiny-carousel-horizontal-slider";
}

function tch_help()
{
	window.open("http://www.gopiplus.com/work/2012/05/26/tiny-carousel-horizontal-slider-wordpress-plugin/");
}