<?php

$API_URL = 'https://api.line.me/v2/bot/message/reply';
$ACCESS_TOKEN = 'F32rTHtphRFXMBhlbbiMJ8aVvTSZzV1gekMbuCfZa0jSUALD4U7eRBX9DBLrNY9DRmnOkWANxqopjQSEr4vMicYZq5GwVT5SCScTxUZYc5qQ1rMZUGssk9lYpOl4C9DN2qyROpsz6nguFrvvQi4ufQdB04t89/1O/w1cDnyilFU='; // Access Token ค่าที่เราสร้างขึ้น
$POST_HEADER = array('Content-Type: application/json', 'Authorization: Bearer ' . $ACCESS_TOKEN);

$request = file_get_contents('php://input');   // Get request content
$request_array = json_decode($request, true);   // Decode JSON to Array

//API Url
$url = '(https://maker.ifttt.com/trigger/click_final/with/key/fF25pJ-v04BVZm7CRNJbhCXN49MzHkkrPRrH1HFgeDA';

//Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$jsonData = array(
    'Value1' => '169',
    'Value2' => '55',
    'Value3' => '16'
);

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); 

//Execute the request


if ( sizeof($request_array['events']) > 0 )
{

 foreach ($request_array['events'] as $event)
 {
  $reply_message = '';
  $reply_token = $event['replyToken'];

  if ( $event['type'] == 'message' ) 
  {
   
   if( $event['message']['type'] == 'text' )
   {
		$text = $event['message']['text'];
		
		if(($text == "อุณหภูมิตอนนี้")||($text == "อุณหภูมิวันนี้")||($text == "อุณหภูมิ")){
			$temp = 27;
			$reply_message = 'ขณะนี้อุณหภูมิที่ '.$temp.'°C องศาเซลเซียส';
		}
		else if(($text== "ตอนนี้อยู่ที่ไหน")||($text== "ตอนนี้อยู่ไหน")||($text== "อยู่ที่ไหน")||($text== "อยู่ไหน")){
			$reply_message = 'ขณะนี้อยู่ที่ห้องเรียน IF-5T05 ...!!!';
		}
	   	else if(($text== "อยากทราบยอด COVID-19 ครับ")||($text== "อยากทราบยอดCOVID-19ครับ")||($text== "อยากทราบยอดCOVID-19")||($text== "COVID-19")){
			$reply_message = curl_exec($ch); 
				//$reply_message = 'รายงานสถานการณ์ ยอดผู้ติดเชื้อไวรัสโคโรนา 2019 (COVID-19) ในประเทศไทย"
					//ผู้ป่วยสะสม     จำนวน 398,995 ราย
					//ผู้เสียชีวิต	  จำนวน 17,365 ราย
					//รักษาหาย      จำนวน 103,753 ราย
					//ผู้รายงานข้อมูล: นายกานต์ เจริญจิตร';
		}
	   	else if(($text== "ข้อมูลส่วนตัวของผู้พัฒนาระบบ")||($text== "ผู้พัฒนาระบบ")||($text== "ข้อมูลส่วนตัวของผู้พัฒนา")||($text== "ข้อมูลผู้พัฒนา")){
			$reply_message = 'ชื่อนายสุพัฒนชัย ปานสุรินทร์ อายุ 22ปี น้ำหนัก 75kg. สูง 151cm. ขนาดรองเท้าเบอร์ 39 ใช้หน่วย EUR';
		}
		else
		{
			$reply_message = 'ระบบได้รับข้อความ ('.$text.') ของคุณแล้ว';
    		}
   
   }
   else
    $reply_message = 'ระบบได้รับ '.ucfirst($event['message']['type']).' ของคุณแล้ว';
  
  }
  else
   $reply_message = 'ระบบได้รับ Event '.ucfirst($event['type']).' ของคุณแล้ว';
 
  if( strlen($reply_message) > 0 )
  {
   //$reply_message = iconv("tis-620","utf-8",$reply_message);
   $data = [
    'replyToken' => $reply_token,
    'messages' => [['type' => 'text', 'text' => $reply_message]]
   ];
   $post_body = json_encode($data, JSON_UNESCAPED_UNICODE);

   $send_result = send_reply_message($API_URL, $POST_HEADER, $post_body);
   echo "Result: ".$send_result."\r\n";
  }
 }
}

echo "OK";

function send_reply_message($url, $post_header, $post_body)
{
 $ch = curl_init($url);
 curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $post_header);
 curl_setopt($ch, CURLOPT_POSTFIELDS, $post_body);
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
 $result = curl_exec($ch);
 curl_close($ch);

 return $result;
}

?>
