<?php
	$ajaxpath = $_GET['ajaxpath'];
	$path = $_GET['path'];
?>

function urldecode( str ) {
	// Decodes URL-encoded string  
	// 
	// version: 905.3122
	// discuss at: http://phpjs.org/functions/urldecode
	// +   original by: Philip Peterson
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +	  input by: AJ
	// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Brett Zamir (http://brett-zamir.me)
	// +	  input by: travc
	// +	  input by: Brett Zamir (http://brett-zamir.me)
	// +   bugfixed by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
	// +   improved by: Lars Fischer
	// %		  note 1: info on what encoding functions to use from: http://xkr.us/articles/javascript/encode-compare/
	// *	 example 1: urldecode('Kevin+van+Zonneveld%21');
	// *	 returns 1: 'Kevin van Zonneveld!'
	// *	 example 2: urldecode('http%3A%2F%2Fkevin.vanzonneveld.net%2F');
	// *	 returns 2: 'http://kevin.vanzonneveld.net/'
	// *	 example 3: urldecode('http%3A%2F%2Fwww.google.nl%2Fsearch%3Fq%3Dphp.js%26ie%3Dutf-8%26oe%3Dutf-8%26aq%3Dt%26rls%3Dcom.ubuntu%3Aen-US%3Aunofficial%26client%3Dfirefox-a');
	// *	 returns 3: 'http://www.google.nl/search?q=php.js&ie=utf-8&oe=utf-8&aq=t&rls=com.ubuntu:en-US:unofficial&client=firefox-a'
	
	var histogram = {}, ret = str.toString(), unicodeStr='', hexEscStr='';
	
	var replacer = function(search, replace, str) {
		var tmp_arr = [];
		tmp_arr = str.split(search);
		return tmp_arr.join(replace);
	};
	
	// The histogram is identical to the one in urlencode.
	histogram["'"]	 = '%27';
	histogram['(']	 = '%28';
	histogram[')']	 = '%29';
	histogram['*']	 = '%2A';
	histogram['~']	 = '%7E';
	histogram['!']	 = '%21';
	histogram['%20'] = '+';
	histogram['\u00DC'] = '%DC';
	histogram['\u00FC'] = '%FC';
	histogram['\u00C4'] = '%D4';
	histogram['\u00E4'] = '%E4';
	histogram['\u00D6'] = '%D6';
	histogram['\u00F6'] = '%F6';
	histogram['\u00DF'] = '%DF'; 
	histogram['\u20AC'] = '%80';
	histogram['\u0081'] = '%81';
	histogram['\u201A'] = '%82';
	histogram['\u0192'] = '%83';
	histogram['\u201E'] = '%84';
	histogram['\u2026'] = '%85';
	histogram['\u2020'] = '%86';
	histogram['\u2021'] = '%87';
	histogram['\u02C6'] = '%88';
	histogram['\u2030'] = '%89';
	histogram['\u0160'] = '%8A';
	histogram['\u2039'] = '%8B';
	histogram['\u0152'] = '%8C';
	histogram['\u008D'] = '%8D';
	histogram['\u017D'] = '%8E';
	histogram['\u008F'] = '%8F';
	histogram['\u0090'] = '%90';
	histogram['\u2018'] = '%91';
	histogram['\u2019'] = '%92';
	histogram['\u201C'] = '%93';
	histogram['\u201D'] = '%94';
	histogram['\u2022'] = '%95';
	histogram['\u2013'] = '%96';
	histogram['\u2014'] = '%97';
	histogram['\u02DC'] = '%98';
	histogram['\u2122'] = '%99';
	histogram['\u0161'] = '%9A';
	histogram['\u203A'] = '%9B';
	histogram['\u0153'] = '%9C';
	histogram['\u009D'] = '%9D';
	histogram['\u017E'] = '%9E';
	histogram['\u0178'] = '%9F';

	for (unicodeStr in histogram) {
		hexEscStr = histogram[unicodeStr]; // Switch order when decoding
		ret = replacer(hexEscStr, unicodeStr, ret); // Custom replace. No regexing
	}
	
	// End with decodeURIComponent, which most resembles PHP's encoding functions
	ret = decodeURIComponent(ret);

	return ret;
}

function changeimg(d,s)
{
	jQuery("#xencarousel_thumb").css("background","url("+urldecode(d.img)+") no-repeat");
	jQuery("#xencarousel_thumb").css("width",d.h+"px");
	jQuery("#xencarousel_thumb").css("height",d.w+"px");
	
	jQuery("#xencarousel_thumb").hover(function() {
		jQuery("#xendelete").show();
	},function() {
		jQuery("#xendelete").hide();
	});
	
}

jQuery(document).ready(function($) {

	$("#xencarouselimage").keypress(function() { 
		$("#xencarouselimage").css({"background":"url(<?php echo $path; ?>/img/ajax-loader.gif) no-repeat center right"});
	});

	$("#xencarouselimage").bind("select blur",function() { 
		$("#xencarouselimage").css({"background":"none"});
	});
	
	$("#xendelete").click(function() {
		$("#xencarousel_thumb").css("background","none");
		$("#xencarousel_thumb").css("width","100px");
		$("#xencarousel_thumb").css("height","100px");
		$("#xencarouselimageid").val("");
		$("#xencarouselimage").val("");
		$("#xendelete").hide();
		$("#xencarousel_thumb").unbind('mouseenter mouseleave');
	});
	
	$("#xencarouselimage").autocomplete("<?php echo $ajaxpath; ?>",{ 
		extraParams: { action: "carousel_ajax_search" }
	}).result(function(evt, data, formatted) {
		$("#xencarouselimageid").val(data[1]);

		$.ajax({
			url: "<?php echo $ajaxpath; ?>",
			data: "xencarousel_image_id="+data[1]+"&action=carousel_ajax_image",
			success: changeimg,
			dataType: "json"
		});
		
	});
		
});
