<?php
 // Проверка на ошибки
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

class SoapSmsGateWay {
//Описание функции веб-сервиса
  public function sendSms($messagesData){

      $rawPost  = "Input:\r\n";
      //Читает содержимое файла в строку
      $rawPost .= file_get_contents('php://input');
      $rawPost .= "\r\n---\r\nmessageData:\r\n";
      // генерирует хранимое представление значения.
      $rawPost .= serialize($messagesData);
      //Пишет строку $rawPost в файл log.txt
      file_put_contents("log.txt",$rawPost);
      //Для отключения юникода
      $jsonString = json_encode($messagesData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK);
      //Получаем массив
      $jsonString = json_decode($jsonString, true);
      //Создаём или открываем базу данных test.db
      $db = new SQLite3("test.db");
      //Экранирование строк
      $number = $db->escapeString($jsonString['Smsservice']['phone']);
      $mes = $db->escapeString($jsonString['Smsservice']['text']);
      $dat = $db->escapeString($jsonString['Smsservice']['date']);
      //Для запросов без выборки данных
      $sql = "INSERT INTO smsservice (number_phone, messagedb, datadb) VALUES ('$number', '$mes', '$dat')";
      //Возвращает значение булева типа
      $resulting = $db->exec($sql);
      //Закрываем базу данных без удаления объекта
      $db->close();
      return array("status" => "true");

  }
}
 
// Отключение кэширования WSDL-документа
ini_set("soap.wsdl_cache_enabled", "0"); // отключаем для тестирования, т.к файлы очень хорошо кешируются
// Создание SOAP-сервер
$server = new SoapServer("http://{$_SERVER['HTTP_HOST']}/Smsservice.wsdl");
// Добавить класс к серверу
$server->setClass("SoapSmsGateWay"); // заменяем только эту строку
// Запуск сервера
$server->handle();
?>