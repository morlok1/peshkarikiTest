<?php

require_once 'CandidateAbstract.php';
require_once 'Toolkit.php';

//AIzaSyDFIWIuAKGm5fEM8FhzXTMSMf1ehVRiT4Q
class Candidate extends CandidateAbstract
{
	
	public function run()
    {

        $address = $_GET['address'];
        $phone = $_GET['phone'];

        $phone = Toolkit::getFormattedPhone($phone);

        $distance = $this->calculateDistance(Toolkit::getCoords($address));

    }
	
	public function calculateDistance($coordsCandidate)
	{
        $addressFrom = $this->getAddressByCoords($coordsCandidate);


        //Соединяемся с базой данных
        $db = $this->connectToDatabases();

        $query = "SELECT * FROM issue_point_table";




        mysqli_close($db);

		
	}

	public function connectToDatabases() {
        $host = 'localhost'; // адрес сервера
        $database = 'test'; // имя базы данных
        $user = 'root'; // имя пользователя
        $password = ''; // пароль
        $link = mysqli_connect($host, $user, $password, $database)
        or die("Ошибка " . mysqli_error($link));

        mysqli_query($link, 'SET NAMES UTF8');

        return $link;
    }

    public function getAddressByCoords($lat, $lng) {
        if(!$lat || !$lng){
            return false;
        }

        $data = file_get_contents("https://maps.google.com/maps/api/geocode/json?latlng="  . $lat . "," . $lng);
        $json = json_decode($data);
        $result = $json -> results[0] -> formatted_address;

        return $result;
    }

    public function getDistance($from, $to) {
        if(!$from || !$to){
            return false;
        }

        $from = str_replace(" ", "-", $from);
        $to = str_replace(" ", "-", $to);
        $key = "AIzaSyDFIWIuAKGm5fEM8FhzXTMSMf1ehVRiT4Q";
        $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=imperial&origins=" . $from . "&destinations=" . $to . "&key=" . $key);
        $json = json_decode($data);

        $result = $json->rows[0]->elements[0]->distance->text;
        $result = trim(str_replace("mi", "", $result));

        return $result;
    }
	
}

?>