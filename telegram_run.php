<?php
require_once 'TelegramBotModel.php';

$host = 'https://api.telegram.org';
$token = '582054434:AAEVQ8Cdihvhw1jHv5cO-AJfF6xzLkbhugE';

function reply($chat_id, $text)
{
	$text = strtolower($text);
	$result['chat_id'] = $chat_id;
	if (stristr($text, 'hai')) {
		$result['text'] = 'hai juga, apa kabar ?';
	} else if (stristr($text, 'baik')) {
		$result['text'] = 'owh baik, udah makan ?';
	} else if (stristr($text, 'udah')) {
		$result['text'] = 'ooo, lagi apa ?';
	} else if (stristr($text, '/main')) {
		$keyboard = array(
			array(
				array('text'=>'text1','callback_data'=>"1"),
				array('text'=>'text2','callback_data'=>"2")
			),
			array(
				array('text'=>'start','callback_data'=>"4")
			)
		);
		$inlineKeyboardMarkup = array(
			'inline_keyboard' => $keyboard
		);
		$result['text'] = 'crot';
		$result['reply_markup'] = $inlineKeyboardMarkup;
	} else {
		$reply_list = array('apakah maksud anda dengan ' . $text . '?', 'apaan si ga jelas !', 'ga ngerti maksudnya ?', 'biasa aja dong !', 'bisa tolong di jelaskan !');
		$index = rand(0,4);
		$result['text'] = $reply_list[$index] . ' /main';
	}
	return $result;
}

$telegram = new TelegramBot($host, $token);
$data_update = $telegram->getUpdates();
$data_end = end($data_update);
$update_id = $data_end['update_id'];
$update_id = $update_id + 1;
while (1) {
	$data = array();
	$data['offset'] = $update_id;
	$data_update = $telegram->getUpdates($data);
	$data_end = end($data_update);
	if ($data_end['update_id'] == $update_id) {
		echo $data_end['message']['chat']['username'] . '(' . $data_end['message']['chat']['id'] . ') => ' . $data_end['message']['text'] . "\n";
		$data = array();
		$data = reply($data_end['message']['chat']['id'], $data_end['message']['text']);
		$telegram->sendMessage($data);
		$update_id = $update_id + 1;
	} else {
		echo 'listening ..' . "\n";
	}
}
