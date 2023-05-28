<?php
ob_start();
$ttti = time();
error_reporting(0);
define('API_KEY','6110901755:AAGAajrbHvf51at38panzwDRfZY7KKYD2oY');
date_default_timezone_set('Asia/Tehran');
//-----------------------------------------------------------------------------------------
$telegram_ip_ranges = [
['lower' => '149.154.160.0', 'upper' => '149.154.175.255'], // literally 149.154.160.0/20
['lower' => '91.108.4.0',    'upper' => '91.108.7.255'],    // literally 91.108.4.0/22
];

$ip_dec = (float) sprintf("%u", ip2long($_SERVER['REMOTE_ADDR']));
$ok=false;

foreach ($telegram_ip_ranges as $telegram_ip_range) if (!$ok) {
    // Make sure the IP is valid.
    $lower_dec = (float) sprintf("%u", ip2long($telegram_ip_range['lower']));
    $upper_dec = (float) sprintf("%u", ip2long($telegram_ip_range['upper']));
    if ($ip_dec >= $lower_dec and $ip_dec <= $upper_dec) $ok=true;
}
if (!$ok) die("No spam 🙃");
//-----------------------------------------------------------------------------------------------
//functions
function bot($method,$datas=[]){
$url = "https://api.telegram.org/bot".API_KEY."/".$method;
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
$res = curl_exec($ch);
if(curl_error($ch)){
var_dump(curl_error($ch));
}else{
return json_decode($res);
}
}
function SM($chatID)
{
	$tab = json_decode(file_get_contents("../../tab.json"),true);
	if($tab['type'] == 'photo')
	{
		bot('sendphoto',['chat_id'=>$chatID,'photo'=>$tab["msgid"],'caption'=>$tab["text"],'reply_markup'=>$tab['reply_markup']]);
	}
	else if($tab['type'] == 'file')
	{
		bot('sendDocument',['chat_id'=>$chatID,'document'=>$tab["msgid"],'caption'=>$tab["text"],'reply_markup'=>$tab['reply_markup']]);
	}
	else if($tab['type'] == 'video')
	{
		bot('SendVideo',['chat_id'=>$chatID,'video'=>$tab["msgid"],'caption'=>$tab["text"],'reply_markup'=>$tab['reply_markup']]);
	}
	else if($tab['type'] == 'music')
	{
		bot('SendAudio',['chat_id'=>$chatID,'audio'=>$tab["msgid"],'caption'=>$tab["text"],'reply_markup'=>$tab['reply_markup']]);
	}
	else if($tab['type'] == 'sticker')
	{
		bot('SendSticker',['chat_id'=>$chatID,'sticker'=>$tab["msgid"],'caption'=>$tab["text"],'reply_markup'=>$tab['reply_markup']]);
	}
	else if($tab['type'] == 'voice')
	{
		bot('SendVoice',['chat_id'=>$chatID,'voice'=>$tab["msgid"],'caption'=>$tab["text"],'reply_markup'=>$tab['reply_markup']]);
	}
	else
	{
		if($tab['reply_markup'] != null)
		{
			bot('SendMessage',['chat_id'=>$chatID,'text'=>$tab['text'],'reply_markup'=>$tab['reply_markup']]);
		}
		else
		{
			bot('SendMessage',['chat_id'=>$chatID,'text'=>$tab['text']]);
		}
	}
}
function SendPhoto($chat_id,$link,$text) {
bot('SendPhoto',['chat_id' => $chat_id, 'photo' => $link, 'caption' => $text]);
}
function sendmessage($chat_id,$text){
bot('sendMessage',['chat_id'=>$chat_id,'text'=>$text,'parse_mode'=>"html"]);
}
function save($filename, $data)
{
$file = fopen($filename, 'w');
fwrite($file, $data);
fclose($file);
}
function getChatstats($chat_id,$token) {
  $url = 'https://api.telegram.org/bot'.$token.'/getChatAdministrators?chat_id=@'.$chat_id;
  $result = file_get_contents($url);
  $result = json_decode ($result);
  $result = $result->ok;
  return $result;
}
function getRanks($file){
   $users = scandir('data/');
   $users = array_diff($users,[".",".."]);
   $coins =[];
   foreach($users as $user){
    $coin = json_decode(file_get_contents('data/'.$user.'/'.$user.'.json'),true)["$file"];
    $coins[$user] = $coin;
}
   arsort($coins);
   foreach($coins as $key => $user){
   $list[] = array('user'=>$key,'coins'=>$coins[$key]);
   } 
   return $list;
}
function deletemessage($chat_id,$message_id){
bot('deletemessage', ['chat_id' => $chat_id,'message_id' => $message_id,]);
}

