<?php
function ListGetAt($list,$position,$delimiter=",")
{
$bit = explode($delimiter,$list);
return $bit[($position-1)];
}

function is_element_empty($featured_ad) {
	if(empty($featured_ad)) {
		return "This element is empty.";
	} else {
		return "This element is not empty!";
	}
}

function get_gem_title ($in_title_field, $in_adnum ) {
	if (empty($in_title_field) ) {
		return $in_adnum;
	} else {
		return $in_title_field;
	}
}

function get_gem_image($in_img_field) {
	if (!empty($in_img_field) ) {
		$imgurl = str_replace(".PDF", ".JGP", $in_img_field);
		$imgurl = str_replace(".jpg", ".JGP", $in_img_field);
		$imgurl = "<img src='http://manage.downeastmaine.com/manage.downeastmaine.com/gemexport/classads/".$imgurl."'>";
		return $imgurl;
	}
}

function map_gem_cat($in_gem_cat) {
	$out_str = "";
	switch($in_gem_cat) {
			// realestate
		case "3525 Lots and Land Sale": 
		case "3555 Seasonal Property":
			$out_str = "real-estate-for-sale";
			break;
		// for sale
		case "1330 Furniture":
		case "1340 General Merchandise":
		case "1370 Rummage Sale":
			$out_str = "for-sale-classifieds";
			break;
		// general/services
		default:
			$out_str = "services-classifieds";
	} // end switch
	return $out_str;
			
}
?>