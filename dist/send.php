<?php

require_once __DIR__ . '/amo/access.php';

$name = "Pavel Usov";
$email = htmlspecialchars($_POST['email']);
$phone = htmlspecialchars($_POST['phone']);
$message = "Тестовое задание";
$lead_title = 'Заявка - '.$name;

$price = 150000;

$data = [
    [
        "name" => $lead_title,
        "price" => $price,
        "_embedded" => [
            "contacts" => [
                [
                    "first_name" => $name,
                    "custom_fields_values" => [
                        [
                            "field_code" => "EMAIL",
                            "values" => [
                                [
                                    "enum_code" => "WORK",
                                    "value" => $email
                                ]
                            ]
                        ],
                        [
                            "field_code" => "PHONE",
                            "values" => [
                                [
                                    "enum_code" => "WORK",
                                    "value" => $phone
                                ]
                            ]
                        ]
					]
                ]
            ]
        ],
    ]
];

$method = "/api/v4/leads/complex";

$headers = [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $access_token,
];


  if(!empty($email) && !empty($phone)){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      $receiver = "Djaild@yandex.ru";
      $subject = "Заявка $name";
      $body = "Имя: $name\nEmail: $email\Телефон: $phone\n\nСообщение:\n$message\n\nС уважением,\n$name";
      $sender = "From: $email";
      if(mail($receiver, $subject, $body, $sender)){

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-API-client/1.0');
		curl_setopt($curl, CURLOPT_URL, "https://$subdomain.amocrm.ru".$method);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_COOKIEFILE, __DIR__.'/amo/cookie.txt');
		curl_setopt($curl, CURLOPT_COOKIEJAR, __DIR__.'amo/cookie.txt');
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		$out = curl_exec($curl);
		$code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$code = (int) $code;
		// $errors = [
		// 	301 => 'Moved permanently.',
		// 	400 => 'Wrong structure of the array of transmitted data, or invalid identifiers of custom fields.',
		// 	401 => 'Not Authorized. There is no account information on the server. You need to make a request to another server on the transmitted IP.',
		// 	403 => 'The account is blocked, for repeatedly exceeding the number of requests per second.',
		// 	404 => 'Not found.',
		// 	500 => 'Internal server error.',
		// 	502 => 'Bad gateway.',
		// 	503 => 'Service unavailable.'
		// ];

         echo "Сообщение успешно отправлено";
      }else{
         echo "Что-то пошло не так. Обратитесь к админу.";
      }
    }else{
      echo "Введите корректный email";
    }
  }else{
    echo "Заполните все поля";
  }
?>