//Variables
$update = json_decode(file_get_contents('php://input'));
$message = $update->message;
$chat_id = $message->chat->id;
$text = $message->text;
$tc = $message->chat->type;
$message_id = $message->message_id;
$first_name = $message->from->first_name;
$from_id = $message->from->id;
$first = $message->from->first_name;
$last = $message->from->last_name;
$username = $message->from->username;
$first2 = $update->callback_query->message->chat->first_name;
$last2 = $update->callback_query->message->chat->last_name;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$message_id2 = $update->callback_query->message->message_id;
$photo = $message->photo;
$sudo = ['6196270628','6196270628','6196270628'];
$admin = "6196270628"; //نایدی عددی ادمی
$channel = file_get_contents("channel.txt"); // آیدی چنل جوین اجباری با @
$token = "1618369374:AAGv0IeDEqjPirWwF9qCYVir1Rr8jshO3R0"; // توکن ربات
$Support = file_get_contents("Support.txt");
$timech = "60";
if (!file_exists("data/$from_id/$from_id.json")){mkdir("data/$from_id");}
$datas = json_decode(file_get_contents("data/$from_id/$from_id.json"),true);
$datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
$coin1 = $datas1["coin"];
$step = $datas["step"];
$inv = $datas["inv"];
$coin = $datas["coin"];
$type = $datas["type"];
$sefaresh = $datas["sefaresh"];
$warn = $datas["warn"];
$timeee = $ttti - 60;
if(is_file("time") or file_get_contents("time") <= $timeee){
	file_put_contents("time",$ttti);
}
if($warn >= 3){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"📍 شما سه اخطار دریافت کردید و از ربات مسدود شدید",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id
]); 
}
$ads = $datas["ads"];
$invcoin = $datas["invcoin"];
$date = date("Y-F-d");
if(file_exists("data/admin2.txt")){
$admin2 = file_get_contents("data/admin2.txt");
}else{
$admin2 = "6196270628";
}
/////////////------//////////
if(file_exists("data/admin3.txt")){
$admin3 = file_get_contents("data/admin3.txt");
}else{
$admin3 = "6196270628";
}
/////////////------//////////
if(file_exists("data/starttext.txt")){
$starttext = file_get_contents("data/starttext.txt");
$starttext = str_replace('NAME',$first,$starttext);
$starttext = str_replace('LAST',$last,$starttext);
$starttext = str_replace('USER',$username,$starttext);
$starttext = str_replace('ID',$from_id,$starttext);
}else{
$starttext = "متن استارت تنظیم نشده است";
}
/////////////////////////---/////
if(file_exists("data/coinamount.txt")){
$coinamount = file_get_contents("data/coinamount.txt");
$coinamount = str_replace('NAME',$first,$coinamount);
}else{
$coinamount = "1";
}
/////////////------//////////
if(file_exists("data/porsant.txt")){
$porsant = file_get_contents("data/porsant.txt");
$porsant = str_replace('NAME',$first,$porsant);
}else{
$porsant = "0.2";
}
/////////////////////////////////////////////---////
if(file_exists("data/joinmcoin.txt")){
$joinmcoin = file_get_contents("data/joinmcoin.txt");
}else{
$joinmcoin = "10";
}
/////////////////////////////////////////////---////
if(file_exists("data/mhiperm.txt")){
$mhiperm = file_get_contents("data/mhiperm.txt");
}else{
$mhiperm = "👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤
👤ممبر بگیر👤";
}
/////////////////////////////////////////////---////
if(file_exists("data/zirtext.txt")){
$idbot = "starkpanelbot";
$zirtext = file_get_contents("data/zirtext.txt");
$zirtext = str_replace('NAME',$first,$zirtext);
$zirtext = str_replace('LAST',$last,$zirtext);
$zirtext = str_replace('LINK',"https://t.me/$idbot?start=$chat_id",$zirtext);
$zirtext = str_replace('ID',$chat_id,$zirtext);
}else{
$zirtext = "متن زیرمجموعه گیری تنظیم نشده است";
}
//////////----------------////////////////////////
if(file_exists("data/almasgett.txt")){
$almasgett = file_get_contents("data/almasgett.txt");
$almasgett = str_replace('NAME',$first,$almasgett);
$almasgett = str_replace('LAST',$last,$almasgett);
$almasgett = str_replace('ID',$chat_id,$almasgett);
}else{
$almasgett = "تنظیم نشده";
}
////////////////----///////////////
if(file_exists("data/ghavanin.txt")){
$ghavanin = file_get_contents("data/ghavanin.txt");
$ghavanin = str_replace('NAME',$first2,$ghavanin);
}else{
$ghavanin = "متن قوانین تنظیم نشده است";
}
///////////////-------///////
if(file_exists("data/invitecoin.txt")){
$invitecoin = file_get_contents("data/invitecoin.txt");
$invitecoin = str_replace('NAME',$first2,$invitecoin);
}else{
$invitecoin = "10";
}
///////////------//////////-----------////////
if(file_exists("data/mmbrsabt1.txt")){
$mmbrsabt1 = file_get_contents("data/mmbrsabt1.txt");
$mmbrsabt1 = str_replace('NAME',$first2,$mmbrsabt1);
}else{
$mmbrsabt1 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt11.txt")){
$mmbrsabt11 = file_get_contents("data/mmbrsabt11.txt");
$mmbrsabt11 = str_replace('NAME',$first2,$mmbrsabt11);
}else{
$mmbrsabt11 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt2.txt")){
$mmbrsabt2 = file_get_contents("data/mmbrsabt2.txt");
$mmbrsabt2 = str_replace('NAME',$first2,$mmbrsabt2);
}else{
$mmbrsabt2 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt22.txt")){
$mmbrsabt22 = file_get_contents("data/mmbrsabt22.txt");
$mmbrsabt22 = str_replace('NAME',$first2,$mmbrsabt22);
}else{
$mmbrsabt22 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt3.txt")){
$mmbrsabt3 = file_get_contents("data/mmbrsabt3.txt");
$mmbrsabt3 = str_replace('NAME',$first2,$mmbrsabt3);
}else{
$mmbrsabt3 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt33.txt")){
$mmbrsabt33 = file_get_contents("data/mmbrsabt33.txt");
$mmbrsabt33 = str_replace('NAME',$first2,$mmbrsabt33);
}else{
$mmbrsabt33 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt78.txt")){
$mmbrsabt78 = file_get_contents("data/mmbrsabt78.txt");
$mmbrsabt78 = str_replace('NAME',$first2,$mmbrsabt78);
}else{
$mmbrsabt78 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt44.txt")){
$mmbrsabt44 = file_get_contents("data/mmbrsabt44.txt");
$mmbrsabt44 = str_replace('NAME',$first2,$mmbrsabt44);
}else{
$mmbrsabt44 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt5.txt")){
$mmbrsabt5 = file_get_contents("data/mmbrsabt5.txt");
$mmbrsabt5 = str_replace('NAME',$first2,$mmbrsabt5);
}else{
$mmbrsabt5 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt55.txt")){
$mmbrsabt55 = file_get_contents("data/mmbrsabt55.txt");
$mmbrsabt55 = str_replace('NAME',$first2,$mmbrsabt55);
}else{
$mmbrsabt55 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt6.txt")){
$mmbrsabt6 = file_get_contents("data/mmbrsabt6.txt");
$mmbrsabt6 = str_replace('NAME',$first2,$mmbrsabt6);
}else{
$mmbrsabt6 = "تنظیم نشده";
}
if(file_exists("data/mmbrsabt98.txt")){
$mmbrsabt98 = file_get_contents("data/mmbrsabt98.txt");
$mmbrsabt98 = str_replace('NAME',$first2,$mmbrsabt98);
}else{
$mmbrsabt98 = "تنظیم نشده";
}
if(file_exists("data/mshopname1.txt")){
$mshopname1 = file_get_contents("data/mshopname1.txt");
}else{
$mshopname1 = "تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/mshopname2.txt")){
$mshopname2 = file_get_contents("data/mshopname2.txt");
}else{
$mshopname2 = "تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/mshopname3.txt")){
$mshopname3 = file_get_contents("data/mshopname3.txt");
}else{
$mshopname3 = "تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/mshopname4.txt")){
$mshopname4 = file_get_contents("data/mshopname4.txt");
}else{
$mshopname4 = "تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/mshopname5.txt")){
$mshopname5 = file_get_contents("data/mshopname5.txt");
}else{
$mshopname5 = "تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/mshopname6.txt")){
$mshopname6 = file_get_contents("data/mshopname6.txt");
}else{
$mshopname6 = "تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/mshoplink.txt")){
$mshoplink = file_get_contents("data/mshoplink.txt");
}else{
$mshoplink = "https://t.me/none";
}
///////////------//////////-----------////////
if(file_exists("data/dok1.txt")){
$dok1 = file_get_contents("data/dok1.txt");
}else{
$dok1 = "💎 عضویت در کانال 💎";
}
///////////------//////////-----------////////
if(file_exists("data/dok2.txt")){
$dok2 = file_get_contents("data/dok2.txt");
}else{
$dok2 = "👤 حساب کاربری";
}
///////////------//////////-----------////////
if(file_exists("data/dok3.txt")){
$dok3 = file_get_contents("data/dok3.txt");
}else{
$dok3 = "❗️❗️قوانین";
}
if(file_exists("data/mdok8.txt")){
$mdok8 = file_get_contents("data/mdok8.txt");
}else{
$mdok8 = "متن راهنما تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/dok547.txt")){
$dok547 = file_get_contents("data/dok547.txt");
}else{
$dok547 = "🔍پیگیری سفارشات🔍";
}
///////////------//////////-----------////////
if(file_exists("data/dok44.txt")){
$dok44 = file_get_contents("data/dok44.txt");
}else{
$dok44 = "📞تماس با ما";
}
///////////------//////////-----------////////
if(file_exists("data/dok0.txt")){
$dok0 = file_get_contents("data/dok0.txt");
}else{
$dok0 = "🔍پیگیری";
}
///////////------//////////-----------////////
if(file_exists("data/dok4.txt")){
$dok4 = file_get_contents("data/dok4.txt");
}else{
$dok4 = "✅سفارش ممبر";
}
///////////------//////////-----------////////
if(file_exists("data/dok5.txt")){
$dok5 = file_get_contents("data/dok5.txt");
}else{
$dok5 = "🛍فروشگاه";
}
///////////------//////////-----------////////
if(file_exists("data/dok6.txt")){
$dok6 = file_get_contents("data/dok6.txt");
}else{
$dok6 = "👥 زیر مجموعه گیری";
}
///////////------//////////-----------////////
if(file_exists("data/dok7.txt")){
$dok7 = file_get_contents("data/dok7.txt");
}else{
$dok7 = "برترین ها🏆";
}
///////////------//////////-----------////////
if(file_exists("data/dok8.txt")){
$dok8 = file_get_contents("data/dok8.txt");
}else{
$dok8 = "❓راهنما";
}
///////////------//////////-----------////////
if(file_exists("data/dok12.txt")){
$dok12 = file_get_contents("data/dok12.txt");
}else{
$dok12 = "🎁کد هدیه";
}
///////////------//////////-----------////////
if(file_exists("data/dok13.txt")){
$dok13 = file_get_contents("data/dok13.txt");
}else{
$dok13 = "🏧انتقال";
}
///////////------//////////-----------////////
if(file_exists("data/dokc1.txt")){
$dokc1 = file_get_contents("data/dokc1.txt");
}else{
$dokc1 = "👤عضویت";
}
///////////------//////////-----------////////
if(file_exists("data/dokc2.txt")){
$dokc2 = file_get_contents("data/dokc2.txt");
}else{
$dokc2 = "✅دریافت الماس";
}
///////////------//////////-----------////////
if(file_exists("data/dokc3.txt")){
$dokc3 = file_get_contents("data/dokc3.txt");
}else{
$dokc3 = "⛔️لغو سفارش";
}
///////////------//////////-----------////////
if(file_exists("data/dokc4.txt")){
$dokc4 = file_get_contents("data/dokc4.txt");
}else{
$dokc4 = "📊پیگیری";
}
///////////------//////////-----------////////
if(file_exists("data/dokc5.txt")){
$dokc5 = file_get_contents("data/dokc5.txt");
}else{
$dokc5 = "🚫گزارش تخلف";
}
///////////------//////////-----------////////
if(file_exists("data/dokc6.txt")){
$dokc6 = file_get_contents("data/dokc6.txt");
}else{
$dokc6 = "🔱ورود به ربات🔱️";
}
///////////------//////////-----------////////
if(file_exists("data/dokday.txt")){
$dokday = file_get_contents("data/dokday.txt");
}else{
$dokday = "💎الماس روزانه";
}
///////////------//////////-----------////////
if(file_exists("data/mdaily.txt")){
$mdaily = file_get_contents("data/mdaily.txt");
}else{
$mdaily = "5";
}
///////////------//////////-----------////////
if(file_exists("data/dokchannel.txt")){
$dokchannel = file_get_contents("data/dokchannel.txt");
}else{
$dokchannel = "👤عضویت در کانال";
}
///////////------//////////-----------////////
if(file_exists("data/dokchannel2.txt")){
$dokchannel2 = file_get_contents("data/dokchannel2.txt");
$dokchannel2 = str_replace('NAME',$first,$dokchannel2);
$dokchannel2 = str_replace('LAST',$last,$dokchannel2);
$dokchannel2 = str_replace('USER',$username,$dokchannel2);
$dokchannel2 = str_replace('ID',$from_id,$dokchannel2);
}else{
$dokchannel2 = "متن جمع آوری در کانال تنظیم نشده است";
}
///////////------//////////-----------////////
if(file_exists("data/piclink.txt")){
$piclink = file_get_contents("data/piclink.txt");
}else{
$piclink = "http://s2.picofile.com/file/8372103468/member_icon_8_jpg.png️";
}
///////////------//////////-----------////////
if(file_exists("data/shoptext.txt")){
$shoptext = file_get_contents("data/shoptext.txt");
$shoptext = str_replace('NAME',$first,$shoptext);
$shoptext = str_replace('LAST',$last,$shoptext);
$idbot = "starkpanelbot";
$shoptext = str_replace('ID',$chat_id,$shoptext);
}else{
$shoptext = "متن فروشگاه تنظیم نشده است";
}
if(file_exists("data/ozvname6.txt")){
$ozvname6 = file_get_contents("data/ozvname6.txt");
$ozvname6 = str_replace('NAME',$first2,$ozvname6);
}else{
$ozvname6 = "تنظیم نشده";
}
if(file_exists("data/ozvname4.txt")){
$ozvname4 = file_get_contents("data/ozvname4.txt");
$ozvname4 = str_replace('NAME',$first2,$ozvname4);
}else{
$ozvname4 = "تنظیم نشده";
}
if(file_exists("data/ozvname5.txt")){
$ozvname5 = file_get_contents("data/ozvname5.txt");
$ozvname5 = str_replace('NAME',$first2,$ozvname5);
}else{
$ozvname5 = "تنظیم نشده";
}
if(file_exists("data/ozvname3.txt")){
$ozvname3 = file_get_contents("data/ozvname3.txt");
$ozvname3 = str_replace('NAME',$first2,$ozvname3);
}else{
$ozvname3 = "تنظیم نشده";
}
if(file_exists("data/ozvname2.txt")){
$ozvname2 = file_get_contents("data/ozvname2.txt");
$ozvname2 = str_replace('NAME',$first2,$ozvname2);
}else{
$ozvname2 = "تنظیم نشده";
}
if(file_exists("data/ozvname.txt")){
$ozvname = file_get_contents("data/ozvname.txt");
$ozvname = str_replace('NAME',$first2,$ozvname);
}else{
$ozvname = "تنظیم نشده";
}
if(file_exists("data/mtzir.txt")){
$mtzir = file_get_contents("data/mtzir.txt");
}else{
$mtzir = "تنظیم نشده";
}
if(file_exists("data/dok2a.txt")){
$dok2a = file_get_contents("data/dok2a.txt");
$dok2a = str_replace('NAME',$first,$dok2a);
$dok2a = str_replace('LAST',$last,$dok2a);
$dok2a = str_replace('USER',$username,$dok2a);
$dok2a = str_replace('ID',$from_id,$dok2a);
$dok2a = str_replace('GEM',$coin,$dok2a);
$dok2a = str_replace('TARIKH',$date,$dok2a);
$dok2a = str_replace('INV',$inv,$dok2a);
$dok2a = str_replace('OZV',$ads,$dok2a);
$dok2a = str_replace('SEF',$sefaresh,$dok2a);
$dok2a = str_replace('POR',$invcoin,$dok2a);
$dok2a = str_replace('banakh',$warn,$dok2a);
}else{
$dok2a = "متن حساب کاربری تنظیم نشده";
}
$sup = "https://t.me/$Support";
$chads = file_get_contents("cht.txt"); //آیدی کانال تبلیغات بدون @
$chor = file_get_contents("data/ch.txt");
$channels = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$chor&user_id=".$from_id or $chatid));
$to = $channels->result->status;
$reply = $update->message->reply_to_message->forward_from->id;
//============================================================================//
//co
if(!empty($from_id) and $text == $dok4 and $tc == 'private'){
$hhhh = explode("\n",file_get_contents("data/$from_id/channels.txt"));
foreach($hhhh as $chaaa){
     if( $chaaa != "" and $chaaa != "raf" and $text == $dok4){
 $channels5555 = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$chaaa&user_id=$from_id"));
 $tod5555 = $channels5555->result->status;
 if($tod5555 != 'member' and $tod5555 != 'creator' and $tod5555 != 'administrator' and $text == $dok4){
   $foiii = file_get_contents("data/$from_id/channels.txt");
   $str = str_replace("$chaaa","raf",$foiii);
   file_put_contents("data/$from_id/channels.txt","$str");
   $hjvhvh = str_replace("@","T.me/",$chaaa);
$newin = $coin -2;
$datas["coin"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
  bot('sendMessage',[
  'chat_id'=>$chat_id,
  'text'=>"💢به دلیل ترک کانال زیر 
$hjvhvh
دو الماس از شما کسر شد"
]);
}
}
}
}
$menu1 = json_encode(['keyboard'=>[
[['text'=>"$dok1"]],
[['text'=>"$dok4"],['text'=>"$dok2"]],
[['text'=>"$dok5"],['text'=>"$dok13"],['text'=>"$dok0"]],
[['text'=>"$dok7"],['text'=>"$dok8"]],
[['text'=>"$dok12"],['text'=>"$dok6"]],
],'resize_keyboard'=>true]);
$menu2 = json_encode(['keyboard'=>[
[['text'=>"✅بلاک و انبلاک⛔️"]],
[['text'=>"📊آمار ربات"],['text'=>"📩بخش پیام"],['text'=>"💠دکمه ها"]],
[['text'=>"⌨️کد هدیه"],['text'=>"🏦مبادلات ربات"],['text'=>"🚀زیرمجموعه گیری"]],
[['text'=>"🆔تنظیم کانال"],['text'=>"🎊پنل ها"],['text'=>"📌ثبت سفارش"]],
[['text'=>"📇تنظیم متن"],['text'=>"🛍فروشگاه"],['text'=>"👤ادمین ها"]],
[['text'=>"🔍پیگیری سفارش"],['text'=>"⚠️اشتراک ربات"],['text'=>"〽️آپدیت ربات"]],
[['text'=>"⭕️راهنما"],['text'=>"❌ایراد ربات"]],
[['text'=>"انصراف"]],
],'resize_keyboard'=>true]);

if(strpos($text == "/start") !== false  and $text !=="/start" and $tc == 'private'){
$id=str_replace("/start ","",$text);
$amar=file_get_contents("data/ozvs.txt");
$exp=explode("\n",$amar);
if(!in_array($from_id,$exp) and $from_id != $id){
if(!is_file("VIP")){
	SM($chat_id);
}
$myfile2 = fopen("data/ozvs.txt", "a") or die("Unable to open file!");
$datas = json_decode(file_get_contents("data/$from_id/$from_id.json"),true);
fwrite($myfile2, "$from_id\n");
fclose($myfile2);
$datas["step"] = "free";
$datas["type"] = "empty";
$datas["inv"] = "0";
$datas["coin"] = "$joinmcoin";
$datas["warn"] = "0";
$datas["ads"] = "0";
$datas["sub"] = "$id";
$datas["invcoin"] = "0";
$datas["panel"] = "free";
$datas["timepanel"] = "null";
$datas['dafeee'] = "first";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$datas12 = json_decode(file_get_contents("data/$id/$id.json"),true);
$invite1 = $datas12["inv"];
settype($invite1,"integer");
$newinvite = $invite1 + 1;
$datas12["inv"] = $newinvite;
$outjson = json_encode($datas12,true);
file_put_contents("data/$id/$id.json",$outjson);
$datas1234 = json_decode(file_get_contents("data/$id/$id.json"),true);
$invite122 = $datas1234["coin"];
settype($invite122,"integer");
$newinvite664 = $invite122 + $invitecoin;
$datas1234["coin"] = $newinvite664;
$outjson = json_encode($datas1234,true);
file_put_contents("data/$id/$id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"☑️🎊یک نفر با لینک شما وارد ربات شد

🎈$invitecoin الماس به حساب شما واریز شد و از هم اکنون $porsant الماس بابت عضویت کاربر در هر کانال به شما تعلق میگیرد
",
'parse_mode'=>"HTML",
]);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$starttext
",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$invitecoin الماس به شما اضافه شد☑️",
'reply_to_message_id'=>$message_id,
]);
}
}
if (!file_exists("data/$from_id/$from_id.json")) {
$myfile2 = fopen("data/ozvs.txt", "a") or die("Unable to open file!");
fwrite($myfile2, "$from_id\n");
fclose($myfile2);
$datas["step"] = "free";
$datas["type"] = "empty";
$datas["inv"] = "0";
$datas["coin"] = "$joinmcoin";
$datas["warn"] = "0";
$datas["ads"] = "0";
$datas["invcoin"] = "0";
$datas["panel"] = "free";
$datas["timepanel"] = "null";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
}
if($text == "/start" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(!is_file("VIP")){
	SM($chat_id);
}
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$starttext
",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
}else{
if(!is_file("VIP")){
	SM($chat_id);
}
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$starttext
",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
}}
if(isset($from_id)){
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$from_id"));
}
else
{
$fromm_id = $update->callback_query->from->id;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$fromm_id"));
}
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
exit();
}
if($text == "انصراف" or $text == "انصراف" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$starttext
",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
}else{
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$starttext
",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
}}
//===hmdemon===//
if($text == "$dok8" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$mdok8",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu1
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$mdok8",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu1
]); 
}}
elseif($text == "$dok6" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$mtzir",
'parse_mode'=>'Markdown', 
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
    [['text'=>"📌دریافت بنر زیرمجموعه گیری", 'callback_data'=> 'zirmoj']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
}
elseif($text == "$dok7" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تمایل به مشاهده برترین کاربران کدام بخش دارید؟",
'parse_mode'=>'Markdown', 
'reply_markup'=>json_encode([ 
'inline_keyboard'=>[
    [['text'=>"👤جذب زیرمجموعه👤", 'callback_data'=> 'barziriz']],
    [['text'=>"عضویت در کانال📢", 'callback_data'=> 'barozvsinza'],['text'=>"📇ثبت سفارش", 'callback_data'=> 'barkosnago']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
}
elseif($data == "barozvsinza"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$vipjoin",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}else{
$views = getRanks("ads");
$user_view_1s = $views[0]['user'];
$mojodi_view_1s = $views[0]['coins'];
$user_view_2s = $views[1]['user'];
$mojodi_view_2s = $views[1]['coins'];
$user_view_3s = $views[2]['user'];
$mojodi_view_3s = $views[2]['coins'];
$user_view_4s = $views[3]['user'];
$mojodi_view_4s = $views[3]['coins'];
$user_view_5s = $views[4]['user'];
$mojodi_view_5s = $views[4]['coins'];
$user_view_6s = $views[5]['user'];
$mojodi_view_6s = $views[5]['coins'];
$user_view_7s = $views[6]['user'];
$mojodi_view_7s = $views[6]['coins'];
$user_view_8s = $views[7]['user'];
$mojodi_view_8s = $views[7]['coins'];
$user_view_9s = $views[8]['user'];
$mojodi_view_9s = $views[8]['coins'];
$user_view_10s = $views[9]['user'];
$mojodi_view_10s = $views[9]['coins'];
SendMessage("$chat_id","
🏆 برترین کاربران عضویت کانال 🏆


🥇نـفـر اول  
♾ شماره کاربری : $user_view_1s
👥تعداد عضویت : $mojodi_view_1s

🥈نـفـر دوم 
♾ شماره کاربری : $user_view_2s
👥تعداد عضویت : $mojodi_view_2s

🥉نـفـر سـوم 
♾ شماره کاربری : $user_view_3s
👥تعداد عضویت : $mojodi_view_3s

🏅نفر چهارم 
♾ شماره کاربری : $user_view_4s
👥تعداد عضویت : $mojodi_view_4s

🏅نفر پنجم 
♾ شماره کاربری : $user_view_5s
👥تعداد عضویت : $mojodi_view_5s

🏅نفر ششم 
♾ شماره کاربری : $user_view_6s
👥تعداد عضویت : $mojodi_view_6s

🏅نفر هفتم 
♾ شماره کاربری : $user_view_7s
👥تعداد عضویت : $mojodi_view_7s

🏅نفر هشتم 
♾ شماره کاربری : $user_view_8s
👥تعداد عضویت : $mojodi_view_8s

🏅نفر نهم 
♾ شماره کاربری : $user_view_9s
👥تعداد عضویت : $mojodi_view_9s

🏅نفر دهم 
♾ شماره کاربری : $user_view_10s
👥تعداد عضویت : $mojodi_view_10s


");}}
elseif($data == "barkosnago"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$vipjoin",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}else{
$views = getRanks("sefaresh");
$user_view_11s = $views[0]['user'];
$mojodi_view_11s = $views[0]['coins'];
$user_view_22s = $views[1]['user'];
$mojodi_view_22s = $views[1]['coins'];
$user_view_33s = $views[2]['user'];
$mojodi_view_33s = $views[2]['coins'];
$user_view_44s = $views[3]['user'];
$mojodi_view_44s = $views[3]['coins'];
$user_view_55s = $views[4]['user'];
$mojodi_view_55s = $views[4]['coins'];
$user_view_66s = $views[5]['user'];
$mojodi_view_66s = $views[5]['coins'];
$user_view_77s = $views[6]['user'];
$mojodi_view_77s = $views[6]['coins'];
$user_view_88s = $views[7]['user'];
$mojodi_view_88s = $views[7]['coins'];
$user_view_99s = $views[8]['user'];
$mojodi_view_99s = $views[8]['coins'];
$user_view_1010s = $views[9]['user'];
$mojodi_view_1010s = $views[9]['coins'];
SendMessage("$chat_id","
🏆 برترین کاربران در ثبت سفارش 🏆

🥇نـفـر اول  
♾ شماره کاربری : $user_view_11s
📇تعداد ثبت سفارش : $mojodi_view_11s

🥈نـفـر دوم 
♾ شماره کاربری : $user_view_22s
📇تعداد ثبت سفارش : $mojodi_view_22s

🥉نـفـر سـوم 
♾ شماره کاربری : $user_view_33s
📇تعداد ثبت سفارش : $mojodi_view_33s

🏅نفر چهارم 
♾ شماره کاربری : $user_view_44s
📇تعداد ثبت سفارش : $mojodi_view_44s

🏅نفر پنجم 
♾ شماره کاربری : $user_view_55s
📇تعداد ثبت سفارش : $mojodi_view_55s

🏅نفر ششم 
♾ شماره کاربری : $user_view_66s
📇تعداد ثبت سفارش : $mojodi_view_66s

🏅نفر هفتم 
♾ شماره کاربری : $user_view_77s
📇تعداد ثبت سفارش : $mojodi_view_77s

🏅نفر هشتم 
♾ شماره کاربری : $user_view_88s
📇تعداد ثبت سفارش : $mojodi_view_88s

🏅نفر نهم 
♾ شماره کاربری : $user_view_99s
📇تعداد ثبت سفارش : $mojodi_view_99s

🏅نفر دهم 
♾ شماره کاربری : $user_view_1010s
📇تعداد ثبت سفارش :$mojodi_view_1010s
    
    ");}}
elseif($data == "barziriz"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$vipjoin",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}else{
$views = getRanks("inv");
$user_view_1 = $views[0]['user'];
$mojodi_view_1 = $views[0]['coins'];
$user_view_2 = $views[1]['user'];
$mojodi_view_2 = $views[1]['coins'];
$user_view_3 = $views[2]['user'];
$mojodi_view_3 = $views[2]['coins'];
$user_view_4 = $views[3]['user'];
$mojodi_view_4 = $views[3]['coins'];
$user_view_5 = $views[4]['user'];
$mojodi_view_5 = $views[4]['coins'];
$user_view_6 = $views[5]['user'];
$mojodi_view_6 = $views[5]['coins'];
$user_view_7 = $views[6]['user'];
$mojodi_view_7 = $views[6]['coins'];
$user_view_8 = $views[7]['user'];
$mojodi_view_8 = $views[7]['coins'];
$user_view_9 = $views[8]['user'];
$mojodi_view_9 = $views[8]['coins'];
$user_view_10 = $views[9]['user'];
$mojodi_view_10 = $views[9]['coins'];
SendMessage("$chat_id","
🏆 برترین کاربران در زیرمجموعه گیری 🏆

🥇نـفـر اول  
♾ شماره کاربری : $user_view_1
👤 تعداد جذب زیر مجموعه : $mojodi_view_1

🥈نـفـر دوم 
♾ شماره کاربری : $user_view_2
👤 تعداد جذب زیر مجموعه : $mojodi_view_2

🥉نـفـر سـوم 
♾ شماره کاربری : $user_view_3
👤 تعداد جذب زیر مجموعه : $mojodi_view_3

🏅نفر چهارم 
♾ شماره کاربری : $user_view_4
👤 تعداد جذب زیر مجموعه : $mojodi_view_4

🏅نفر پنجم 
♾ شماره کاربری : $user_view_5
👤 تعداد جذب زیر مجموعه : $mojodi_view_5

🏅نفر ششم 
♾ شماره کاربری : $user_view_6
👤 تعداد جذب زیر مجموعه : $mojodi_view_6

🏅نفر هفتم 
♾ شماره کاربری : $user_view_7
👤 تعداد جذب زیر مجموعه : $mojodi_view_7

🏅نفر هشتم 
♾ شماره کاربری : $user_view_8
👤 تعداد جذب زیر مجموعه : $mojodi_view_8

🏅نفر نهم 
♾ شماره کاربری : $user_view_9
👤 تعداد جذب زیر مجموعه : $mojodi_view_9

🏅نفر دهم 
♾ شماره کاربری : $user_view_10
👤 تعداد جذب زیر مجموعه : $mojodi_view_10

");}}
elseif($data == "barkosnago"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$vipjoin",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}else{
$views = getRanks("sefaresh");
$user_view_11s = $views[0]['user'];
$mojodi_view_11s = $views[0]['coins'];
$user_view_22s = $views[1]['user'];
$mojodi_view_22s = $views[1]['coins'];
$user_view_33s = $views[2]['user'];
$mojodi_view_33s = $views[2]['coins'];
$user_view_44s = $views[3]['user'];
$mojodi_view_44s = $views[3]['coins'];
$user_view_55s = $views[4]['user'];
$mojodi_view_55s = $views[4]['coins'];
$user_view_66s = $views[5]['user'];
$mojodi_view_66s = $views[5]['coins'];
$user_view_77s = $views[6]['user'];
$mojodi_view_77s = $views[6]['coins'];
$user_view_88s = $views[7]['user'];
$mojodi_view_88s = $views[7]['coins'];
$user_view_99s = $views[8]['user'];
$mojodi_view_99s = $views[8]['coins'];
$user_view_1010s = $views[9]['user'];
$mojodi_view_1010s = $views[9]['coins'];
SendMessage("$chat_id","
🏆 برترین کاربران در ثبت سفارش 🏆

🥇نـفـر اول  
♾ شماره کاربری : $user_view_11s
📇تعداد ثبت سفارش : $mojodi_view_11s

🥈نـفـر دوم 
♾ شماره کاربری : $user_view_22s
📇تعداد ثبت سفارش : $mojodi_view_22s

🥉نـفـر سـوم 
♾ شماره کاربری : $user_view_33s
📇تعداد ثبت سفارش : $mojodi_view_33s

🏅نفر چهارم 
♾ شماره کاربری : $user_view_44s
📇تعداد ثبت سفارش : $mojodi_view_44s

🏅نفر پنجم 
♾ شماره کاربری : $user_view_55s
📇تعداد ثبت سفارش : $mojodi_view_55s

🏅نفر ششم 
♾ شماره کاربری : $user_view_66s
📇تعداد ثبت سفارش : $mojodi_view_66s

🏅نفر هفتم 
♾ شماره کاربری : $user_view_77s
📇تعداد ثبت سفارش : $mojodi_view_77s

🏅نفر هشتم 
♾ شماره کاربری : $user_view_88s
📇تعداد ثبت سفارش : $mojodi_view_88s

🏅نفر نهم 
♾ شماره کاربری : $user_view_99s
📇تعداد ثبت سفارش : $mojodi_view_99s

🏅نفر دهم 
♾ شماره کاربری : $user_view_1010s
📇تعداد ثبت سفارش :$mojodi_view_1010s
    
    ");}}
elseif($data == "barozvsinza"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$vipjoin",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}else{
$views = getRanks("ads");
$user_view_1s = $views[0]['user'];
$mojodi_view_1s = $views[0]['coins'];
$user_view_2s = $views[1]['user'];
$mojodi_view_2s = $views[1]['coins'];
$user_view_3s = $views[2]['user'];
$mojodi_view_3s = $views[2]['coins'];
$user_view_4s = $views[3]['user'];
$mojodi_view_4s = $views[3]['coins'];
$user_view_5s = $views[4]['user'];
$mojodi_view_5s = $views[4]['coins'];
$user_view_6s = $views[5]['user'];
$mojodi_view_6s = $views[5]['coins'];
$user_view_7s = $views[6]['user'];
$mojodi_view_7s = $views[6]['coins'];
$user_view_8s = $views[7]['user'];
$mojodi_view_8s = $views[7]['coins'];
$user_view_9s = $views[8]['user'];
$mojodi_view_9s = $views[8]['coins'];
$user_view_10s = $views[9]['user'];
$mojodi_view_10s = $views[9]['coins'];
SendMessage("$chat_id","
🏆 برترین کاربران عضویت کانال 🏆


🥇نـفـر اول  
♾ شماره کاربری : $user_view_1s
👥تعداد عضویت : $mojodi_view_1s

🥈نـفـر دوم 
♾ شماره کاربری : $user_view_2s
👥تعداد عضویت : $mojodi_view_2s

🥉نـفـر سـوم 
♾ شماره کاربری : $user_view_3s
👥تعداد عضویت : $mojodi_view_3s

🏅نفر چهارم 
♾ شماره کاربری : $user_view_4s
👥تعداد عضویت : $mojodi_view_4s

🏅نفر پنجم 
♾ شماره کاربری : $user_view_5s
👥تعداد عضویت : $mojodi_view_5s

🏅نفر ششم 
♾ شماره کاربری : $user_view_6s
👥تعداد عضویت : $mojodi_view_6s

🏅نفر هفتم 
♾ شماره کاربری : $user_view_7s
👥تعداد عضویت : $mojodi_view_7s

🏅نفر هشتم 
♾ شماره کاربری : $user_view_8s
👥تعداد عضویت : $mojodi_view_8s

🏅نفر نهم 
♾ شماره کاربری : $user_view_9s
👥تعداد عضویت : $mojodi_view_9s

🏅نفر دهم 
♾ شماره کاربری : $user_view_10s
👥تعداد عضویت : $mojodi_view_10s


");}}
if($text == "/creator" and $tc == 'private'){
	$creator = file_get_contents("../../creator.txt");
	SendMessage($chat_id,$creator);
}
if($text == "$dok1" and $tc == 'private'){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$almasgett",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text' => "$dokchannel", 'url' => "https://t.me/$chads"],['text'=>"$dokday", 'callback_data'=> 'dokrozam']],
]
])
]);
}
//----------------------------
if($text == "$dok3" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$ghavanin",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"$ghavanin",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]); 
}}
elseif($data == "dokrozam"){
$chat_id = $update->callback_query->message->chat->id;
$lasttime = file_get_contents("data/$from_id/time.txt");
if($date == $lasttime){
$lasttime = file_get_contents("data/$from_id/time.txt");
SendMessage($chat_id,"❌شما امتیاز امروز خود را دریافت کرده اید");
}else{
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$datas = json_decode(file_get_contents("data/$from_id/$from_id.json"),true);
$inv = $datas["coin"];
$newin = $inv + $mdaily;
$datas["coin"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/$from_id/time.txt",$date);
SendMessage($chat_id,"✅با موفقیت $mdaily به موجودی شما اضافه شد.");
}}
if($text == "$dok5" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"
$shoptext
",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text' => "$mshopname1", 'url' => "$mshoplink/$shopf1"],['text' => "$mshopname2", 'url' => "$mshoplink/$shopf2"]],
[['text' => "$mshopname3", 'url' => "$mshoplink/$shopf3"],['text' => "$mshopname4", 'url' => "$mshoplink/$shopf4"]],
[['text' => "$mshopname5", 'url' => "$mshoplink/$shopf5"],['text' => "$mshopname6", 'url' => "$mshoplink/$shopf6"]],
]
])
]);
}
elseif($text == "🚀زیرمجموعه گیری" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به بخش تنظیم زیرمجموعه خوش اومدی",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
            [['text'=>"👤تنظیمات زیرمجموعه گیری👤", 'callback_data'=> 'none']],
    [['text'=>"$invitecoin", 'callback_data'=> 'invzir'],['text'=>"✅الماس زیرمجموعه", 'callback_data'=> 'none']],
        [['text'=>"$porsant", 'callback_data'=> 'porzir'],['text'=>"✅پورسانت زیرمجموعه", 'callback_data'=> 'none']],
                [['text'=>"🔵عکس بنر", 'callback_data'=> 'axb'],['text'=>"🔴متن بنر", 'callback_data'=> 'mtb']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($text == "📌ثبت سفارش" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به بخش تنظیم سفارشات خوش اومدی",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
    [['text'=>"👤نام پلن ها", 'callback_data'=> 'sinzanos'],['text'=>"🔍تعداد اعضا️",'callback_data'=> 'sinzanos'],['text'=>"💎الماس مورد نیاز",'callback_data'=> 'sinzanos']],
    [['text'=>"$ozvname", 'callback_data'=> 'ozvname1'],['text'=>"$mmbrsabt1", 'callback_data'=> 'ozvte1'],['text'=>"$mmbrsabt11", 'callback_data'=> 'almasni1']],
        [['text'=>"$ozvname2", 'callback_data'=> 'ozvname2'],['text'=>"$mmbrsabt2", 'callback_data'=> 'ozvte2'],['text'=>"$mmbrsabt22", 'callback_data'=> 'almasni2']],
            [['text'=>"$ozvname3", 'callback_data'=> 'ozvname3'],['text'=>"$mmbrsabt3", 'callback_data'=> 'ozvte3'],['text'=>"$mmbrsabt33", 'callback_data'=> 'almasni3']],
                [['text'=>"$ozvname4", 'callback_data'=> 'ozvname4'],['text'=>"$mmbrsabt78", 'callback_data'=> 'ozvte4'],['text'=>"$mmbrsabt44", 'callback_data'=> 'almasni4']],
                    [['text'=>"$ozvname5", 'callback_data'=> 'ozvname5'],['text'=>"$mmbrsabt5", 'callback_data'=> 'ozvte5'],['text'=>"$mmbrsabt55", 'callback_data'=> 'almasni5']],
                        [['text'=>"$ozvname6", 'callback_data'=> 'ozvname6'],['text'=>"$mmbrsabt6", 'callback_data'=> 'ozvte6'],['text'=>"$mmbrsabt98", 'callback_data'=> 'almasni6']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($data == "almasni1"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh11";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان $almasbot لازم برای سفارش ممبر پلان 1 را ارسال نمایید

میزان فعلی : $mmbrsabt11",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh11" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt11.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "almasni2"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh22";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان $almasbot لازم برای سفارش ممبر پلان 2 را ارسال نمایید

میزان فعلی : $mmbrsabt22",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh22" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt22.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}

elseif($data == "almasni3"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh33";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان $almasbot لازم برای سفارش ممبر پلان 3 را ارسال نمایید

میزان فعلی : $mmbrsabt33",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh33" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt33.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "almasni4"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh44";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان $almasbot لازم برای سفارش ممبر پلان 4 را ارسال نمایید

میزان فعلی : $mmbrsabt44",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh44" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt44.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "almasni5"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh55";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان $almasbot لازم برای سفارش ممبر پلان 5 را ارسال نمایید

میزان فعلی : $mmbrsabt55",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh55" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt55.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "almasni6"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh66";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان $almasbot لازم برای سفارش ممبر پلان 6 را ارسال نمایید

میزان فعلی : $mmbrsabt98",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh66" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt98.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvte1"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh1";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان عضویت ممبر پلان اول را ارسال نمایید

میزان فعلی : $mmbrsabt1",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh1" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt1.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvte2"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان عضویت ممبر پلان دوم را ارسال نمایید

میزان فعلی : $mmbrsabt2",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh2" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt2.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}

elseif($data == "ozvte3"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh3";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان عضویت ممبر پلان سوم را ارسال نمایید

میزان فعلی : $mmbrsabt3",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh3" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt3.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
if($text == "⚠️اشتراک ربات" and $tc == 'private'){
    if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
bot('sendMessage',[
'chat_id'=>$chat_id,
 'text'=>"نامحدود...",
]);
}}
if($text == "⭕️راهنما" and $tc == 'private'){
    if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
bot('sendMessage',[
'chat_id'=>$chat_id,
 'text'=>"✅تمامی قابلیت های ربات بصورت واضح هست و نیازی به راهنمایی نیست.

⛔️درصورت داشتن هرگونه مشکل به رباتساز مورد نظر مراجعه کنید.",
]);
}}
if($text == "❌ایراد ربات" and $tc == 'private'){
    if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
bot('sendMessage',[
'chat_id'=>$chat_id,
 'text'=>"✅لطفا درصورت هر گونه مشکل در ربات یا باگ ..... 
💎حتما به پشتیبانی رباتساز مراجعه کنید و سپس مشکل ربات را بگویید.

🎈با تشکر از همراهی شما",
]);
}}
elseif($text == "〽️آپدیت ربات" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"این بخش به دلایل امنیتی برای مدتی خاموش است⚠️
",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($text == "🔍پیگیری سفارش" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"📉جهت دیدن پیگیری سفارشی کافیست رو دکمه گزارش کلیک کنید و تمام اطلاعات سفارش برایتان ارسال میشود
و درصورتی که برایتان ارسال نمیشود میتوانید با دکمه پیگیری ان سفارش را پیگیری نمایید دوست عزیز.
",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvte4"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh4";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان عضویت ممبر پلان چهارم را ارسال نمایید

میزان فعلی : $mmbrsabt78",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh4" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt78.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}

elseif($data == "ozvte5"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh5";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان عضویت ممبر پلان پنجم را ارسال نمایید

میزان فعلی : $mmbrsabt5",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh5" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt5.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}

elseif($data == "ozvte6"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "psefatesh6";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"میزان عضویت ممبر پلان ششم را ارسال نمایید

میزان فعلی : $mmbrsabt6",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "psefatesh6" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mmbrsabt6.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvname6"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "ozvname6";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفاً نام بخش سفارش پلن 6 را ارسال کنید 🙏🏻

نمونه = سفارش 25 ممبر👤
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "ozvname6" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ozvname6.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvname5"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "ozvname5";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفاً نام بخش سفارش پلن 5 را ارسال کنید 🙏🏻

نمونه = سفارش 25 ممبر👤
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "ozvname5" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ozvname5.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvname4"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "ozvname4";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفاً نام بخش سفارش پلن 4 را ارسال کنید 🙏🏻

نمونه = سفارش 25 ممبر👤
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "ozvname4" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ozvname4.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvname3"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "ozvname3";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفاً نام بخش سفارش پلن 3 را ارسال کنید 🙏🏻

نمونه = سفارش 25 ممبر👤
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "ozvname3" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ozvname3.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvname2"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "ozvname2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفاً نام بخش سفارش پلن 2 را ارسال کنید 🙏🏻

نمونه = سفارش 25 ممبر👤
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "ozvname2" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ozvname2.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($data == "ozvname1"){
$chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "ozvname";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفاً نام بخش سفارش پلن 1 را ارسال کنید 🙏🏻

نمونه = سفارش 25 ممبر👤
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "ozvname" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ozvname.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
elseif($text == "🏦مبادلات ربات" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به قسمت مبادلات ربات خوش اومدید",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
            [['text'=>"🔴بخش اهدا و کسر🔵", 'callback_data'=> 'none']],
    [['text'=>"✅اهدا", 'callback_data'=> 'ehdase'],['text'=>"⭐️الماس همگانی", 'callback_data'=> 'hamse'],['text'=>"⛔️کسر", 'callback_data'=> 'kase']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($text == "🎊پنل ها" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به بخش پنل ها خوش اومدی.",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
            [['text'=>"🔥پورسانت دهی ربات شما🔥", 'callback_data'=> 'none']],
    [['text'=>"$coinamount", 'callback_data'=> 'cozv'],['text'=>"1️⃣الماس عضویت ", 'callback_data'=> 'none']],
        [['text'=>"$joinmcoin", 'callback_data'=> 'jozv'],['text'=>"2️⃣الماس ورودی ", 'callback_data'=> 'none']],
            [['text'=>"$mdaily", 'callback_data'=> 'rozv'],['text'=>"3️⃣الماس روزانه ", 'callback_data'=> 'none']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($text == "🆔تنظیم کانال" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به بخش تنظیم کانال خوش اومدی.",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
            [['text'=>"🔔تنظیم کانال ها🔔", 'callback_data'=> 'none']],
    [['text'=>"📢اطلاع رسانی", 'callback_data'=> 'etoz'],['text'=>"📣تبلیغات", 'callback_data'=> 'taoz']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($text == "📩بخش پیام" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
    $alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"✉️به بخش ارسال پیام خوش اومدید.",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
            [['text'=>"🔔پیام به کاربر🔔", 'callback_data'=> 'pmkar']],
    [['text'=>"📢پیام همگانی", 'callback_data'=> 'pmhamg'],['text'=>"📣فروارد همگانی", 'callback_data'=> 'forhamg']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($data == "pmhamg"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "send2all";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"پیام خود رو بفرست",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"/panel"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}

elseif($step == "send2all" and $text != "/panel" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$all_member = fopen( "data/ozvs.txt", 'r');
while( !feof( $all_member)) {
$user = fgets( $all_member);
bot('sendMessage',[
'chat_id'=>$user,
'text'=>$text,
'parse_mode'=>"MarkDown",
]);
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"پیام به همه ارسال شد",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}

elseif($data == "chatabli"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "setsht";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی کانال تبلیغات رو بدون @ ارسال نمایید.",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"/panel"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
}
}
elseif($text != "/panel" and $step == "setsht" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("cht.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی کانال تبلیغات با موفقیت @$text تنظیم شد.",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]);
}
}
elseif($data == "forhamg"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "f2all";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"پیام خودت رو فور بده اینجا",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"/panel"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}
    
}
elseif($text != "/panel" and $step == "f2all" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$all_member = fopen( "data/ozvs.txt", 'r');
while( !feof( $all_member)) {
$user = fgets( $all_member);
bot('ForwardMessage',[
'chat_id'=>$user,
'from_chat_id'=>$chat_id,
'message_id'=>$message_id
]);
}    
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"فروارد همگانی به همه اعضای ربات فروارد شد",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]);
}}
if($text == "$dok4" and $tc == 'private'){
$truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}else{
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"❓مقدار ممبر درخواستی خود را انتخاب کنید",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[
['text' => "$ozvname", 'callback_data' => "seen$mmbrsabt1"],['text' => "$ozvname2", 'callback_data' => "seen$mmbrsabt2"]
                    ],
[
['text' => "$ozvname3", 'callback_data' => "seen$mmbrsabt3"],['text' => "$ozvname4", 'callback_data' => "seen$mmbrsabt78"]
                    ],
                    [
['text' => "$ozvname5", 'callback_data' => "seen$mmbrsabt5"],
                    
['text' => "$ozvname6", 'callback_data' => "seen$mmbrsabt6"]],
                    ],


])
]);
}
}
if ($data == "seen$mmbrsabt3") {
$datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
$datas1["ted"] = "$mmbrsabt3";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/sabtkasr.txt",$mmbrsabt33);
file_put_contents("data/$chatid/$chatid.json",$outjson);
$in = $datas1["coin"];
if ($in >= "$mmbrsabt33") {
$datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen$mmbrsabt4") {
$datas1["ted"] = "$mmbrsabt4";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/sabtkasr.txt",$mmbrsabt44);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >= "$mmbrsabt44") {
            $datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen$mmbrsabt2") {
$datas1["ted"] = "$mmbrsabt2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/sabtkasr.txt",$mmbrsabt22);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >= "$mmbrsabt22") {
            $datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen210") {
$datas1["ted"] = "210";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >= "210") {
            $datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen$mmbrsabt1") {
$datas1["ted"] = "$mmbrsabt1";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/sabtkasr.txt",$mmbrsabt11);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >= "$mmbrsabt11") {
            $datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen$mmbrsabt5") {
$datas1["ted"] = "$mmbrsabt5";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/sabtkasr.txt",$mmbrsabt55);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >= "$mmbrsabt55") {
$datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen$mmbrsabt6") {
$datas1["ted"] = "$mmbrsabt6";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/sabtkasr.txt",$mmbrsabt66);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >= "$mmbrsabt66") {
$datas1["step"] = "seen2";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        } else {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید ️الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        }
    } if ($data == "seen300") {
$datas1["ted"] = "200";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
        $datas1 = json_decode(file_get_contents("data/$chatid/$chatid.json"),true);
        $in = $datas1["coin"];
        if ($in >399) {
             bot('editmessagetext', [
                'chat_id' => $chatid,
                'message_id' => $message_id2,
                'text' => "تعداد الماس های شما جهت سفارش کافی نیست💢",
                'reply_markup' => json_encode([
                 "resize_keyboard"=>true,'one_time_keyboard' => true,
                'inline_keyboard' => [
                            [
            ['text' => "خرید الماس", 'callback_data' => "buycoin"]
                            ],
                        ]
                    ])
            ]);
        } else {
            $datas1["step"] = "seen2";
$outjson54522 = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson54522);
deletemessage($chatid, $message_id2);
bot('sendMessage', [
'chat_id' => $chatid,
'text' => "💎 جهت سفارش لطفا ایدی کانال خودت رو بدون @ ارسال نمایید

حتما ربات باید ادمین کانال شما باشه🎈‼️",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
        }
    }
if ($step == "seen2" and $text != "انصراف") {
$channels255 = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$text&user_id=1618369374"));
$channels2553 = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChat?chat_id=@$text"));
$tod = $channels255->result->status;
$descch1 = $channels2553->result->title;
$descch2 = $channels2553->result->username;
$descch3 = $channels2553->result->id;
$descch4 = $channels2553->result->description;
$descch5 = $channels2553->result->photo->big_file_id;
if(isset($channels2553->result->photo)){
	$patch = bot('getfile',['file_id'=>$descch5])->result->file_path;
	$piclink = file_get_contents("https://api.telegram.org/file/bot$token/$patch");
	file_put_contents("$text.jpg",$piclink);
}
if(!is_file("ads/cont/$descch2.txt")){
if($tod == 'administrator'){
if(isset($channels2553->result->photo)){
$post_id = bot('SendPhoto', [
'chat_id' =>"@$chads", 
'photo' =>new CURLFile("$text.jpg"),
'caption' => "🌈👤سفارش جدید عضویت👤🌈
====================
📣نام کانال
[$descch1]
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
📙توضیحات
[$descch4]
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
📋آیدی
[@$descch2]
====================
💎جهت دریافت الماس ابتدا $dokc1 را بزنید و پس از عضویت $dokc2 را انتخاب کنید",
'parse_mode' => "html",
'reply_markup' => json_encode([
'inline_keyboard' => [
[
["text" => "$dokc1","url" => "https://t.me/$descch2"]
],
[
["text" => "$dokc2", 'callback_data' => "getcoin-$descch2"],["text" => "$dokc3", 'callback_data' => "cancel-$descch2"]
],
[
["text" => "$dokc4", 'callback_data' => "pay-$descch2"],["text" => "$dokc5", 'callback_data' => "goz-$descch2"]
],
[
["text" => "$dokc6", 'url' => "https://t.me/starkpanelbot"]
],
]
])
])->result->message_id;
unlink("$text.jpg");
}else{
$post_id = bot('SendPhoto', [
'chat_id' =>"@$chads", 
'photo' =>"$piclink",
'caption' => "🌈👤سفارش جدید عضویت👤🌈
====================
📣نام کانال
[$descch1]
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
📙توضیحات
[$descch4]
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
📋آیدی
[@$descch2]
====================
💎جهت دریافت الماس ابتدا $dokc1 را بزنید و پس از عضویت $dokc2 را انتخاب کنید",
'parse_mode' => "html",
'reply_markup' => json_encode([
'inline_keyboard' => [
[
["text" => "$dokc1","url" => "https://t.me/$descch2"]
],
[
["text" => "$dokc2", 'callback_data' => "getcoin-$descch2"],["text" => "$dokc3", 'callback_data' => "cancel-$descch2"]
],
[
["text" => "$dokc4", 'callback_data' => "pay-$descch2"],["text" => "$dokc5", 'callback_data' => "goz-$descch2"]
],
[
["text" => "$dokc6", 'url' => "https://t.me/starkpanelbot"]
],
]
])
])->result->message_id;
}
$al = $datas["ted"];
$sabtkasr = file_get_contents("data/$chat_id/sabtkasr.txt");
$getsho = $coin - $sabtkasr;
$datas["coin"] = "$getsho";
$nu = $sefaresh + 1;
$datas["sefaresh"] = "$nu";
$outjson845 = json_encode($datas,true);
file_put_contents("data/$chat_id/$chat_id.json",$outjson845);
file_put_contents("ads/postid/$descch2.txt", $post_id);
file_put_contents("ads/cont/$descch2.txt",$al);
file_put_contents("ads/admin/$descch2.txt",$chat_id);
file_put_contents("ads/seen/$descch2.txt","0");
file_put_contents("ads/user/$descch2.txt","");
$datas["step"] = "free";
$outjson9415 = json_encode($datas,true);
file_put_contents("data/$chat_id/$chat_id.json",$outjson9415);
$done = file_get_contents("data/done.txt");
$addre = $done + 1;
file_put_contents("data/done.txt", $addre);
bot('sendMessage', [
'chat_id' => $chat_id,
'text' => "✅کانال @$descch2 با موفقیت در کانال ممبرگیر ثبت شد

👇🏻پیگیری وضعیت در👇🏻
@$chads
⚙️⚙️⚙️⚙️⚙️⚙️⚙️⚙️",
'parse_mode' => "html"
]);
        } else {
$datas["step"] = "free";
$outjson45 = json_encode($datas,true);
file_put_contents("data/$chat_id/$chat_id.json",$outjson45);
bot('sendMessage', [
'chat_id' => $chat_id,
'text' => "❌ربات در کانال شما ادمین نمی باشد

لطفا ابتدا ربات را در کانال خود ادمین کنید و سپس مجدد تلاش نمایید",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]);
}
}else{
$datas["step"] = "free";
$outjson453 = json_encode($datas,true);
file_put_contents("data/$chat_id/$chat_id.json",$outjson453);
bot('sendMessage', [
'chat_id' => $chat_id,
'text' => "💢 شما یک سفارش فعال برای $descch2 دارید

لطفا تا اتمام سفارش صبور باشید و دیگر اقدام به سفارش برای این کانال نکنید💡",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]);
}
}
if (strpos($data, "getcoin-") !== false) {
$newd = str_replace("getcoin-",'',$data);
$fromm_id = $update->callback_query->from->id;
@$ue = file_get_contents("ads/user/$newd.txt");
@$se = file_get_contents("ads/seen/$newd.txt");
$get = bot('getChatMember',[
'chat_id'=>'@'.$newd,
'user_id'=>$fromm_id
]);
if($get->result->status == 'administrator' or $get->result->status == 'creator'){
	bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "💢 شما نمیتوانید از سفارش خود الماس دریافت نمایید",
'show_alert' => false
]);
die();
}else{
if (strpos($ue, "$fromm_id") !== false) {
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "❌ شما قبلا از این سفارش الماس گرفته اید",
'show_alert' => false
]);
} else {
// برسی ادمین بودن ربات
$channels23 = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$newd&user_id=1618369374"));
$tod3 = $channels23->result->status;
if($tod3 != 'administrator'){
$end = file_get_contents("ads/seen/$newd.txt");
$ad = file_get_contents("ads/admin/$newd.txt");
$co = file_get_contents("ads/cont/$newd.txt");
$te = file_get_contents("ads/time/$newd.txt");
$de = file_get_contents("ads/date/$newd.txt");
bot('sendMessage', [
'chat_id' => $ad,
'text'=>"❎سفارش شما لغو شد❎
❌💢شما ربات را از ادمین بودن کانال خود خارج کرده اید

آیدی کانال✍🏻 : @$newd
تعداد ممبر درخواستی👥 : $co
تعداد ممبر های دریافتی👤 : $co",
'parse_mode' =>"html",
]);
@$don = file_get_contents("data/done.txt");
$getdon = $don + 1;
file_put_contents("data/done.txt", $getdon);
@$enn = file_get_contents("data/enf.txt");
$getenf = $enn + 1;
file_put_contents("data/enf.txt", $getenf);
$post_id = file_get_contents("ads/postid/$newd.txt");
$de = $newd + 1;
bot('deletemessage', [
'chat_id' =>"@$chads",
'message_id' => $post_id
]);
unlink("ads/seen/$newd.txt");
unlink("ads/admin/$newd.txt");
unlink("ads/cont/$newd.txt");
unlink("ads/time/$newd.txt");
unlink("ads/user/$newd.txt");
unlink("ads/date/$newd.txt");
unlink("ads/postid/$newd.txt");
die();
}
// برسی ادمین بودن ربات
$channels23 = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$newd&user_id=".$fromm_id));
$tod3 = $channels23->result->status;
$channels231 = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=@$chads&user_id=".$fromm_id));
$tod31 = $channels231->result->status;
if($tod3 == 'member' or $tod3 == 'creator' or $tod3 == 'administrator'){
if($tod31 == 'member' or $tod31 == 'creator' or $tod31 == 'administrator'){
$user = file_get_contents("ads/user/$newd.txt");
$members = explode("\n", $user);
if (!in_array($fromm_id, $members)) {
$add_user = file_get_contents("ads/user/$newd.txt");
$add_user .= $fromm_id . "\n";
file_put_contents("ads/user/$newd.txt", $add_user);
}
$getse = $se + 1;
file_put_contents("ads/seen/$newd.txt", $getse);
$datas3165 = json_decode(file_get_contents("data/$fromm_id/$fromm_id.json"),true);
$coin2 = $datas3165["coin"];
$getsho = $coin2 + $coinamount;
$datas3165["coin"] = "$getsho";
$outjson75241 = json_encode($datas3165,true);
file_put_contents("data/$fromm_id/$fromm_id.json",$outjson75241);
$datas366 = json_decode(file_get_contents("data/$fromm_id/$fromm_id.json"),true);
$coin22 = $datas366["ads"];
$getsho22 = $coin22 + 1;
$datas366["ads"] = "$getsho22";
$outjson7525 = json_encode($datas366,true);
file_put_contents("data/$fromm_id/$fromm_id.json",$outjson7525);
$coing = $datas3165["coin"];
$myfile2 = fopen("data/$fromm_id/channels.txt", "a") or die("Unable to open file!");
fwrite($myfile2, "@$newd\n");
fclose($myfile2);
$sub = $datas3165["sub"];
if($sub != null){
	$subdata552 = json_decode(file_get_contents("data/$sub/$sub.json"),true);
$invcoin = $subdata552["invcoin"];
$inv = $subdata552["coin"];
$newinv= $inv + $porsant;
$newinvcoin= $invcoin + $porsant;
if($datas3651['dafeee'] == 'first'){
		$tiksh = $subdata552['coin'];
		$ziiii = $tiksh +$invitecoin;
		$subdata552["coin"] = "$ziiii";
		bot('sendMessage',[
		'chat_id'=>$sub,
		'text'=>"تبریک🎊
یکی از زیرمجموعه های شما اولین عضویت خود را در یک کانال انجام داد✅",
		]);
		$datas3651["dafeee"] = "$newinv";
		$outjson = json_encode($datas3651,true);
		file_put_contents("data/$fromm_id/$fromm_id.json",$outjson);
		}
$subdata552["coin"] = "$newinv";
$subdata552["invcoin"] = "$newinvcoin";
$outjson = json_encode($subdata552,true);
file_put_contents("data/$sub/$sub.json",$outjson);
}
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "شما $coinamount الماس گرفتید💎 موجودی جدید : $coing",
'show_alert' => false
]);
}else{
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "💢ابتدا در کانال ممبرگیر عضو شوید",
'show_alert' => false
]);
}
}else{
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "شما هنوز در کانال عضو نشده اید❌",
'show_alert' => true
]);
}
$end = file_get_contents("ads/seen/$newd.txt");
$ad = file_get_contents("ads/admin/$newd.txt");
$co = file_get_contents("ads/cont/$newd.txt");
$te = file_get_contents("ads/time/$newd.txt");
$de = file_get_contents("ads/date/$newd.txt");
if ($end >= $co) {
bot('sendMessage', [
'chat_id' => $ad,
'text' => "سفارش شما به پایان رسید✅

آیدی کانال📣 : @$newd
تعداد ممبر درخواستی شما👥 : $co
تعداد ممبر های دریافتی شما👤 : $co",
'parse_mode' =>"html",
]);
@$don = file_get_contents("data/done.txt");
$getdon = $don + 1;
file_put_contents("data/done.txt", $getdon);
@$enn = file_get_contents("data/enf.txt");
$getenf = $enn + 1;
file_put_contents("data/enf.txt", $getenf);
$post_id = file_get_contents("ads/postid/$newd.txt");
$de = $newd + 1;
bot('deletemessage', [
'chat_id' =>"@$chads",
'message_id' => $post_id
]);
unlink("ads/seen/$newd.txt");
unlink("ads/admin/$newd.txt");
unlink("ads/cont/$newd.txt");
unlink("ads/time/$newd.txt");
unlink("ads/user/$newd.txt");
unlink("ads/date/$newd.txt");
unlink("ads/postid/$newd.txt");
}
}
}
}
if (strpos($data, "cancel-") !== false) {
$newd = str_replace("cancel-",'',$data);
$fromm_id = $update->callback_query->from->id;
$end = file_get_contents("ads/seen/$newd.txt");
$ad = file_get_contents("ads/admin/$newd.txt");
$co = file_get_contents("ads/cont/$newd.txt");
$te = file_get_contents("ads/time/$newd.txt");
$de = file_get_contents("ads/date/$newd.txt");
if ($fromm_id == $ad or $fromm_id == $admin) {
$rcoin = $co - $end;
$datas3 = json_decode(file_get_contents("data/$ad/$ad.json"),true);
$coin2 = $datas3["coin"];
$getsho = $coin2 + $rcoin;
$datas3["coin"] = "$getsho";
$outjson = json_encode($datas3,true);
file_put_contents("data/$ad/$ad.json",$outjson);
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "سفارش شما لغو شد و $rcoin الماس باقی مانده شما پس داده شد",
'show_alert' => false
]);
bot('sendMessage', [
'chat_id' => $ad,
'text' => "کاربر گرامی سفارش شما لغو شد⭕️

و الماس های باقی مانده برگشت داده شد☑️

 💎الماس های برگشت داده شده : $rcoin

با تشکر📇",
'parse_mode' => "html"
]);
@$enn = file_get_contents("data/enf.txt");
$getenf = $enn + 1;
file_put_contents("data/enf.txt", $getenf);
$newd = str_replace("cancel-",'',$data);
$post_id = file_get_contents("ads/postid/$newd.txt");
bot('deletemessage', [
'chat_id' =>"@$chads",
'message_id' =>$post_id
]);
unlink("ads/seen/$newd.txt");
unlink("ads/admin/$newd.txt");
unlink("ads/cont/$newd.txt");
unlink("ads/time/$newd.txt");
unlink("ads/user/$newd.txt");
unlink("ads/date/$newd.txt");
unlink("ads/postid/$newd.txt");
}else{
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "این سفارش متعلق به شما نیست🔴",
'show_alert' => false
]);
}
}
if (strpos($data, "goz-") !== false) {
$newd = str_replace("goz-",'',$data);
$fromm_id = $update->callback_query->from->id;
$end = file_get_contents("ads/seen/$newd.txt");
$ad = file_get_contents("ads/admin/$newd.txt");
$co = file_get_contents("ads/cont/$newd.txt");
$te = file_get_contents("ads/time/$newd.txt");
$de = file_get_contents("ads/date/$newd.txt");
$po = file_get_contents("ads/postid/$newd.txt");
if ($fromm_id != $ad) {
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "گزارش شما ثبت شد و به ادمین اطلاع داده شد⭕️",
'show_alert' => false
]);
bot('sendmessage',[
'chat_id'=>$sudo[0],
'text'=>"🔱 ادمین گرامی
پست زیر👇
 https://t.me/$chads/$po
توسط کاربر زیر گزارش شده است👇
$fromm_id
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
اطلاعات پست💡

پیوی سفارش دهنده ممبر👇
[$ad](tg://user?id=$ad)
پیوی گزارش کننده👇
[$fromm_id](tg://user?id=$fromm_id)
تعداد ممبر سفارش داده شده👤👇
$co
تعداد ممبر دریافتی💌👇
$end
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄",
'parse_mode'=>'Markdown', 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true,
            'keyboard'=>[
                [
                ['text'=>"انصراف"],
                ]
              ],
])
]);
}else{
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "شما نمیتوانید پست خود را گزارش کنید🎈",
'show_alert' => false
]);
}
}
if (strpos($data, "pay-") !== false) {
$newd = str_replace("pay-",'',$data);
$fromm_id = $update->callback_query->from->id;
$end = file_get_contents("ads/seen/$newd.txt");
$ad = file_get_contents("ads/admin/$newd.txt");
$co = file_get_contents("ads/cont/$newd.txt");
$te = file_get_contents("ads/time/$newd.txt");
$de = file_get_contents("ads/date/$newd.txt");
if ($fromm_id == $ad or $fromm_id == $admin) {
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "اطلاعات سفارش شما به شرح زیر است📇
تعداد ممبر سفارش داده شده 🛍: $co
تعداد ممبر دریافتی 👥: $end
با تشکر",
'show_alert' => true
]);
}else{
bot('answercallbackquery', [
'callback_query_id' => $update->callback_query->id,
'text' => "این سفارش متعلق به شما نیست🔴",
'show_alert' => false
]);
}
}
if($data == "home"){
$datas1["step"] = "free";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
bot('editmessagetext', [
'chat_id' => $chatid,
'message_id' => $message_id2,
'text' => "عملیات لغو شد××
شما به منوی اصلی برگشتید🏛
لطفا یک گزینه را انتخاب کنید:)",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu1
]);
}
//===سورس جم ممب ===//
if($data == "buycoin"){
$datas1["step"] = "free";
$outjson = json_encode($datas1,true);
file_put_contents("data/$chatid/$chatid.json",$outjson);
bot('editmessagetext', [
'chat_id' => $chatid,
'message_id' => $message_id2,
'text'=>"
$shoptext
",
'parse_mode'=>"HTML",
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[
['text' => "$mshopname1", 'url' => "$mshoplink"]
                    ],
                    [
['text' => "$mshopname2", 'url' => "$mshoplink"]
                    ],
                    [
['text' => "$mshopname3", 'url' => "$mshoplink"]
                    ],
                    [
['text' => "$mshopname4", 'url' => "$mshoplink"]
                    ],
[
['text' => "$mshopname5", 'url' => "$mshoplink"]
],
[
['text' => "$mshopname6", 'url' => "$mshoplink"]
],
]
])
]);
}
elseif($text == "$dok12" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
mkdir("data/codes");
$datas["step"] = "mgiftcode";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"کد هدیه را وارد کنید",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}
if($step == "mgiftcode" and $text != "انصراف" and $tc == 'private'){ 
      if(file_exists("data/codes/$text.txt")){
        $pricegift = file_get_contents("data/codes/$text.txt");
        $datas = json_decode(file_get_contents("data/$chat_id/$chat_id.json"),true);
        $inv = $datas["coin"];
        $newin = $inv + $pricegift;
        $datas["coin"] = "$newin";
        $outjson = json_encode($datas,true);
        file_put_contents("data/$chat_id/$chat_id.json",$outjson);
		SendMessage($chat_id,"☑️💌🎊 کد ارسالی شما صحیح بود

$pricegift الماس به حساب شما واریز شد");
        unlink("data/codes/$text.txt");
        $datas1["step"] = "free";
bot('sendMessage', [
'chat_id' =>"@$chads",
'text' => "🔅☑️کد با موفقیت استفاده شد

🔅 🎈 🔅 🎈 🔅
✍🏻کد : $text
🕴🏻دریافت کننده : $chat_id
🔅 🎈 🔅 🎈 🔅

@starkpanelbot",
]);
	}else{
		SendMessage($chat_id,"❌کد ارسالی نامعتبر و یا استفاده شده می باشد");
	}
}
elseif($data == "mthemt"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "dok2a";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"شما الان میتونید متن حساب کاربری رو ادیت بزنید,به بزرگ بودن یا نبودن کلمات حتما دقت کنید.
➖➖➖➖➖➖
اسم کاربر👈 NAME
فامیل کاربر👈 LAST
یوزرنیم کاربر 👈 USER
نمایش تاریخ 👈 TARIKH
تعداد زیرمجموعه 👈 INV
پورسانت دریافتی 👈 POR
تعداد عضویت 👈 OZV
تعداد سفارشات 👈 SEF
آیدی عددی کاربر 👈 ID
موجودی کاربر  👈 GEM
➖➖➖➖➖➖
جهت انصراف از تنظیم از دکمه ذیل استفاده نمایید ✅
",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"🔝ورود به پنل مدیریت🔝"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "dok2a" and $text != "🔝ورود به پنل مدیریت🔝" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok2a.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
/////////////////////////
elseif($text == "$dok13" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
$datas["step"] = "movegeme";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"✅ شماره کاربری فرد مورد نظر که قصد انتقال الماس به آن را دارید وارد کنید

⚠️ شماره کاربری هر شخص در قسمت حساب کاربری قابل دریافت است",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}
if($step == "movegeme" and $text != "/start" and $text != "انصراف" and $tc == 'private'){ 
      if(file_exists("data/$text/")){
        file_put_contents("data/$chat_id/movemem.txt",$text);
$datas["step"] = "movegeme2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
		SendMessage($chat_id,"🔄میزان الماس جهت انتقال را وارد نمایید.");
        unlink("data/codes/$text.txt");
	}else{
		SendMessage($chat_id,"❌این کاربر عضو ربات نمی باشد");
	}
}
if($step == "movegeme2" and $text != "/start" and $text != "انصراف" and $tc == 'private'){ 
        $datas = json_decode(file_get_contents("data/$from_id/$from_id.json"),true);
        
$coin11 = (abs($text));
        
        
        
        $inv = $datas["coin"];
    if ($inv >= $coin11) {
  if($text >= $coin11){
        $movemem = file_get_contents("data/$from_id/movemem.txt");
        $datas = json_decode(file_get_contents("data/$from_id/$from_id.json"),true);
        $inv = $datas["coin"];
        $newin = $inv - $coin11;
        $datas["coin"] = "$newin";
        $outjson = json_encode($datas,true);
        file_put_contents("data/$from_id/$from_id.json",$outjson);
        $datas212 = json_decode(file_get_contents("data/$movemem/$movemem.json"),true);
        $inv212 = $datas212["coin"];
        $newin212 = $inv212 + $coin11;
        $datas212["coin"] = "$newin212";
        $outjson = json_encode($datas212,true);
        file_put_contents("data/$movemem/$movemem.json",$outjson);
		SendMessage($chat_id,"✅$text به کاربر $movemem با موفقیت انتقال یافت");
		SendMessage($movemem,"✅$text به کاربر $movemem با موفقیت انتقال یافت");
$datas["step"] = "none";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
	}else{
		SendMessage($chat_id,"❌حداقل الماس قابل انتقال 10 الماس می باشد");
	}
	}else{
		SendMessage($chat_id,"❌الماس شما کافی نمی باشد");
	}
}
elseif($data == "zirmoj"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
    bot('sendphoto',[
    'photo'=>"$piclink",
    'chat_id'=>$chat_id,
    'caption'=>"$zirtext
",
'parse_mode'=>'html',

    ]);
}
elseif($text == "$dok0" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"👈️ گزینه مورد نظر را انتخاب نمائید.",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
    [['text'=>"$dok3", 'callback_data'=> 'ghavanin']],
[['text'=>"$dok44", 'callback_data'=> 'poshteam'],['text'=>"$dok547", 'callback_data'=> 'pigsefar']],
]
])
]);
}
elseif($data == "poshteam"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$datas["step"] = "support";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendmessage',[
'chat_id'=>$chat_id,
'text'=>"✉️ متن پیام خود را با رعایت موارد زیر ارسال نمایید:

1⃣ سوال، پیام، انتقاد و پیشنهادهای خود را درون یک پیام واحد نوشته و ارسال نمایید. (از جواب دادن به مواردی که در چند پیام جداگانه ارسال می شود معذوریم)
2⃣ تا زمان دریافت پاسخ صبور باشید و از پرسش مجدد خودداری کنید.",
'parse_mode'=>'Markdown', 
'reply_markup'=>json_encode([ 
'resize_keyboard'=>true,
            'keyboard'=>[
                [
                ['text'=>"$backsinza"],
                ]
              ],
])
]);
}
if($step == "support" && $text != "$backsinza"){ 
$datas["step"] = "support";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('ForwardMessage',[
'chat_id'=>$admin,
'from_chat_id'=>$chat_id,
'message_id'=>$message_id
]);
SendMessage($admin,"👆🏻عددی کاربر پیام بالا: $chat_id

✅جهت پاسخ دهی وارد بخش پیام به کاربر شوید و سپس عددی شخص را وارد نمایید و پیام خود را ارسال کنید.");
SendMessage($chat_id,"پیغام شما دریافت شد✅

تا زمان دریافت پاسخ شکیبا باشید🙏🏻");
}
elseif($s2da != "" && $from_id == $admin){
bot('sendmessage',[
'chat_id'=>$s2da,
 'text'=>"✅پاسخ تیم پشتیبانی
 
$text",
'parse_mode'=>'MarkDown',
]);
bot('sendmessage',[
 'chat_id'=>$chat_id,
 'text'=>"پاسخ با موفقیت به $s2da ارسال شد",
'parse_mode'=>'MarkDown',
 ]);
}
elseif($data == "pigsefar"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
$datas["step"] = "sinzamsexsi";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"🔍ایدی کانال سفارش خود را بدون @ ارسال نمایید.

📍تنها ادمین ان سفارش میتواند پیگیری ان سفارش را انجام دهد.",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"$backsinza"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}
if($step == "sinzamsexsi" and $text != "/start" and $text != "$backsinza" and $tc == 'private'){ 
        $datas["step"] = "sinzamsexsi";
if (file_exists("ads/admin/$text.txt")) {
$end = file_get_contents("ads/seen/$text.txt");
$ad = file_get_contents("ads/admin/$text.txt");
$co = file_get_contents("ads/cont/$text.txt");
$te = file_get_contents("ads/time/$text.txt");
$de = file_get_contents("ads/date/$text.txt");
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5 or $chat_id == $ads or $chat_id == $ad) {
    $sinzamr = $co - $end;
bot('SendMessage', [
'chat_id'=>$chat_id,
'text' => "اطلاعات سفارش شما به شرح زیر است📇
🆔ایدی کانال شما: @$text

👤تعداد ممبر درخواستی شما: $co
🎁تعداد ممبر دریافتی: $end
💎تعداد ممبر باقیمانده: $sinzamr

🔆با تشکر از شما دوست عزیز🔆",
'show_alert' => true
]);
}}else{
    if($step == "sinzamsexsi" and $text != "/start" and $text != "$backsinza" and $tc == 'private'){ 
bot('SendMessage', [
'chat_id'=>$chat_id,
'text' => "🚫این سفارش برای شما نیست یا وجود ندارد.
❓درصورتی که شما این سفارش را ثبت کردید و وجود دارد به بدون @ بودن ان دقت نمایید.",
'show_alert' => false
]);
}}}
if($text == "$dok2" and $tc == 'private'){
    $truechannel = json_decode(file_get_contents("https://api.telegram.org/bot$token/getChatMember?chat_id=$channel&user_id=$chat_id"));
$tch25 = $truechannel->result->status;
if($tch25 != 'member' and $tch25 != 'creator' and $tch25 != 'administrator' and is_file("channel.txt") and $chat_id != $admin){
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"💎جهت حمایت از ما در کانال زیر عضو شوید

🎁[ $chaneel ]🎁
✅سپس مجدد ربات رو استارت کنید.
/start",
'disable_web_page_preview' => true,
'parse_mode'=>"HTML",
]);
}
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$step = $datas["step"];
$inv = $datas["inv"];
$coin = $datas["coin"];
$type = $datas["type"];
$sefaresh = $datas["sefaresh"];
$warn = $datas["warn"];
$ads = $datas["ads"];
$invcoin = $datas["invcoin"];
bot('sendMessage',[
'chat_id'=>$chat_id,
 'text'=>"
 $dok2a
",
'parse_mode'=>'HTML',
'reply_markup'=>json_encode([
'inline_keyboard'=>[
[['text'=>"به اشتراک گذاشتن کد کاربری",'url' => "https://telegram.me/share/url?url=$chat_id&&text=کد%20کاربری%20من%20در%20ربات%20[*[USERNAME]*]"]],
]
])
]);
}
//----------------------------------------------------------------------
elseif($text == "مدیریت" or $text == "پنل" or $text == "/panel" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"سلام مدیر گرامی به پنل خوش آمدید!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}
}
elseif($text == "✅بلاک و انبلاک⛔️" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به بخش بلاک و انبلاک خوش اومدی",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
            [['text'=>"⛔️بخش بلاک و انبلاک✅", 'callback_data'=> 'sinznopebrosokey']],
    [['text'=>"✅انبلاک کردن", 'callback_data'=> 'blockinfo'],['text'=>"⛔️بلاک کردن", 'callback_data'=> 'unblockinfo']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "📊آمار ربات" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
$allusers = count($alaki) - 2;
$done = file_get_contents("data/done.txt");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تعداد کاربران ربات شما: $allusers
تبلیغات انجام شده : $done
",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
///////////////----------------////////////
elseif($text == "📇تنظیم متن" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
$allusers = count($alaki);
$done = file_get_contents("data/done.txt");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به قسمت تنظیم متن خوش اومدی
",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
    [['text'=>"💎دریافت الماس", 'callback_data'=> 'ms1'],['text'=>"⛔️قوانین", 'callback_data'=> 'ms2']],
    [['text'=>"✅استارت", 'callback_data'=> 'ms3'],['text'=>"🎊فروشگاه", 'callback_data'=> 'ms4']],
        [['text'=>"🔐حساب کاربری", 'callback_data'=> 'mthemt'],['text'=>"👤توضیحات زیر", 'callback_data'=> 'ms6']],
            [['text'=>"🔴راهنما🔴", 'callback_data'=> 'ms5']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "🛍فروشگاه" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
$allusers = count($alaki);
$done = file_get_contents("data/done.txt");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به قسمت تنظیم فروشگاه خوش آمدید

در این قسمت میتوانید نام محصولات و لینک متصل به آن را مدیریت نمایید",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($text == "👤ادمین ها" and $tc == 'private'){	
if ($chat_id == $admin) {
$alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
$allusers = count($alaki);
$done = file_get_contents("data/done.txt");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به پنل مدیریت ادمین ها خوش آمدید
در این قسمت میتوانید ادمین های دوم و سوم را مدیریت نمایید",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
    [['text'=>"💎ادمین دوم", 'callback_data'=> 'ad1'],['text'=>"💎ادمین اول", 'callback_data'=> 'ad2']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"❌این بخش فقط توسط ادمین اصلی ربات قابل استفاده می باشد",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);     
}}
elseif($text == "👤ادمین ها" and $tc == 'private'){	
if ($chat_id == $admin2 or $chat_id == $admin3) {
$alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
$allusers = count($alaki);
$done = file_get_contents("data/done.txt");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"❌این بخش فقط توسط ادمین اصلی ربات قابل استفاده می باشد",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "💠دکمه ها" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$alluser = file_get_contents("data/ozvs.txt");
$alaki = explode("\n",$alluser);
$allusers = count($alaki);
$done = file_get_contents("data/done.txt");
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"به قسمت تنظیم دکمه ها خوش اومدید
",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
    [['text'=>"🚀دکمه های ربات🚀", 'callback_data'=> 'none']],
    [['text'=>"$dok1", 'callback_data'=> 'dok1']],
    [['text'=>"$dok2", 'callback_data'=> 'dok2'],['text'=>"$dok4", 'callback_data'=> 'dok4']],
    [['text'=>"$dok3", 'callback_data'=> 'dok3'],['text'=>"$dok13", 'callback_data'=> 'dok13'],['text'=>"$dok5", 'callback_data'=> 'unblockinfo']],
    [['text'=>"$dok6", 'callback_data'=> 'dok6'],['text'=>"$dok7", 'callback_data'=> 'dok7']],
    [['text'=>"$dok0", 'callback_data'=> 'dok0'],['text'=>"$dok12", 'callback_data'=> 'dok12']],
    [['text'=>"📣دکمه های کانال📣", 'callback_data'=> 'none']],
    [['text'=>"$dokc1", 'callback_data'=> 'dokc1'],['text'=>"$dokc3", 'callback_data'=> 'dokc3']],
    [['text'=>"$dokc2", 'callback_data'=> 'dokc2'],['text'=>"$dokc5", 'callback_data'=> 'dokc5']],
    [['text'=>"$dokc4", 'callback_data'=> 'dokc4'],['text'=>"$dokc6", 'callback_data'=> 'dokc6']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($data == "axb"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext688";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"خب عکس جدید رو بفرستین",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"انصراف"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext688" and $text != "پنل" and $tc == 'private' and $text != "انصراف" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
	$filephoto = $update->message->photo;
	$photo = $filephoto[count($filephoto)-1]->file_id;
	if(isset($photo)){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/piclink.txt",$photo);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
	}else{
		bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا عکس بفرس",
'parse_mode'=>"MarkDown"
]); 
	}
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dokc6"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext680";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید ورود به ربات را ارسال نمایید

نام فعلی : $dokc6",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext680" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokc6.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dokc5"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext679";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه گزارش تخلف را ارسال نمایید

نام فعلی : $dokc5",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext679" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokc5.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dokc4"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext678";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه پیگیری سفارش را ارسال نمایید

نام فعلی : $dokc4",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext678" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokc4.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dokc3"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext677";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه لغو سفارش را ارسال نمایید

نام فعلی : $dokc3",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext677" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokc3.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dokc2"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext676";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه دریافت الماس را ارسال نمایید

نام فعلی : $dokc2",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext676" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokc2.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "ad1"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin) {
$datas["step"] = "adminman2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی ادمین جدید را ارسال نمایید
ادمین فعلی : $admin2",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "adminman2" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/admin2.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

ادمین جدید : $text",
'parse_mode'=>"HTML",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'inline_keyboard'=>[
    [['text'=>"💎ادمین دوم", 'callback_data'=> 'ad1'],['text'=>"💎ادمین اول", 'callback_data'=> 'ad2']],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "ad2"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin) {
$datas["step"] = "adminman3";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی ادمین جدید را ارسال نمایید
ادمین فعلی : $admin3",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "adminman3" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/admin3.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

ادمین جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"ادمین سوم"],['text'=>"ادمین دوم"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dokc1"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext675";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه عضویت در کانال را ارسال نمایید

نام فعلی : $dokc1",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext675" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokc1.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($text == "پیگیری سفارشات" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext673";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه پیگیری سفارشات را ارسال نمایید

نام فعلی : $dok8",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext673" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok8.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok7"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext672";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه برترین ها را ارسال نمایید

نام فعلی : $dok7",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext672" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok7.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok6"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext671";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه زیر مجموعه گیری را ارسال نمایید

نام فعلی : $dok6",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext671" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok6.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok5"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext670";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه فروشگاه را ارسال نمایید

نام فعلی : $dok5",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext670" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok5.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok4"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext669";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه سفارش ممبر را ارسال نمایید

نام فعلی : $dok4",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext669" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok4.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok3"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext668";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه قوانین را ارسال نمایید

نام فعلی : $dok3",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext668" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok3.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok2"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext667";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه پروفایل را ارسال نمایید

نام فعلی : $dok2",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext667" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok2.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok1"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext666";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه دریافت الماس رایگان را ارسال نمایید

نام فعلی : $dok1",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext666" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok1.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok12"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextgif1";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه *کد هدیه* را ارسال نمایید

نام فعلی : $dok12",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextgif1" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok12.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"دریافت الماس رایگان"]],
[['text'=>"پروفایل"],['text'=>"قوانین"],['text'=>"سفارش ممبر"]],
[['text'=>"فروشگاه"],['text'=>"زیر مجموعه گیری"]],
[['text'=>"برترین ها"],['text'=>"پیگیری سفارشات"]],
[['text'=>"کد هدیه"],['text'=>"انتقال الماس"]],
[['text'=>"جمع آوری در کانال"],['text'=>"الماس روزانه"]],
[['text'=>"⚙تنظیم دکمه کانال"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($data == "dok0"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextgifms1";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه *پیگیری* را ارسال نمایید

نام فعلی : $dok0",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextgifms1" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok0.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"دریافت الماس رایگان"]],
[['text'=>"پروفایل"],['text'=>"قوانین"],['text'=>"سفارش ممبر"]],
[['text'=>"فروشگاه"],['text'=>"زیر مجموعه گیری"]],
[['text'=>"برترین ها"],['text'=>"پیگیری سفارشات"]],
[['text'=>"کد هدیه"],['text'=>"انتقال الماس"]],
[['text'=>"جمع آوری در کانال"],['text'=>"الماس روزانه"]],
[['text'=>"⚙تنظیم دکمه کانال"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($text == "جمع آوری در کانال" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextchanneldok";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه *جمع آوری در کانال* را ارسال نمایید

نام فعلی : $dokchannel",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextchanneldok" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokchannel.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"دریافت الماس رایگان"]],
[['text'=>"پروفایل"],['text'=>"قوانین"],['text'=>"سفارش ممبر"]],
[['text'=>"فروشگاه"],['text'=>"زیر مجموعه گیری"]],
[['text'=>"برترین ها"],['text'=>"پیگیری سفارشات"]],
[['text'=>"کد هدیه"],['text'=>"انتقال الماس"]],
[['text'=>"جمع آوری در کانال"],['text'=>"الماس روزانه"]],
[['text'=>"⚙تنظیم دکمه کانال"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($text == "الماس روزانه" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextdokdaily";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه *الماس روزانه* را ارسال نمایید

نام فعلی : $dokday",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextdokdaily" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dokday.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"دریافت الماس رایگان"]],
[['text'=>"پروفایل"],['text'=>"قوانین"],['text'=>"سفارش ممبر"]],
[['text'=>"فروشگاه"],['text'=>"زیر مجموعه گیری"]],
[['text'=>"برترین ها"],['text'=>"پیگیری سفارشات"]],
[['text'=>"کد هدیه"],['text'=>"انتقال الماس"]],
[['text'=>"جمع آوری در کانال"],['text'=>"الماس روزانه"]],
[['text'=>"⚙تنظیم دکمه کانال"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "dok13"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextgif125";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید دکمه *انتقال الماس* را ارسال نمایید

نام فعلی : $dok13",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextgif125" and $text != "پنل" and $tc == 'private' and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/dok13.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت تنظیم شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"دریافت الماس رایگان"]],
[['text'=>"پروفایل"],['text'=>"قوانین"],['text'=>"سفارش ممبر"]],
[['text'=>"فروشگاه"],['text'=>"زیر مجموعه گیری"]],
[['text'=>"برترین ها"],['text'=>"پیگیری سفارشات"]],
[['text'=>"کد هدیه"],['text'=>"انتقال الماس"]],
[['text'=>"جمع آوری در کانال"],['text'=>"الماس روزانه"]],
[['text'=>"⚙تنظیم دکمه کانال"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
////////////////---------------////////////////////////////////////////////////
elseif($data == "ms1"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext117DAR";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن جمع دریافت الماس  را ارسال کنید
به جای نام NAME
به جای یوزرنیم @USER
و به جای نام خانوادگی LAST
و به جای آیدی عددی ID

را در متن قرار دهید تا جایگزین شود!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext117DAR" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/almasgett.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
elseif($data == "porzir"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext11";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تعداد الماس دریافتی برای پورسانت عضویت کانال زیرمجموعه برای هر سفارش را با حروف انگلیسی وارد نمایید
مثال : 0.2",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext11" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/porsant.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
//////---////----///------------/----/---/-/-/-/-----//////////////////
elseif($data == "cozv"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext22";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تعداد الماس دریافتی برای عضویت در هر کانال را با حروف انگلیسی وارد نمایید
مثال : 1",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext22" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/coinamount.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
//////////------////////////////////////-------------------/////////////
elseif($data == "jozv"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextjoi1";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تعداد الماس دریافتی در صورت ورود به ربات را با حروف انگلیسی وارد نمایید
میزان فعلی : $joinmcoin",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextjoi1" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/joinmcoin.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
//////////////////////////////////
elseif($data == "rozv"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttextmdaily";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تعداد الماس روزانه را با حروف انگلیسی وارد نمایید
میزان فعلی : $mdaily",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttextmdaily" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mdaily.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

میزان جدید : $text",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
//////////////////////////////////
elseif($data == "ms3"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "starttext";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن استارت را ارسال کنید
به جای نام NAME
به جای یوزرنیم @USER
و به جای نام خانوادگی LAST
و به جای آیدی عددی ID

را در متن قرار دهید تا جایگزین شود!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "starttext" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/starttext.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
//----------------------------------------------------------------------
//----------------------------------------------------------------------
elseif($text == "نام محصول اول" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam1";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید محصول اول فروشگاه را ارسال نمایید

نام فعلی : $mshopname1",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam1" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshopname1.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "نام محصول دوم" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید محصول دوم فروشگاه را ارسال نمایید

نام فعلی : $mshopname2",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam2" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshopname2.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "نام محصول سوم" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam3";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید محصول سوم فروشگاه را ارسال نمایید

نام فعلی : $mshopname3",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam3" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshopname3.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "نام محصول چهارم" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam4";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید محصول چهارم فروشگاه را ارسال نمایید

نام فعلی : $mshopname4",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam4" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshopname4.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "نام محصول پنجم" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam5";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید محصول پنجم فروشگاه را ارسال نمایید

نام فعلی : $mshopname5",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam5" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshopname5.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "نام محصول ششم" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam6";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"نام جدید محصول ششم فروشگاه را ارسال نمایید

نام فعلی : $mshopname6",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam6" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshopname6.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

نام جدید : $text",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($text == "نصب درگاه پرداخت" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "mshopnam7";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>" لطفا لینک جدید متصل به فروشگاه را به همراه https:// ارسال نمایید

لینک فعلی : [$mshoplink]",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "mshopnam7" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mshoplink.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت ثبت شد

لینک جدید : [$text]",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"نام محصول دوم"],['text'=>"نام محصول اول"]],
[['text'=>"نام محصول چهارم"],['text'=>"نام محصول سوم"]],
[['text'=>"نام محصول ششم"],['text'=>"نام محصول پنجم"]],
[['text'=>"نصب درگاه پرداخت"]],
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
//----------------------------------------------------------------------
elseif($data == "etoz"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
	if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
		$datas["step"] = "getchannel";
		$outjson = json_encode($datas,true);
		file_put_contents("data/$from_id/$from_id.json",$outjson);
		bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا ابتدا ربات را ادمین کانال کنید سپس آیدی کانال را همراه با @ ارسال نمایید.\nدرصورتی که میخواهید قفل کانال حذف شود عدد 0 را ارسال کنید.",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
	}
}
elseif($step == "getchannel" and $text != "پنل" and $tc == 'private'){
	$datas["step"] = "none";
	$outjson = json_encode($datas,true);
	file_put_contents("data/$from_id/$from_id.json",$outjson);
	if($text == '0'){
		unlink("channel.txt");
		bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"قفل کانال با موفقیت حذف شد.",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
	}
	else{
		file_put_contents("channel.txt",$text);
		bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"قفل کانال روی کانال $text تنظیم شد.",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]);
	}
}
elseif($data == "unblockinfo"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "getid";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی فرد را ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "getid" and $text != "پنل" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(file_exists("data/$text/$text.json")){
$datas["step"] = "sendwarn";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/id.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"چند اخطار به کاربر داده شود؟!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"همچین کاربری در ربات وجود ندارد
آیدی عددی درست ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
elseif($step == "sendwarn" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(is_numeric($text)){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$id = file_get_contents("data/id.txt");
$datas = json_decode(file_get_contents("data/$id/$id.json"),true);
$inv = $datas["warn"];
$newin = $inv + $text;
$datas["warn"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$id/$id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"💢از طرف مدیریت به شما *$text* اخطار داده شد!",
'parse_mode'=>"MarkDown",
]); 
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*$text* اخطار به *$id* داده شد",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا عدد ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
//////////----------------/////////////////////////////////////////
elseif($data == "ehdase"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "getid2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی فرد را ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "getid2" and $text != "پنل" and $tc == 'private' and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(file_exists("data/$text/$text.json")){
$datas["step"] = "sendcoin2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/id.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"چند الماس به کاربر داده شود؟!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"همچین کاربری در ربات وجود ندارد
آیدی عددی درست ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
elseif($step == "sendcoin2" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(is_numeric($text)){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$id = file_get_contents("data/id.txt");
$datas = json_decode(file_get_contents("data/$id/$id.json"),true);
$inv = $datas["coin"];
$newin = $inv + $text;
$datas["coin"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$id/$id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"از طرف مدیریت به شما *$text* الماس داده شد!",
'parse_mode'=>"MarkDown",
]); 
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*$text* الماس به *$id* ارسال گردید",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا عدد ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
//----------------------------------------------------------------------
elseif($text == "⌨️کد هدیه" and $tc == 'private'){	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "getid2gg";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"کد هدیه جدید را ارسال نمایید",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "getid2gg" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "sendcoin2gg";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("newgiftm.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"این کد شامل چند الماس باشد؟",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "sendcoin2gg" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(is_numeric($text)){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$newgiftm = file_get_contents("newgiftm.txt");
file_put_contents("data/codes/$newgiftm.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"کد *$newgiftm* به ارزش *$text* الماس با موفقیت ساخته شد",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
bot('sendMessage', [
'chat_id' =>"@$chads",
'text' => "💌🎊کد هدیه جدید ساخته شد

🔅 🎈 🔅 🎈 🔅
✍🏻کد : $newgiftm
💎الماس : $text
🔅 🎈 🔅 🎈 🔅

@starkpanelbot",
]);
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا عدد ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
//----------------------------------------------------------------------
elseif($data == "blockinfo"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;	
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "getids";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی فرد را ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "getids" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(file_exists("data/$text/$text.json")){
$datas["step"] = "sendwarns";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/id.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"چند اخطار از کاربر کسر شود؟!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"همچین کاربری در ربات وجود ندارد
آیدی عددی درست ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
elseif($step == "sendwarns" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(is_numeric($text)){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$id = file_get_contents("data/id.txt");
$datas = json_decode(file_get_contents("data/$id/$id.json"),true);
$inv = $datas["warn"];
$newin = $inv - $text;
$datas["warn"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$id/$id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"☑️از طرف مدیریت از شما *$text* اخطار کسر گردید!",
'parse_mode'=>"MarkDown",
]); 
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*$text* اخطار از *$id* کسر گردید",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا عدد ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
////////////------------//////////////////////////////////////////////////
elseif($data == "kase"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "getids2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی فرد را ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "getids2" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(file_exists("data/$text/$text.json")){
$datas["step"] = "sendcoins2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/id.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"چند الماس از کاربر کسر شود؟!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"همچین کاربری در ربات وجود ندارد
آیدی عددی درست ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
elseif($step == "sendcoins2" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
if(is_numeric($text)){
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$id = file_get_contents("data/id.txt");
$datas = json_decode(file_get_contents("data/$id/$id.json"),true);
$inv = $datas["coin"];
$newin = $inv - $text;
$datas["coin"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$id/$id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"از طرف مدیریت از شما *$text* الماس کسر گردید!",
'parse_mode'=>"MarkDown",
]); 
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"*$text* الماس از *$id* کسر گردید",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"لطفا عدد ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
//////////----------------/////////////////////////////////////////
elseif($data == "pmkar"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "getid2000";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی عددی دریافت کننده ی پیام را ارسال نمایید",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"/panel"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "getid2000" and $text != "/panel" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
if(file_exists("data/$text/$text.json")){
$datas["step"] = "sendcoin2000";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/id.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن ارسالی به کاربر مورد نظر را ارسال نمایید",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"/panel"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}else{
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"همچین کاربری در ربات وجود ندارد
آیدی عددی درست ارسال کنید!",
'parse_mode'=>"MarkDown",
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"/panel"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}}
elseif($step == "sendcoin2000" and $text != "/panel" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3 or $chat_id == $admin4 or $chat_id == $admin5) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
$id = file_get_contents("data/id.txt");
$datas = json_decode(file_get_contents("data/$id/$id.json"),true);
$inv = $datas["coin"];
$newin = $inv + $text;
$datas["coin"] = "$newin";
$outjson = json_encode($datas,true);
file_put_contents("data/$id/$id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$id,
'text'=>"💢یک پیام از طرف مدیریت دریافت کرده اید
----------------------
$text",
'parse_mode'=>"MarkDown",
]); 
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"با موفقیت به $id ارسال گردید

متن پیام
------------------------
$text",
'parse_mode'=>'MarkDown',
        	'reply_markup'=>$menu2
]); 
}}
////////////----/////////////////////////////////////////////////
//----------------------------------------------------------------------
elseif($data == "ms2"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "zirtext21";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن قوانین را ارسال کنید",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "zirtext21" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/ghavanin.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
elseif($data == "ms5"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "zirtext21ms5";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن راهنما را ارسال کنید",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "zirtext21ms5" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mdok8.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
elseif($data == "ms6"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "zirtext21ms5ms6";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن توضیحات زیرمجموعه را ارسال کنید",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "zirtext21ms5ms6" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/mtzir.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
/////----///////////-----------//////////--------////////////------------------------
elseif($data == "ms4"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "zirtext2";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن فروشگاه را ارسال کنید

به جای نام NAME
و به جای نام خانوادگی LAST
و به جای آیدی عددی ID",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "zirtext2" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/shoptext.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
/////////////////------////////////
elseif($data == "invzir"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "zirtext24";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"الماس زیرمجموعه گیری را وارد نمایید
مثال : 10",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "zirtext24" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/invitecoin.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
///////////////////////////////////////////---//////
elseif($data == "mtb"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "zirtext";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"متن زیرمجموعه گیری را ارسال کنید
به جای نام NAME
به جای لینک LINK
و به جای نام خانوادگی LAST
و به جای آیدی عددی ID

را در متن قرار دهید تا جایگزین شود!",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}}
elseif($step == "zirtext" and $text != "پنل" and $tc == 'private'){ 
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("data/zirtext.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تنظیم شد!",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
}}
//----------------------------------------------------------------------
elseif($data == "taoz"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "setsht";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی کانال تبلیغات رو بدون @ ارسال نمایید.",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]);
}
}
elseif($text != "پنل" and $step == "setsht" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "free";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
file_put_contents("cht.txt",$text);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"آیدی کانال تبلیغات با موفقیت @$text تنظیم شد.",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]);
}
}
elseif($data == "hamse"){
    $chat_id = $update->callback_query->message->chat->id;
    $from_id = $update->callback_query->message->chat->id;
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "sekhame";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"🧮 خب تعداد الماس رو وارد کنید.",
'parse_mode'=>"MarkDown",
'reply_to_message_id'=>$message_id,
'reply_markup'=>json_encode([
'keyboard'=>[
[['text'=>"پنل"]],
],
"resize_keyboard"=>true,'one_time_keyboard' => true,
])
]); 
}
}
elseif($text != "پنل" and $step == "sekhame" and $tc == 'private'){
if ($chat_id == $admin or $chat_id == $admin2 or $chat_id == $admin3) {
$datas["step"] = "none";
$outjson = json_encode($datas,true);
file_put_contents("data/$from_id/$from_id.json",$outjson);
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"در حال فرستادن $text الماس برای همه اعضا",
'parse_mode'=>'MarkDown',
          'reply_markup'=>$menu2
]); 
$all_member = file_get_contents("data/ozvs.txt");
$ex = explode("\n",$all_member);
$cplug = count($ex) - 2;
for($n=0; $n<=$cplug; $n++) {
$user = $ex[$n];
$gfgfgfgf = json_decode(file_get_contents("data/$user/$user.json"),true);
$inv = $gfgfgfgf["coin"];
$newin = $inv + $text;
$gfgfgfgf["coin"] = "$newin";
$fvdsfvdsfv = json_encode($gfgfgfgf,true);
file_put_contents("data/$user/$user.json",$fvdsfvdsfv);
bot('sendMessage',[
'chat_id'=>$user,
'text'=>"#همگانی

↖️ تعداد $text الماس همگانی از طرف مدیریت ارسال شد.",
'parse_mode'=>"html"
]); 
}
bot('sendMessage',[
'chat_id'=>$chat_id,
'text'=>"تعداد $text الماس با موفقیت برای همه اعضا ارسال شد.",
'parse_mode'=>"html"
]);
}
}
if(file_exists(error_log))
	unlink(error_log);
?>
