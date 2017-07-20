<?php
class Toolkit {
	public static function getCoords($address)
	{
	    if(!$address){
	    	return false; 
		}
		$address = preg_replace("/ /i", "%20", "Санкт-Петербург" . ',' . $address);
		$data = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=" . $address . "&sensor=false");
		$json = json_decode($data);
		$result['lat'] = $json -> results[0] -> geometry -> location -> lat;
		$result['lng'] = $json -> results[0] -> geometry -> location -> lng;
		return $result;
	}
	
	public static function getFormattedPhone($phone)
	{
		$phone = ereg_replace("[^0-9]",'',$phone); 
	    if(strlen($phone) != 10){
	    	return false; 
		}
	    $sArea = substr($phone, 0,3); 
	    $sPrefix = substr($phone,3,3); 
	    $sNumber = substr($phone,6,4); 
	    $phone = "+7"."(".$sArea.")".$sPrefix."-".$sNumber; 
	    return $phone;
	}
}
	