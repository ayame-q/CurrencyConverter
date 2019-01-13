<?php
$text = trim(getenv('POPCLIP_TEXT'));
$to = trim(getenv('POPCLIP_OPTION_CURRENCY'));
$fee = trim(getenv('POPCLIP_OPTION_FEE'));

$frommark = mb_substr($text, 0, 1);
$price = mb_substr($text, 1);
$price = str_replace(",", "", $price);

switch ($frommark){
	case "$":
		$from = mb_strtolower(trim(getenv('POPCLIP_OPTION_DOLLAR')));	//ドル設定
		break;
	case "€":
		$from = "eur";	//ユーロ
		break;
	case "¥":
		$from = mb_strtolower(trim(getenv('POPCLIP_OPTION_YEN')));	//¥設定
		break;
	case "£":
		$from = "gbp";	//イギリスポンド
		break;
	case "₽":
		$from = "rub";	//ロシアルーブル
		break;
	case "₹":
		$from = "inr";	//インドルピー
		break;
	case "₩":
		$from = "krw";	//韓国ウォン
		break;
	case "R":
		$from = "zar";	//南アフリカランド
		break;
	case "฿":
		$from = "thb";	//タイバーツ
		break;
	case "₱":
		$from = "php";	//フィリピンペソ
		break;
	case "₺":
		$from = "try";	//トルコリラ
		break;
	case "₺":
		$from = "ngn";	//ナイジェリアナイラ
		break;
		
	default:
		// エラー
		exit();
}

$url = "http://api.aoikujira.com/kawase/json/" . $from;
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
$res = curl_exec($curl);
$header = curl_getinfo($curl);
$code = $header["http_code"];
if ($code >= 400 || !$res) {
	// エラー
	exit();
}
$data = json_decode($res, true);

$base = $data[$to];

switch ($to){
	case "USD":
		$tomark = "$";
		$digit = 2;
		break;
	case "EUR":
		$tomark = "€";
		$digit = 2;
		break;
	case "JPY":
		$tomark = "¥";
		$digit = 0;
		break;
	case "GBP":
		$tomark = "€";
		$digit = 2;
		break;
	case "CAD":
		$tomark = "$";
		$digit = 2;
		break;
	case "AUD":
		$tomark = "$";
		$digit = 2;
		break;
	case "CNY":
		$tomark = "¥";
		$digit = 2;
		break;
	case "RUB":
		$tomark = "₽";
		$digit = 2;
		break;
	case "BRL":
		$tomark = "$";
		$digit = 2;
		break;
	case "INR":
		$tomark = "₹";
		$digit = 2;
		break;
	case "KRW":
		$tomark = "₩";
		$digit = 2;
		break;
	case "TWD":
		$tomark = "$";
		$digit = 2;
		break;
	case "NZD":
		$tomark = "$";
		$digit = 2;
		break;
	case "HKD":
		$tomark = "$";
		$digit = 2;
		break;
	case "THB":
		$tomark = "฿";
		$digit = 2;
		break;
	case "PHP":
		$tomark = "₱";
		$digit = 2;
		break;
	case "ZAR":
		$tomark = "R";
		$digit = 2;
		break;
	case "TRY":
		$tomark = "₺";
		$digit = 2;
		break;
	case "NGN":
		$tomark = "₺";
		$digit = 2;
		break;
	case "SGD":
		$tomark = "$";
		$digit = 2;
		break;
	case "MXN":
		$tomark = "$";
		$digit = 2;
		break;
}

echo($tomark . number_format($price * $base + $price * $base * $fee * 0.01, $digit));