<?php
//Чтобы не вылезали варнинги при формировании json

ini_set('display_errors','On');
error_reporting('E_ALL');

require_once 'CandidateAbstract.php';
require_once 'Toolkit.php';

class Candidate extends CandidateAbstract
{
	
	public function run()
    {
        $error = 0;

        $address = $_GET['address'];
        $phone = $_GET['phone'];

        $phone = Toolkit::getFormattedPhone($phone);

        $nearPointInformation = $this->calculateDistance(Toolkit::getCoords($address));

        //Получили все необходимые данные - формируем ответ
        //Проверяем ошибку
        if (!$nearPointInformation) {
            $error = 1;
            $nearPointInformation['name'] = "";
            $nearPointInformation['distance'] = "";
            $phone = "";
        }
        $json_data = array( 'pointName' => $nearPointInformation['name'],
                            'distance' => $nearPointInformation['distance'],
                            'phone' => $phone,
                            'error' => $error);

        //Возвращаем
        echo json_encode($json_data);

    }


    /**
     * Производит нахождение ближайшего к клиенту
     * пункта выдачи товара
     *
     * @param $coordsCandidate - координаты клиента
     * @return mixed|bool - пара "название пункта выдачи", "расстояние до него" либо false, в случае ошибки
     */
	public function calculateDistance($coordsCandidate)
	{
        $addressFrom = $this->getAddressByCoords($coordsCandidate['lat'],$coordsCandidate['lng']);

        $db = $this->connectToDatabases();

        $query = "SELECT name, lat, lng FROM issue_point_table";

        $answer = mysqli_query($db, $query)
        or die("Error " . mysqli_error($db));

        $minDistance = 0;
        $pointName = "";

        if ($answer) {


            $rows = mysqli_num_rows($answer);

            for ($i = 0; $i < $rows; $i++) {
                $row = mysqli_fetch_row($answer);
                //Получили координаты
                $lat = $row[1];
                $lng = $row[2];

                $addressTo = $this->getAddressByCoords($lat,$lng);


                $nowDistance = $this->getDistance($addressFrom, $addressTo);

                //Если все хорошо - работаем
                if ($nowDistance) {
                    //Если проход первый
                    if ($minDistance == 0) {
                        $minDistance = $nowDistance;
                        $pointName = $row[0];
                    } //Иначе сравниваем
                    else if ($minDistance > $nowDistance) {
                        $minDistance = $nowDistance;
                        $pointName = $row[0];
                    }
                }
                else { //Если что-то пошло не так - возвращаем ошибку и вылетаем отсюда
                       return false;
                }
            }
        }

        mysqli_close($db);

        //Теперь у нас есть название и расстояние до ближайшего пункта выдачи

        $result['name']=  $pointName;
        $result['distance'] = $minDistance;

        return $result;
		
	}


	/**
	 * Производит подключение к тестовой базе данных, в которой хранится
     * информация о всех доступных точках выдачи
	 */
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


    /**
     * Возвращает адрес точки заданной широтой и долготой
     * с исоплзованием google maps api
     *
     * @param $lat - широта точки
     * @param $lng - долгота точки
     * @return bool|string - адрес заданной точки, либо false, в случае ошибки
     */
    public function getAddressByCoords($lat, $lng) {
        if(!$lat || !$lng){
            return false;
        }

        $data = file_get_contents("https://maps.google.com/maps/api/geocode/json?latlng="  . $lat . "," . $lng);
        $json = json_decode($data);
        $result = $json -> results[0] -> formatted_address;

        return $result;
    }

    /**
     * Расчитывает расстояние между двумя заданными адресами
     * с использованием google maps api
     *
     * @param $from - адрес отправления
     * @param $to - адрес назначения
     * @return bool|string - расстояние между точками, либо false, в случае ошибки
     */
    public function getDistance($from, $to)
    {
        if (!$from || !$to) {
            return false;
        }

        $from = str_replace(" ", "-", $from);
        $to = str_replace(" ", "-", $to);
        $key = "AIzaSyDFIWIuAKGm5fEM8FhzXTMSMf1ehVRiT4Q";
        $data = file_get_contents("https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=" . $from . "&destinations=" . $to . "&key=" . $key);
        $json = json_decode($data);

        if ($json->rows[0]->elements[0]->status == "OK") {
            $result = $json->rows[0]->elements[0]->distance->text;
            $result = ereg_replace("[^0-9,/.]", "", $result);
        }
        else {
            $result = false;
        }
        return $result;
    }
	
}

?>