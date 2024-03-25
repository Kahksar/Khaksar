<?php

// @AliCybeRR

// set a cronjob 1min !

error_reporting(0);
ini_set('display_errors', 0);
ini_set('memory_limit', -1);
ini_set('max_execution_time', -1);
if(!is_dir('data')) mkdir('data');
if(!file_exists('data/Poker.txt')) file_put_contents("data/Poker.txt","Off");
if(!is_dir('files')){
mkdir('files');
}
if(!file_exists('madeline.php')){
copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php');
}
if(!file_exists('online.txt')){
file_put_contents('online.txt','off');
}
if(!file_exists('bold.txt')){
file_put_contents('bold.txt','off');
}
if(!file_exists('part.txt')){
file_put_contents('part.txt','off');
}
include 'madeline.php';
$settings = [];
$settings['logger']['max_size'] = 5*1024*1024;
$MadelineProto = new \danog\MadelineProto\API('AliCybeRR.madeline', $settings);
$MadelineProto->start();

function closeConnection($message = 'AliCybeRR Self Is Running ...'){
 if (php_sapi_name() === 'cli' || isset($GLOBALS['exited'])) {
  return;
 }
// AliCybeRR
    @ob_end_clean();
    @header('Connection: close');
    ignore_user_abort(true);
    ob_start();
    echo "$message";
    $size = ob_get_length();
    @header("Content-Length: $size");
    @header('Content-Type: text/html');
    ob_end_flush();
    flush();
    $GLOBALS['exited'] = true;
}
function shutdown_function($lock)
{
   try {
    $a = fsockopen((isset($_SERVER['HTTPS']) && @$_SERVER['HTTPS'] ? 'tls' : 'tcp').'://'.@$_SERVER['SERVER_NAME'], @$_SERVER['SERVER_PORT']);
    fwrite($a, @$_SERVER['REQUEST_METHOD'].' '.@$_SERVER['REQUEST_URI'].' '.@$_SERVER['SERVER_PROTOCOL']."\r\n".'Host: '.@$_SERVER['SERVER_NAME']."\r\n\r\n");
    flock($lock, LOCK_UN);
    fclose($lock);
} catch(Exception $v){}
}
if (!file_exists('bot.lock')) {
 touch('bot.lock');
}
// AliCybeRR
$lock = fopen('bot.lock', 'r+');
$try = 1;
$locked = false;
while (!$locked) {
 $locked = flock($lock, LOCK_EX | LOCK_NB);
 if (!$locked) {
  closeConnection();
 if ($try++ >= 30) {
 exit;
 }
   sleep(1);
 }
}
if(!file_exists('data.json')){
 file_put_contents('data.json', '{"power":"on","adminStep":"","typing":"off","gaming":"off","echo":"off","markread":"off","poker":"off","enemies":[],"answering":[]}');
}
// Coded by : @AliCybeRR
class EventHandler extends \danog\MadelineProto\EventHandler
{
public function __construct($MadelineProto){
parent::__construct($MadelineProto);
}
public function onUpdateSomethingElse($update)
{
yield $this->onUpdateNewMessage($update);
}
public function onUpdateNewChannelMessage($update)
{
yield $this->onUpdateNewMessage($update);
}
public function onUpdateNewMessage($update){
$from_id = isset($update['message']['from_id']) ? $update['message']['from_id']:'';
  try {
 if(isset($update['message']['message'])){
 $text = $update['message']['message'];
 $msg_id = $update['message']['id'];
 $message = isset($update['message']) ? $update['message']:'';
 $MadelineProto = $this;
 $me = yield $MadelineProto->get_self();
 $admin = $me['id'];
 $chID = yield $MadelineProto->get_info($update);
 $peer = $chID['bot_api_id'];
 $type3 = $chID['type'];
 $data = json_decode(file_get_contents("data.json"), true);
 $step = $data['adminStep'];
 $Poker=file_get_contents("data/Poker.txt");
 if(!file_exists('ooo')){
 file_put_contents('ooo', '');
 }
  if(file_exists('ooo') && file_get_contents('online.txt') == 'on' && (time() - filectime('ooo')) >= 30){
   @unlink('ooo');
   @file_put_contents('ooo', '');
   yield $MadelineProto->account->updateStatus(['offline' => false]);
  }
$partmode=file_get_contents("part.txt");
$boldmode=file_get_contents("bold.txt");
$Poker=file_get_contents("data/Poker.txt");
if($Poker=='On' and $from_id!==$admin){
if(strpos($text,"😐")!==false){
yield $this->messages->sendMessage(['peer' => $peer, 'reply_to_msg_id' =>$msg_id ,'message' => "😐"]);}}
 if($from_id == $admin){
   if(preg_match("/^[\/\#\!]?(bot) (on|off)$/i", $text)){
     preg_match("/^[\/\#\!]?(bot) (on|off)$/i", $text, $m);
     $data['power'] = $m[2];
     file_put_contents("data.json", json_encode($data));
     yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "B͟o͟t͟  N҉o҉w҉  I҈s҈ $m[2]"]);
   }
  if(preg_match("/^[\/\#\!]?(poker) (on|off)$/i", $text)){
  preg_match("/^[\/\#\!]?(poker) (on|off)$/i", $text, $m);
  file_put_contents('data/Poker.txt', $m[2]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "poker Mod Now Is $m[2]"]);
   }
   if(preg_match("/^[\/\#\!]?(part) (on|off)$/i", $text)){
  preg_match("/^[\/\#\!]?(part) (on|off)$/i", $text, $m);
  file_put_contents('part.txt', $m[2]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🇵 🇦 🇷 🇹  N̾o̾w̾  Is$m[2]"]);
   }
if(preg_match("/^[\/\#\!]?(bold) (on|off)$/i", $text)){
  preg_match("/^[\/\#\!]?(bold) (on|off)$/i", $text, $m);
  file_put_contents('bold.txt', $m[2]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "𝓑𝓸𝓵𝓭  𝘕𝘰𝘸 Is $m[2]"]);
}
if ($text == 'آدم فضایی') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽                     🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽                    🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽                   🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽                  🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽                 🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽                🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽               🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽              🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽             🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽            🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽           🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽          🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽         🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽        🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽       🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽      🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽     🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽    🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽   🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽  🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽 🔦😼"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👽🔦🙀"]);
}
if ($text == 'موشک' or $text=='حمله'  or $text=='سفینه بترکون') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                                🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                               🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                              🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                             🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                            🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                           🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                          🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                         🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                        🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                       🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                      🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                     🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                   🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                  🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                 🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀                🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀               🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀              🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀            🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀           🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀          🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀         🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀        🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀       🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀      🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀     🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀    🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀   🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀  🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀 🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍🚀🛸"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌍💥Boom💥"]);
}
if ($text == 'پول' or $text=='دلار'  or $text=='ارباب شهر من') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌                    💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌                   💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌                 💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌                💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌               💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌              💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌             💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌            💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌           💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌          💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥                     💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌        💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌       💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌      💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌     💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌    💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌   💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌  💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌ 💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥            ‌💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥           💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥          💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥         💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥        💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥       💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥      💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥     💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥    💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥   💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥  💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔥 💵"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💸"]);
}
if ($text == 'با کارای ت باید چالش سعی کن نرینی بزارن' or $text == 'خزوخیل') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩               🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩              🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩             🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩            🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩           🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩          🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩         🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩        🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩       🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩      🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩     🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩    🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩   🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩  🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💩 🤢"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤮🤮"]);
  }
if ($text == 'جن' or $text=='روح'  or $text=='روحح') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                                   🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                                  🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                                 🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                                🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                               🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                              🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                             🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                            🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                           🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                          🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                         🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                        🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                       🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                      🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                     🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                    🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                   🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                  🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻                 🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻               🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻              🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻             🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻            🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻           🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻          🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻         🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻        🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻       🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻      🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻     🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻    🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻   🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻  🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻 🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👻🙀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☠بگارف☠"]);
}
if ($text == 'برم خونه' or $text == 'رسیدم خونه') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠              🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠             🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠            🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠           🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠          🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠         🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠        🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠       🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠      🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠     🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠    🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠   🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠  🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠 🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏠🚶‍♂"]);
}
if ($text == 'قلب') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "❤️🧡💛💚"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💜💙🖤💛"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤍🤎💛💜"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💚❤️🖤🧡"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💜💚🧡🖤"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤍🧡🤎💜"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💙🧡💜🧡"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💚💛💙💜"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🖤💛💙🤍"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🖤🤍💙❤"]);
}
if ($text == 'فرار از خونه' or $text=='شکست عشقی') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡 💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡  💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡   💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡    💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡     💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡      💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡       💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡        💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡         💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡          💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡           💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡            💃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡              💃💔👫"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡                 🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡               🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡             🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡           🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡         🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡       🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡     🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡  🚶‍♀"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏡🚶‍♀"]);
}
if ($text == 'عقاب' or $text=='ایگل'  or $text=='پیشی برد') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍                         🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍                      🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍                    🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍                  🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍                🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍               🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍              🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍            🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍           🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍          🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍         🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍        🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍       🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍      🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍     🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍    🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍   🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍 🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🐍🦅"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "پیشی برد😹"]);
}
if ($text == 'حموم' or $text=='حمام'  or $text=='حمومم') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪                  🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪                 🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪                🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪              🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪             🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪            🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪           🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪          🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪         🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪        🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪       🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪      🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪     🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪    🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪   🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪  🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪 🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛁🚪🗝🤏"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛀💦😈"]);
}
if ($text == 'آپدیت' or $text=='اپدیت'  or $text=='آپدیت شو') {
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️10%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️20%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️30%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️▪️40%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️▪️▪️50%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️▪️▪️▪️60%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️▪️▪️▪️▪️70%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️▪️▪️▪️▪️▪️80%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "▪️▪️▪️▪️▪️▪️▪️▪️▪️90%"]);
   yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "❗️EROR❗️"]);
   }
   if ($text == 'جنایتکارو بکش' or $text=='بکشش'  or $text=='اینو بکش') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂                 • 🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂                •  🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂               •   🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂              •    🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂             •     🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂            •      🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂           •       🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂          •        🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂         •         🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂        •          🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂       •           🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂      •            🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂     •             🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂    •              🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂   •               🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂  •                🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂 •                 🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😂•                  🔫🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤯                  🔫 🤠"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "فرد جنایتکار کشته شد :)"]);
}
if ($text == 'بریم مسجد' or $text == 'مسجد') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌                  🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌                 🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌                🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌               🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌              🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌             🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌            🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌           🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌          🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌         🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌        🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌       🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌      🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌     🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌    🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌   🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌  🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌 🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🕌🚶‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "اشهدان الا الا الله📢"]);
  }
  if ($text == 'کوسه' or $text == 'وای کوسه') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄┅┄┅┄┄┅🏊‍♂┅┄┄┅🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄┅┄┅┄┄🏊‍♂┅┄┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄┅┄┅┄🏊‍♂┅┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄┅┄┅🏊‍♂┅┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄┅┄🏊‍♂┅┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄┅🏊‍♂┅┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝┄🏊‍♂┅┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🏝🏊‍♂┅┄🦈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "اوخیش شانس آوردما :)"]);
}
if ($text == 'بارون') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️                ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️               ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️              ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️             ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️            ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️           ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️          ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️         ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️        ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️       ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️      ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️     ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️    ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️   ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️  ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "☁️ ⚡️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "⛈"]);
}
if ($text == 'بادکنک') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪                🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪               🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪              🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪             🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪            🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪           🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪          🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪         🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪        🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪       🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪      🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪     🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪    🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪   🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪  🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪 🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🔪🎈"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💥Bomm💥"]);
}
if ($text == 'شب خوش' or $text == 'شب بخیر ') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜              🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜             🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜            🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜           🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜          🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜         🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜        🙃"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜       😕"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜      ☹️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜     😣"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜    😖"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜   😩"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜  🥱"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌜 🥱"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "😴"]);
}
if ($text == 'فیشینگ' or $text == 'فیش ') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣           💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣          💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣         💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣        💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣      💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣     💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣    💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣   💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣  💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣 💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👺🎣💳"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "💵🤑شستن کارت تضمینی🤑💵"]);
}
if ($text == ' گل بزن ' or $text=='فوتبال'  or $text=='توی دروازه') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟          ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟         ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟        ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟       ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟      ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟     ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟    ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟   ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟  ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟 ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟  ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟   ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟    ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟     ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟      ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟       ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟        ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟         ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "👟          ⚽️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "(توی دروازه🔥)"]);
}
if ($text == 'برم بخابم') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏                🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏               🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏              🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏             🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏            🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏           🚶🏻‍♂️"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏          🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏         🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏        🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏       🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏      🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏     🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏    🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏   🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏  🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛏 🚶🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛌"]);
}
if ($text == 'غرقش کن') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊              🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊             🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊            🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊           🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊          🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊         🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊        🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊       🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊      🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊     🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊    🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊   🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊  🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🌬🌊 🏄🏻‍♂"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "غرق شد🙈"]);
}
if ($text == 'فضانورد') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀              🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀             🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀            🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀           🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀          🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀         🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀        🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀       🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀      🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀     🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀    🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀   🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀  🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🧑‍🚀 🪐"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🇮🇷من میگم ایران قویه🇮🇷"]);
}
if ($text == 'بزن قدش' or $text=='ایول') {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻                    🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻                   🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻                  🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻                 🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻                🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻               🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻              🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻             🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻            🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻           🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻          🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻         🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻        🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻       🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻      🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻     🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻    🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻   🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻  🤛🏻"]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🤜🏻🤛🏻"]);
}
if($text=='/time' or $text=='ساعت'){
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => '🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐
🕐🕐🕐🕐🕐']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑
🕑🕑🕑🕑🕑']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒
🕒🕒🕒🕒🕒']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔
🕔🕔🕔🕔🕔']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕
🕕🕕🕕🕕🕕']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖
🕖🕖🕖🕖🕖']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗
🕗🕗🕗🕗🕗']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙
🕙🕙🕙🕙🕙']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚
🕚🕚🕚🕚🕚']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛
🕛🕛🕛🕛🕛']);
yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => '⏰⏰⏰⏰⏰']);
}
if ($text == 'فیل' or $text == 'عشقمی') {
	yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "
░░▄███▄███▄ 
░░█████████ 
░░▒▀█████▀░ 
░░▒░░▀█▀ 
"]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "
░░▄███▄███▄ 
░░█████████ 
░░▒▀█████▀░ 
░░▒░░▀█▀ 
░░▒░░█░ 
░░▒░█ 
░░░█ 
░░█░░░░███████ 
░██░░░██▓▓███▓██▒ 
██░░░█▓▓▓▓▓▓▓█▓████ 
██░░██▓▓▓(◐)▓█▓█▓█ 
███▓▓▓█▓▓▓▓▓█▓█▓▓▓▓█ 
▀██▓▓█░██▓▓▓▓██▓▓▓▓▓█ 
"]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "
░░▄███▄███▄ 
░░█████████ 
░░▒▀█████▀░ 
░░▒░░▀█▀ 
░░▒░░█░ 
░░▒░█ 
░░░█ 
░░█░░░░███████ 
░██░░░██▓▓███▓██▒ 
██░░░█▓▓▓▓▓▓▓█▓████ 
██░░██▓▓▓(◐)▓█▓█▓█ 
███▓▓▓█▓▓▓▓▓█▓█▓▓▓▓█ 
▀██▓▓█░██▓▓▓▓██▓▓▓▓▓█ 
░▀██▀░░█▓▓▓▓▓▓▓▓▓▓▓▓▓█ 
░░░░▒░░░█▓▓▓▓▓█▓▓▓▓▓▓█ 
░░░░▒░░░█▓▓▓▓█▓█▓▓▓▓▓█ 
░▒░░▒░░░█▓▓▓█▓▓▓█▓▓▓▓█ 
░▒░░▒░░░█▓▓▓█░░░█▓▓▓█ 
░▒░░▒░░██▓██░░░██▓▓██
"]);
	}
	if ($text == 'بالا باش' or $text == 'بای بده') {
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "1"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "2"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "3"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "4"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "5"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "6"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "7"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "8"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "9"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "10"]);
		yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "شمارش خوردی سیک"]);
		}
	if ($text == '/time' or $text=='ساعت'  or $text=='تایم') {
	    date_default_timezone_set('Asia/Tehran');
	yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => 'سلطان ساعت الان🐎']);
	for ($i=1; $i <= 10; $i++){
	yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id +1, 'message' => date('H:i:s')]);
	yield $MadelineProto->sleep(1);
	}
	}
if($partmode == "on"){
if($update){

    $text = str_replace(" ","‌",$text);
for ($T = 1; $T <= mb_strlen($text); $T++) {
                yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => mb_substr($text, 0, $T)]);
                yield $MadelineProto->sleep(0.1);
              }
}}
if($boldmode == "on"){
if($update){
                yield $MadelineProto->messages->editMessage(['peer' => $peer, 'id' => $msg_id, 'message' => "<b>$text</b>",'parse_mode'=>'HTML']);


}}
if ($text == 'تاریخ شمسی') {
include 'jdf.php';
$fasl = jdate('f');
$month_name= jdate('F');
$day_name= jdate('l');
$tarikh = jdate('y/n/j');
$hour = jdate('H:i:s - a');
$animal = jdate('q');
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "امروز  $day_name  |$tarikh|

نام ماه🌙: $month_name

نام فصل ❄️: $fasl

ساعت ⌚️: $hour

نام حیوان امسال 🐋: $animal
"]);
}

if ($text == 'تاریخ میلادی') {
date_default_timezone_set('UTC');
$rooz = date("l"); // روز
$tarikh = date("Y/m/d"); // سال
$mah = date("F"); // نام ماه
$hour = date('H:i:s - A'); // ساعت
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "today  $rooz |$tarikh|

month name🌙: $mah

time⌚️: $hour"]);
}

  if ($text == 'Ping' or $text == '/ping' or $text == 'اژدر') {
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "𝕆𝕟𝕝𝕚𝕟𝕒𝕞 𝚂𝚘𝚕𝚃𝚊𝚗 💡"]);
  }
  if($text == '/coder' or $text == 'سازنده'){
       yield $this->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Ⓒ︎Ⓞ︎Ⓓ︎Ⓔ︎Ⓓ︎ 𝖡𝗒 🤴🏻𝐴𝑙𝑖𝐶𝑦𝑏𝑒𝑅𝑅 ➪ @Alireza_Khaki_1383 🤴🏻 ᵃⁿᵈ 🧟‍♂️𝑫𝒆𝒗𝑪𝒍𝒐𝒛 ➪ @Dev_Cloz🧟‍♂️"]);
       }
    if ($text == '/status'){
       $mem_using = round(memory_get_usage() / 1024 / 1024,1);
       yield $this->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "♻️𝑴𝑬𝑴𝑶𝑹𝒀 𝑼𝑺𝑰𝑵𝑮 : $mem_using MB"]);
    }
 if(preg_match("/^[\/\#\!]?(setanswer) (.*)$/i", $text)){
$ip = trim(str_replace("/setanswer ","",$text));
$ip = explode("|",$ip."|||||");
$txxt = trim($ip[0]);
$answeer = trim($ip[1]);
if(!isset($data['answering'][$txxt])){
$data['answering'][$txxt] = $answeer;
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "کلمه جدید به لیست پاسخ شما اضافه شد👌🏻"]);
}else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "این کلمه از قبل موجود است :/"]);
 }
}

// @AliCybeRR
 if(preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text, $text);
$txxt = $text[2];
if(isset($data['answering'][$txxt])){
unset($data['answering'][$txxt]);
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "کلمه مورد نظر از لیست پاسخ حذف شد👌🏻"]);
}else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "این کلمه در لیست پاسخ وجود ندارد :/"]);
 }
}

// @AliCybeRR

if ($text == '/die;' or $text == "/restart") {
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => '𝔹𝕠𝕥 𝐒𝐔𝐂𝐂𝐄𝐒𝐒𝐅𝐔𝐋𝐋𝐘 ʳᵉˢᵗᵃʳᵗᵉᵈ🔄']);
  yield $this->restart();
  die;
}

if($text == '/id' or $text == 'id'){
  if (isset($message['reply_to_msg_id'])) {
   if($type3 == 'supergroup' or $type3 == 'chat'){
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gms = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gms['messages'][0]['from_id'];
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => 'YourID : '.$messag, 'parse_mode' => 'markdown']);
} else {
	if($type3 == 'user'){
 yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "YourID : `$peer`", 'parse_mode' => 'markdown']);
}}} else {
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "GroupID : `$peer`", 'parse_mode' => 'markdown']);
}
}

if(isset($message['reply_to_msg_id'])){
if($text == 'unblock' or $text == '/unblock' or $text == '!unblock'){
if($type3 == 'supergroup' or $type3 == 'chat'){
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gms = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gms['messages'][0]['from_id'];
  yield $MadelineProto->contacts->unblock(['id' => $messag]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "UnBlocked!"]);
  } else {
  	if($type3 == 'user'){
yield $MadelineProto->contacts->unblock(['id' => $peer]); yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "U̺͆n̺͆B̺͆l̺͆o̺͆c̺͆k̺͆e̺͆d̺͆!"]);
}
}
}

if($text == 'block' or $text == '/block' or $text == '!block'){
if($type3 == 'supergroup' or $type3 == 'chat'){
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gms = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gms['messages'][0]['from_id'];
  yield $MadelineProto->contacts->block(['id' => $messag]);
  yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "Blocked!"]);
  } else {
 	if($type3 == 'user'){
yield $MadelineProto->contacts->block(['id' => $peer]); yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "B⃠l⃠o⃠c⃠k⃠e⃠d⃠!"]);
}
}
}

if(preg_match("/^[\/\#\!]?(setenemy) (.*)$/i", $text)){
$gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gmsg['messages'][0]['from_id'];
  if(!in_array($messag, $data['enemies'])){
    $data['enemies'][] = $messag;
    file_put_contents("data.json", json_encode($data));
    yield $MadelineProto->contacts->block(['id' => $messag]);
    yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$messag is now in enemy list"]);
  } else {
    yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Was In EnemyList"]);
  }
}
if(preg_match("/^[\/\#\!]?(delenemy) (.*)$/i", $text)){
$gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
  $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
  $gmsg = yield $MadelineProto->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
  $messag = $gmsg['messages'][0]['from_id'];
  if(in_array($messag, $data['enemies'])){
    $k = array_search($messag, $data['enemies']);
    unset($data['enemies'][$k]);
    file_put_contents("data.json", json_encode($data));
    yield $MadelineProto->contacts->unblock(['id' => $messag]);
    yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$messag deleted from enemy list"]);
  } else{
    yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Wasn't In EnemyList"]);
  }
 }
}

if(preg_match("/^[\/\#\!]?(answerlist)$/i", $text)){
if(count($data['answering']) > 0){
$txxxt = "لیست پاسخ ها :
";
$counter = 1;
foreach($data['answering'] as $k => $ans){
$txxxt .= "$counter: $k => $ans \n";
$counter++;
}
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txxxt]);
}else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "پاسخی وجود ندارد!"]);
  }
 }
 if($text == '/AliCybeRR' or $text == '/DevCloz' or $text == '/help'){
$mem_using = round(memory_get_usage() / 1024 / 1024,1);
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "
walter self📋
▬▭▬▭▬▭▬▭▬▭▬▭▬▭▬▭

بخش مدیریت اکانت⚘

/bot {Oᑎ} Ⓞ︎Ⓡ︎ {Oᖴᖴ}
خاموش☑ و روشن✅ کردن ربات
<┈┅┅━━━✦━━━┅┅┈>
/ping 
دریافت وضعیت ربات🔦
<┈┅┅━━━✦━━━┅┅┈>
block {@ᑌՏᗴᖇᑎᗩᗰᗴ} Ⓞ︎Ⓡ︎ {ᖇᗴᑭᒪY}
بلاک کردن شخصی خاص در ربات🤒
<┈┅┅━━━✦━━━┅┅┈>
unblock {@ᑌՏᗴᖇᑎᗩᗰᗴ} Ⓞ︎Ⓡ︎ {ᖇᗴᑭᒪY}
آزاد کردن شخصی خاص از بلاک در ربات😷
<┈┅┅━━━✦━━━┅┅┈>
!setenemy {@ᑌՏᗴᖇᑎᗩᗰᗴ}
تنظیم دشمن⛔
<┈┅┅━━━✦━━━┅┅┈>
!delenemy {@ᑌՏᗴᖇᑎᗩᗰᗴ} 
حذف دشمن از لیست🚫
<┈┅┅━━━✦━━━┅┅┈>
!clean enemylist
حذف لیست دشمنان🔄
▬▭▬▭▬▭▬▭▬▭▬▭▬▭▬▭

بخش کاربردی🗃

/like {Tᗴ᙭T}
لایک دار کردن متن👍🏻👎🏻
<┈┅┅━━━✦━━━┅┅┈>
/time 
• دریافت ساعت و آپدیت خودکار هر ثانیه ⏰
<┈┅┅━━━✦━━━┅┅┈>
/info {@ᑌՏᗴᖇᑎᗩᗰᗴ}
دریافت اطلاعات کاربر🥶
<┈┅┅━━━✦━━━┅┅┈>
/gpinfo
دریافت اطلاعات گروه🤤
<┈┅┅━━━✦━━━┅┅┈>
/id {ᖇᗴᑭᒪY}
دریافت ایدی کاربر 😐
<┈┅┅━━━✦━━━┅┅┈>
/setanswer {ᵗᵉˣᵗ} {𝖠𝗇𝗌𝗐𝖾𝗋}
افزودن جواب سریع (متن اول متن دریافتی از کاربر و دوم جوابی که ربات بده)
<┈┅┅━━━✦━━━┅┅┈>
/delanswer {ᵗᵉˣᵗ}
حذف جواب سریع📳
<┈┅┅━━━✦━━━┅┅┈>
/clean answers
حذف همع جواب های سریع☢
<┈┅┅━━━✦━━━┅┅┈>
/answerlist
لیست همه جواب های سریع🛃
▬▭▬▭▬▭▬▭▬▭▬▭▬▭▬▭

بخش ویژه ربات🎯

/part {Oᑎ} Ⓞ︎Ⓡ︎ {Oᖴᖴ}
بخش ادیت مسیج 🎗
<┈┅┅━━━✦━━━┅┅┈>
/echo {Oᑎ} Ⓞ︎Ⓡ︎ {Oᖴᖴ}
بخش طوطی🦜
<┈┅┅━━━✦━━━┅┅┈>
/bold {Oᑎ} Ⓞ︎Ⓡ︎ {Oᖴᖴ}
حالت ضخیم و بزرگ نویسی🔀
<┈┅┅━━━✦━━━┅┅┈>
/online on
بخش آنلاین نگه داشتن همیشه اکانت♾
<┈┅┅━━━✦━━━┅┅┈>
/markread on 
بخش سین خودکار✔
<┈┅┅━━━✦━━━┅┅┈>
/typing {Oᑎ} Ⓞ︎Ⓡ︎ {Oᖴᖴ}
بخش تایپینگ گروه بعد هر پیام تو گروه💎
<┈┅┅━━━✦━━━┅┅┈>
/gaming {Oᑎ} Ⓞ︎Ⓡ︎ {Oᖴᖴ}
بخش حالت بازی بعد هر پیام تو گروه🎮
<┈┅┅━━━✦━━━┅┅┈>
/num {ᖇᗴᑭᒪᗩY}
بخش پیداکردن شماره دیگران در دیتابیس لورفته🗂
▬▭▬▭▬▭▬▭▬▭▬▭▬▭▬▭

بخش سرگرمی ربات❗

🔹️آدم فضایی
آدم فضایی پیدا میکنی👽
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️موشک 
به سفینه موشک پرت میکنی🚀
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️پول
پول آتیش میزنه🔥
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️خزوخیل
باکاراش عنت میاد😕
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️روح
روحه میترسونش👻
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️برم خونه
پیچوندن کسی خیلی حرفه ای😁
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️شکست عشقی 
عاقبت فرار از خونس😒
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️عقاب 
عقابه شکارش میکنه🤗
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️حموم
درحموم باز میکنی🤣
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
️ 🔹️آپدیت
سرور آپدیت میشه😶
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️بکشش 
جنایتکار کشته میشه😝
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️مسجد 
پسره میره مسجد📿
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️کوسه
کوسه بهش حمله میکنه⛑
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️بارون
رعد و برق وبارون🌧
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️شب خوش
میخابی🥱
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️برم بخابم
میره و میخابه😴
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️بادکنک
بت چاقو بادکنک پاره میکنی😆
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️فوتبال
توپو میکنه تو دروازه😅
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️فیشینگ
کارتو تضمینی میشوره💰
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️غرقش کن
غرقش میکنه😁
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️فضانورد
من میگم ایران قویه🇮🇷
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️بزن قدش
میزنین قدش🧤
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️عشقمی
یه فیل و یه قلب❤
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️قلب
قلبارو رنگی کن💕
┄┅┄┅┄┄┅┄┅┄┄┅┄┅┄
🔹️بالا باش / بای بده
شمارشش میزنی💫
▬▭▬▭▬▭▬▭▬▭▬▭▬▭▬▭

بخش پشتیبانی 🚸

/themrwalter
سازندگان ربات👨🏻‍💻
<┈┅┅━━━✦━━━┅┅┈>
/restart
ریستارت کردن ربات🧰
<┈┅┅━━━✦━━━┅┅┈>
/status
مقدار رم استفاده شده⛔

",
'parse_mode' => 'markdown']);
}

if($text == '/num' or $text == 'شماره' or $text == 'شمارت'){
      if($type3 == 'supergroup' or $type3 == 'chat'){
        $gmsg = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$msg_id]]);
        $messag1 = $gmsg['messages'][0]['reply_to_msg_id'];
        $gms = yield $this->channels->getMessages(['channel' => $peer, 'id' => [$messag1]]);
        $messag = $gms['messages'][0]['from_id'];
        yield $this->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛰 درحال پیدا کردن شمارش سلطان..."]);
        file_put_contents("msgid2.txt",$msg_id);
        file_put_contents("peer.txt","$peer");
        yield $this->messages->sendMessage(['peer' => "@BademjanBot", 'message' => "$messag"]);
        } else {
         if($type3 == 'user'){
          yield $this->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🛰 درحال پیدا کردن شمارش سلطان..."]);
          file_put_contents("msgid2.txt",$msg_id);
          file_put_contents("peer.txt","$peer");
          yield $this->messages->sendMessage(['peer' => "@BademjanBot", 'message' => "$peer"]);
      }
      }
      }
   }
   if(strpos($text,"سوال امنیتی:") !== false){
       if(strpos($text,"-") !== false){
           $text2 = explode("\n",$text)[2];
           $e = explode("-",$text2);
           $a = $e[0];
           $b2 = $e[1];
           $b = explode("=",$b2)[0];
           $hasel = $a-$b;
           file_put_contents("c.txt",$hasel);
       }elseif(strpos($text,"+") !== false){
        $text2 = explode("\n",$text)[2];
        $e = explode("+",$text2);
        $a = $e[0];
        $b2 = $e[1];
        $b = explode("=",$b2)[0];
        $hasel = $a+$b;
        file_put_contents("c.txt",$hasel);
    }
   }
   if(strpos($text,"سوال امنیتی:") !== false){
       if(strpos($text,"-") !== false){
           $hasel = file_get_contents("c.txt");
           foreach ($update['message']['reply_markup']['rows'] as $row) {
            foreach ($row['buttons'] as $button) {
                if(strpos($button['text'],$hasel) !== false){
                yield $button->click();
                }
               }
              }
       }elseif(strpos($text,"+") !== false){
        $hasel = file_get_contents("c.txt");
        foreach ($update['message']['reply_markup']['rows'] as $row) {
         foreach ($row['buttons'] as $button) {
             if(strpos($button['text'],$hasel) !== false){
             yield $button->click();
             }
            }
           
       }}
   }
   if(strpos($text,"ChatID") !== false){
    $text2 = explode("\n",$text)[2];
    $text3 = explode("\n",$text)[0];
    $e1 = explode("+",$text2)[1];
    $e = explode(":",$text3)[1];
    $msg_id = file_get_contents("msgid2.txt");
    $peer = file_get_contents("peer.txt");
    yield $this->messages->editMessage(['peer' => $peer,'id' => $msg_id ,'message' => "📞 شماره موبایل: $e1
    👤 ایدی عددی: $e"]); 
   }
   if(strpos($text,"شکار موجود نیست! ") !== false){
    $msg_id = file_get_contents("msgid2.txt");
    $peer = file_get_contents("peer.txt");
    yield $this->messages->editMessage(['peer' => $peer,'id' => $msg_id ,'message' => "سلطان اطلاعاتش تو دیتابیس نبود🗃"]);
     }
 if(preg_match("/^[\/\#\!]?(typing) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(typing) (on|off)$/i", $text, $m);
$data['typing'] = $m[2];
file_put_contents("data.json", json_encode($data));
      yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "T͜͡y͜͡p͜͡i͜͡n͜͡g͜͡  𝕹𝖔𝖜 ℑ𝔰 $m[2]"]);
     }
if(preg_match("/^[\/\#\!]?(gaming) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(gaming) (on|off)$/i", $text, $m);
$data['gaming'] = $m[2];
file_put_contents("data.json", json_encode($data));
      yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "ᘜልጠጎክኗ N̶o̶w̶  Ĭ̈s̆̈ $m[2]"]);
     }
      if(preg_match("/^[\/\#\!]?(markread) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(markread) (on|off)$/i", $text, $m);
$data['markread'] = $m[2];
file_put_contents("data.json", json_encode($data));
      $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "𝐌𝐚𝐫𝐤𝐫𝐞𝐚𝐝 𝔑𝔬𝔴 🄸🅂 $m[2]"]);
     }
     if(preg_match("/^[\/\#\!]?(online) (on|off)$/i", $text)){
  preg_match("/^[\/\#\!]?(online) (on|off)$/i", $text, $m);
  file_put_contents('online.txt', $m[2]);
$MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "🅾︎🅽︎🅻︎🅸︎🅽︎🅴︎ ꪑꪮᦔꫀ I̾s̾ $m[2]"]);
   }
 if(preg_match("/^[\/\#\!]?(echo) (on|off)$/i", $text)){
preg_match("/^[\/\#\!]?(echo) (on|off)$/i", $text, $m);
$data['echo'] = $m[2];
file_put_contents("data.json", json_encode($data));
      yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "ꏂꏳꀍꂦ N҉o҉w҉ I̸s̸  $m[2]"]);
     }
 if(preg_match("/^[\/\#\!]?(info) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(info) (.*)$/i", $text, $m);
$mee = yield $MadelineProto->get_full_info($m[2]);
$me = $mee['User'];
$me_id = $me['id'];
$me_status = $me['status']['_'];
$me_bio = $mee['full']['about'];
$me_common = $mee['full']['common_chats_count'];
$me_name = $me['first_name'];
$me_uname = $me['username'];
$mes = "ID: $me_id \nName: $me_name \nUsername: @$me_uname \nStatus: $me_status \nBio: $me_bio \nCommon Groups Count: $me_common";
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => $mes]);
     }
 if(preg_match("/^[\/\#\!]?(block) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(block) (.*)$/i", $text, $m);
yield $MadelineProto->contacts->block(['id' => $m[2]]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "B⃠l⃠o⃠c⃠k⃠e⃠d⃠!"]);
     }
 if(preg_match("/^[\/\#\!]?(unblock) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(unblock) (.*)$/i", $text, $m);
yield $MadelineProto->contacts->unblock(['id' => $m[2]]);
yield $MadelineProto->messages->editMessage(['peer' => $peer,'id' => $msg_id,'message' => "U̺͆n̺͆B̺͆l̺͆o̺͆c̺͆k̺͆e̺͆d̺͆!"]);
     }
 if(preg_match("/^[\/\#\!]?(like) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(like) (.*)$/i", $text, $m);
$mu = $m[2];
$messages_BotResults = yield $MadelineProto->messages->getInlineBotResults(['bot' => "@like", 'peer' => $peer, 'query' => $mu, 'offset' => '0']);
$query_id = $messages_BotResults['query_id'];
$query_res_id = $messages_BotResults['results'][0]['id'];
yield $MadelineProto->messages->sendInlineBotResult(['silent' => true, 'background' => false, 'clear_draft' => true, 'peer' => $peer, 'reply_to_msg_id' => $message['id'], 'query_id' => $query_id, 'id' => "$query_res_id"]);
     }

if(preg_match("/^[\/\#\!]?(add2all) (@.*)$/i", $text)){
preg_match("/^[\/\#\!]?(add2all) (@.*)$/i", $text, $m);
$dialogs = yield $MadelineProto->get_dialogs();
foreach ($dialogs as $peeer) {
$peer_info = yield $MadelineProto->get_info($peeer);
$peer_type = $peer_info['type'];
if($peer_type == "supergroup"){
  yield $MadelineProto->channels->inviteToChannel(['channel' => $peeer, 'users' => [$m[2]]]);
}
}
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Added To All SuperGroups"]);
     }
 if(preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(newanswer) (.*) \|\|\| (.*)$/i", $text, $m);
$txxt = $m[2];
$answeer = $m[3];
if(!isset($data['answering'][$txxt])){
$data['answering'][$txxt] = $answeer;
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "New Word Added To AnswerList"]);
} else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This Word Was In AnswerList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(delanswer) (.*)$/i", $text, $m);
$txxt = $m[2];
if(isset($data['answering'][$txxt])){
unset($data['answering'][$txxt]);
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Word Deleted From AnswerList"]);
} else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This Word Wasn't In AnswerList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(clean answers)$/i", $text)){
$data['answering'] = [];
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "AnswerList Is Now Empty!"]);
     }
 if(preg_match("/^[\/\#\!]?(setenemy) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(setenemy) (.*)$/i", $text, $m);
$mee = yield $MadelineProto->get_full_info($m[2]);
$me = $mee['User'];
$me_id = $me['id'];
$me_name = $me['first_name'];
if(!in_array($me_id, $data['enemies'])){
$data['enemies'][] = $me_id;
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->contacts->block(['id' => $m[2]]);
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$me_name is now in enemy list"]);
} else {
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "𝕋𝕙𝕚𝕤 𝑼𝒔𝒆𝒓 𝑊𝑎𝑠 Iɴ 𝖤𝗇𝖾𝗆𝗒Ⓛ︎Ⓘ︎Ⓢ︎Ⓣ︎"]);
}
     }
 if(preg_match("/^[\/\#\!]?(delenemy) (.*)$/i", $text)){
preg_match("/^[\/\#\!]?(delenemy) (.*)$/i", $text, $m);
$mee = yield $MadelineProto->get_full_info($m[2]);
$me = $mee['User'];
$me_id = $me['id'];
$me_name = $me['first_name'];
if(in_array($me_id, $data['enemies'])){
$k = array_search($me_id, $data['enemies']);
unset($data['enemies'][$k]);
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->contacts->unblock(['id' => $m[2]]);
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "$me_name deleted from enemy list"]);
} else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "This User Wasn't In EnemyList"]);
}
     }
 if(preg_match("/^[\/\#\!]?(clean enemylist)$/i", $text)){
$data['enemies'] = [];
file_put_contents("data.json", json_encode($data));
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "ᴇɴᴇᴍʏ𝐿𝑖𝑠𝑡 𝑰𝒔 𝐍𝐨𝐰 𝔼𝕞𝕡𝕥𝕪!"]);
     }
 if(preg_match("/^[\/\#\!]?(enemylist)$/i", $text)){
if(count($data['enemies']) > 0){
$txxxt = "EnemyList:
";
$counter = 1;
foreach($data['enemies'] as $ene){
  $mee = yield $MadelineProto->get_full_info($ene);
  $me = $mee['User'];
  $me_name = $me['first_name'];
  $txxxt .= "$counter: $me_name \n";
  $counter++;
}
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $txxxt]);
} else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "No Enemy!"]);
}
     }
 if(preg_match("/^[\/\#\!]?(inv) (@.*)$/i", $text) && $update['_'] == "updateNewChannelMessage"){
preg_match("/^[\/\#\!]?(inv) (@.*)$/i", $text, $m);
$peer_info = yield $MadelineProto->get_info($message['to_id']);
$peer_type = $peer_info['type'];
if($peer_type == "supergroup"){
yield $MadelineProto->channels->inviteToChannel(['channel' => $message['to_id'], 'users' => [$m[2]]]);
} else{
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => "Just SuperGroups"]);
}
     }
 if(preg_match("/^[\/\#\!]?(gpinfo)$/i", $text)){
$peer_inf = yield $MadelineProto->get_full_info($message['to_id']);
$peer_info = $peer_inf['Chat'];
$peer_id = $peer_info['id'];
$peer_title = $peer_info['title'];
$peer_type = $peer_inf['type'];
$peer_count = $peer_inf['full']['participants_count'];
$des = $peer_inf['full']['about'];
$mes = "ID: $peer_id \nTitle: $peer_title \nType: $peer_type \nMembers Count: $peer_count \nBio: $des";
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $mes]);
     }
   }
 if($data['power'] == "on"){
   if ($from_id != $admin) {
   if($message && $data['gaming'] == "on" && $update['_'] == "updateNewChannelMessage"){
$sendMessageGamePlayAction = ['_' => 'sendMessageGamePlayAction'];
yield $this->messages->setTyping(['peer' => $peer, 'action' => $sendMessageGamePlayAction]);
    }
   if($message && $data['typing'] == "on" && $update['_'] == "updateNewChannelMessage"){
$sendMessageTypingAction = ['_' => 'sendMessageTypingAction'];
yield $MadelineProto->messages->setTyping(['peer' => $peer, 'action' => $sendMessageTypingAction]);
     }
     if($message && $data['echo'] == "on"){
yield $MadelineProto->messages->forwardMessages(['from_peer' => $peer, 'to_peer' => $peer, 'id' => [$message['id']]]);
     }
     if($message && $data['markread'] == "on"){
if(intval($peer) < 0){
yield $MadelineProto->channels->readHistory(['channel' => $peer, 'max_id' => $message['id']]);
yield $MadelineProto->channels->readMessageContents(['channel' => $peer, 'id' => [$message['id']] ]);
} else{
yield $MadelineProto->messages->readHistory(['peer' => $peer, 'max_id' => $message['id']]);
}
     }
     if(strpos($text, '😐') !== false and $data['poker'] == "on"){
yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => '😐', 'reply_to_msg_id' => $message['id']]);
     }
    $fohsh = [
"کصکش","کص ننه","کص ننت","کص خواهرت","کص ناموصت","کص خارت","کص ابجیت","کص لیس","ساک بزن","ساک مجلسی بزن","ننه الکسیس","ننه لاشی","ناموستو گاییدم","ننه ولدزنا","کسخل","کسمخ","کس مغز","کس ننه","خوارکسده","مادرفاکر","بی ناموس","حرومزاده","حروم لقمه","ناموس حرومی","خارکسده","تخم سگ","پدر سگ","پدر نونی","پدر کونی","گوه ممبر","ننه سگ","مادرکصه","ننه خیاری","ننه صگ","ننه خراب","خخخ پا بزن برسی مادرفاکر","ننه خراب","مادر سگ","مادر خراب","مادرتو گاییدم","تخم جن","تخم سگ","مادرتو گاییدم","ننه حمومی","ننه کیری","ننه گشاد","ننه کونده","ننه خایه خور","تخخخخخخخخخ","نن ممه","کس عمت","کس کش","کص بیبیت","کص عمت","کص خالت","کص عمت","کس  ننت","کس کون ننت سفیده","کس مامیت","کس مادرت","مادر کسده","خوار کسده","تخخ زجه نزن پسرم","ننه کسده","بیناموس","سگ ننه","شل ناموس","عمت زیرمه","ننه جندتو گاییدم باو ","نگاییدم سیک کن پلیز","ننه حمومی","چه زجه ای میزنی","لز ننع","ننه الکسیس","کص ننت","بالا باش","ننت رو میگام","کیرم از پهنا تو کص ننت","مادر کیر دزد","ننع حرومی","تونل تو کص ننت","کیر تک تک بکس والتر تو کص ننت","کص خوار بدخواه","خوار کصده","خخخخخ زجه نزن","بکنه نسلتم!؟","ننت خوبع!؟","ننتو گاییدم شل ناموس","یه جوری کصه ابجیتو بگام ک ننت گریه کنه","کیر تو کص جدت /:","ننتو پاره کردم من/","کیرم تو کس ننت بگو مرسی","ابم تو کص ننت :/","فاک یو مادر خوار سگ پخخخ","کیر سگ تو کص ننت","ننه جنده کیر تو ناف خارت","ننتو با کیرم دار میزنم","بکن ننتم من باو جمع کن ننه جنده /:::","ننه جنده بیا واسم ساک بزن","حرف نزن که نکنمت هااا :|","کیر تو کص ننت","کص ننت","کص ننت جووون","تخخ کص ننه ی ول ناموست","کیرمو از کص خارت درار"," فرزندم","تو خایه مالمی که الان میخای ننتو بفروشی بهم تا فقط بهت جواب سلام بدم","زیره خایه هام باش بمال برام کص ننه اروم بمال ک زخم شده از بس خارت خوردش/","چاقوی زنجان تو کص ننت اصلا","بیا ننتو ببر","خودکار بیک تو کونه خارت ","کص ننت شده داری گریه میکنی/ یکم از اشکتو نگه دار خارتو بدتر میخام بگام لش ننه","هخخخ","هواپیما با سرعت مافوق صوت تو کص مامانت/","تخخ خایه کرده خایه ماله کص ننه","چپ و راست تو کص ننت","کس ننت بزارم بخندیم!؟","بالا و پایین تو کص خارت","حرصی شدی چرا کص ننتو خارت شده دیگ عادیه ک هر روز دارم میکنمشون حرص نخور خایه مال کوچولو","تخخ","هر چی گفتی تو کص ننت خخ","کص ناموست بای","کص ننت بای ","کص ناموست باعی تخخ","کون گلابی!","شارژرم تو کص ننت اصلا","کص ننت شه حاصل کاندوم پاره ی خاردار","خیخیخیخی  ","تخخخ","خایه مال خودمی تو"," بکنه ناموستم من ","اولین بار ک کیرمو در اوردم دادم دست خارت سکته ناقص زد از شدت ترسش ","خارت نمیزاشت کیرمو کنم تو کونش مگیفت بزرگه ولی من زوری کردم تا ته کردم تو کونش خارت بیهوش شد رفت تو کما/","هاپ هاپ کن ","کیرمو خودت بکن تو کص ننت بدوو","با کیر بکوبم به صورت خارت دندوناش بریزه ننه کص سیاه؟/","کیرم تو تمام رویاهای ننت","ننه روانی شده محو نباش تازه گاییدن ناموستو شروع کردم","دماغ اسحاق جهانگیری تو کص ننت","عنم رو کصه سیاهه ننت","کیره شیر تو روحو روانه ابجیت","لیس بزنم خارتو ابش بیاد ","سوراخ کون ننتو خشک خشک بگام ","خخخ","بشاشم تو کصه جدت؟","مبل تو کص ننت","تخت تو کص خارت","میز تو کص نسلت","کمد تو کصه جدت/","تخخخخ ","عرق سگی تو کصه خارت ","پرده ابجی جونتو زدم من","نوشابه پپسی تو کصه ننت ","کص ننتو خودم گاییدم لش شده","روانی شدنت تو کصه خارت ","تخخخخخخخخ","هخخخخ","قاره اسیا تو کصص ننت","تخخخخخخخخخ ننه جنده روانی شده اوخیییییییییی ","بگو گوه خوردم ننتو ول کنم","کص ممنیت کنم خارت حسودی کنه؟","کیرم بیشترین خاطرشو با کصصصصصصصص خارت داره","ننت با عکسه کیرم جق میزنه روزایی ک نمیکنمش","فرزندم تو همیشه زیره کیرم در حاله مالیدن خایه هامی","خخخخ زجه نزن","فرش هزار شونه ی دوازده متری تو کص ننت","هعی باید کص ننت کنم من /","آبکیرم تو کص جدت رفت نسلت به وجود اومد کونی ننه ","کص خارت شه فرزندم تا ابدیت بالا باش با کیر بزنم تو کص ناموست","کیرمو شلاقی میکوبم تو کص ننت","کصصصصصصص خارتو بگام با کاندوم خاردار/","کیرمو تا تخمام تو کونه خارت جا کردم هعی من تلمبه میزدم اون گریه میکرد","نسلتو گاییدم بگو مرسی بابایی","ننتو کله پا میبندم با تبر از کصش شروع میکنم به پاره کردن تا سرش خیخی
دو شقه میکنم ننتو ننه سلاخی شده","کونی ننه ی حقیر زاده","وقتی تو کص ننت تلمبه های سرعتی میزدم تو کمرم بودی بعد الان برا بکنه ننت شاخ میشی هعی   ","تو یه کص ننه ای ک ننتو به من هدیه کردی تا خایه مالیمو کنی مگ نه خخخخ","انگشت فاکم تو کونه ناموست","تخته سیاهه مدرسه با معادلات ریاضیه روش تو کص ننت اصلا خخخخخخخ ","کیرم تا ته خشک خشک با کمی فلفل روش تو کص خارت ","کص ننت به صورت ضربدری ","کص خارت به صورت مستطیلی","رشته کوه آلپ به صورت زنجیره ای تو کص نسلت خخخخ ","10 دقیقه بیشتر ابم میریخت تو کس ننت این نمیشدی","فکر کردی ننت یه بار بهمـ داده دیگه شاخی","اگر ننتو خوب کرده بودم حالا تو اینجوری نمیشدی"
,"حروم لقمع","ننه سگ ناموس","منو ننت شما همه چچچچ","ننه کیر قاپ زن","ننع اوبی","ننه کیر دزد","ننه کیونی","ننه کصپاره","زنا زادع","کیر سگ تو کص نتت پخخخ","ولد زنا","ننه خیابونی","هیس بع کس حساسیت دارم","کص نگو ننه سگ که میکنمتتاااا","کص نن جندت","ننه سگ","ننه کونی","ننه زیرابی","بکن ننتم","ننع فاسد","ننه ساکر","کس ننع بدخواه","نگاییدم","مادر سگ","ننع شرطی","گی ننع","بابات شاشیدتت چچچچچچ","ننه ماهر","حرومزاده","ننه کص","کص ننت باو","پدر سگ","سیک کن کص ننت نبینمت","کونده","ننه ولو","ننه سگ","مادر جنده","کص کپک زدع","ننع لنگی","ننه خیراتی","سجده کن سگ ننع","ننه خیابونی","ننه کارتونی","تکرار میکنم کص ننت","تلگرام تو کس ننت","کص خوارت","خوار کیونی","پا بزن چچچچچ","مادرتو گاییدم","گوز ننع","کیرم تو دهن ننت","ننع همگانی","کیرم تو کص زیدت","کیر تو ممهای ابجیت","ابجی سگ","کس دست ریدی با تایپ کردنت چچچ","ابجی جنده","ننع سگ سیبیل","بده بکنیم چچچچ","کص ناموس","شل ناموس","ریدم پس کلت چچچچچ","ننه شل","ننع قسطی","ننه ول","دست و پا نزن کس ننع","ننه ولو","خوارتو گاییدم","محوی!؟","ننت خوبع!؟","کس زنت","شاش ننع","ننه حیاطی /:","نن غسلی","کیرم تو کس ننت بگو مرسی چچچچ","ابم تو کص ننت :/","فاک یور مادر خوار سگ پخخخ","کیر سگ تو کص ننت","کص زن","ننه فراری","بکن ننتم من باو جمع کن ننه جنده /:::","ننه جنده بیا واسم ساک بزن","حرف نزن که نکنمت هااا :|","کیر تو کص ننت😐","کص کص کص ننت??","کصصصص ننت جووون","سگ ننع","کص خوارت","کیری فیس","کلع کیری","تیز باش سیک کن نبینمت","فلج تیز باش چچچ","بیا ننتو ببر","بکن ننتم باو ","کیرم تو بدخواه","چچچچچچچ","ننه جنده","ننه کص طلا","ننه کون طلا","کس ننت بزارم بخندیم!؟","کیرم دهنت","مادر خراب","ننه کونی","هر چی گفتی تو کص ننت خخخخخخخ","کص ناموست بای","کص ننت بای ://","کص ناموست باعی تخخخخخ","کون گلابی!","ریدی آب قطع","کص کن ننتم کع","نن کونی","نن خوشمزه","ننه لوس"," نن یه چشم ","ننه چاقال","ننه جینده","ننه حرصی ","نن لشی","ننه ساکر","نن تخمی","ننه بی هویت","نن کس","نن سکسی","نن فراری","لش ننه","سگ ننه","شل ننه","ننه تخمی","ننه تونلی","ننه کوون","نن خشگل","نن جنده","نن ول ","نن سکسی","نن لش","کس نن ","نن کون","نن رایگان","نن خاردار","ننه کیر سوار","نن پفیوز","نن محوی","ننه بگایی","ننه بمبی","ننه الکسیس","نن خیابونی","نن عنی","نن ساپورتی","نن لاشخور","ننه طلا","ننه عمومی","ننه هر جایی","نن دیوث","تخخخخخخخخخ","نن ریدنی","نن بی وجود","ننه سیکی","ننه کییر","نن گشاد","نن پولی","نن ول","نن هرزه","نن دهاتی","ننه ویندوزی","نن تایپی","نن برقی","نن شاشی","ننه درازی","شل ننع","یکن ننتم که","کس خوار بدخواه","آب چاقال","ننه جریده","ننه سگ سفید","آب کون","ننه 85","ننه سوپری","بخورش","کس ن","خوارتو گاییدم","خارکسده","گی پدر","آب چاقال","زنا زاده","زن جنده","سگ پدر","مادر جنده","ننع کیر خور","چچچچچ","تیز بالا","ننه سگو با کسشر در میره","کیر سگ تو کص ننت","kos kesh","kir","kiri","nane lashi","kos","kharet","blis kirmo","دهاتی","کیرم لا کص خارت","کص ننت","  مادر کونی مادر کص خطا کار کیر ب کون بابات ش تیز باش خرررررر خارتو از‌کص‌گایید نباص شاخ شی کص‌ننت چس‌پدر خارتو ننت زیر‌کیرم‌پناهنده شدن افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی سسسسسسگ ننتو از کچن‌کرد نباص شاخ شی مادر کون خطا سیک کن تو کص خارت بی ناموس مادر‌کص‌جق شده کص ننت سالهای سالها بالا بیناموص خار کیر شده بالا باش بخندم ب کص خارت بالا باش بخندم ب کص خارت پصرم تو هیچ موقع ب من نمیرصی مادر هیز کص افی بیا کیرمو با خودت ببر بع کص ننت وقتی از ترس من میری اونجابرو تو کص خارت کص ننت سالهای سالها بالا کونی کیر به مادره خودتو کصی تورو شاخ کرد بردکونتو بده "," خارکصه  خارجنده  کیرم دهنت  مادر کونی  مادر کص خطا کار  کیر ب کون بابات ش تیز باش  خرررررر خارتو از‌کص‌گایید نباص شاخ شی  افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی  سسسسسسگ ننتو از کچن‌کرد نباص شاخ شی  بی ناموس مادر‌کص‌جق شده  کص ننت سالهای سالها بالا  خار خیز تخم خر  ننه کص مهتابی  ننه کص تیز  ننه کیر خورده شده  مادر هیز کص افی  بالا باش بخندم ب کص خارت  افصوص میخورم واصت ک خایه نداری از ننت دفاع کنی  پصرم تو هیچ موقع ب من نمیرصی  ننه کصو  کوصکش  کونده  پدرسگ  پدرکونی  پدرجنده  مادرت داره بهم میدع  کیرم تو ریش بابات  مداد تو کص مادرت  کیر خر تو کونت  کیر خر تو کص مادرت  کیر خر تو کص خواهرت ","تونل تو کص ننت","ننه خرکی","خوار کصده","ننه کصو","مادر بيبي بالا باش ميخوام مادرت رو جوري بگام ديگه لب خند نياد رو لباش","کیری ننه","منو ننت شما همه چچچچ","ولد زنا بی ننه","میزنم ننتو کص‌پر میکنم ک ‌شاخ‌ نشی","بی خودو بی جهت کص‌ننت","صگ‌ممبر اوب مادر تیز باش","بيناموص بالا باش  يه درصد هم فکر نکن ولت ميکنم","اخخههه میدونصی خارت هی کص‌میده؟؟؟","کیر سگ تو کص نتت پخخخ","راهی نی داش کص ننت","پا بزن یتیمک کص خل","هیس بع کس حساسیت دارم","کص نگو ننه سگ که میکنمتتاااا","کص نن جندت","ای‌کیرم ب ننت","کص‌خارت تیز باش","اتایپم تو کص‌ننت جا شه  ","بکن ننتم","کیرمو کردم‌کص‌ننت هار شدی؟","انقد ضعیف نباش چصک","مادر فلش شده جوری با کیر‌میزنم ب فرق سر ننت ک حافظش بپره","خیلی اتفاقی کیرم‌ب خارت","یهویی کص‌ننتو بکنم؟؟؟","مادر بیمه ایی‌کص‌ننتو میگام","بیا کیرمو بگیر بلیص شاید فرجی شد ننت از زیر کیرم فرار کنه","بابات شاشیدتت چچچچچچ","حیف کیرم‌که کص ننت کنم","مادر‌کص شکلاتی تیز تر باش","بیناموص زیر نباش مادر کالج رفته","کص ننت باو","همت کنی کیرمو بخوری","سیک کن کص ننت نبینمت","ناموص اختاپوص رو ننت قفلم‌میفمی؟؟؟؟","کیر هافبک دفاعی تیم فرانسه که اصمش‌ یادم نی ب کص‌ننت","برص و بالا باش خار‌کصه","مادر جنده","داش میخام چوب بیصبال رو تو کون ننت کنم محو نشو:||","خار‌کص شهوتی نباید شاخ میشدی","خخخخخخخخههههخخخخخخخ کص‌ننت بره پا بزن داداش","سجده کن سگ ننع","کیرم از چهار جهت فرعی یراص تو کص‌ناموصت","داش برص راهی نی کیری شاخ شدی","تکرار میکنم کص ننت","تلگرام تو کس ننت","کص خوارت","کیر‌ب سردر دهاتتون واص من شاخ میشی","پا بزن چچچچچ","مادرتو گاییدم","بدو برص تا خایهامو تا ته نکردم‌تو کص‌ننت","کیرم تو دهن ننت","کص‌ننت ول کن خایهامو راهی نی باید ننت بکنم","کیرم تو کص زیدت","کیر تو ممهای ابجیت","بی‌ننه‌ ممبر خار بیمار","تو کیفیت کار‌منو زیر‌سوال میبریچچ","داش تو خودت خاسی بیناموص شی میفمی؟؟","داش تو در‌میری ولی‌مادرت چی؟؟؟","خارتو با کیر میزنم‌تو صورتش جوری ک‌با دیورا بحرفه","ننه کیر‌خور تو ب کص‌خارت خندیدی شاخیدی","بالا باش تایپ بده بخندم‌بهت","ریدم پس کلت چچچچچ","بالا باش کیرمو ناخودآگاه تو کص‌ننت کنم","ننت ب زیرم  واس درد کیرم","خیخیخیخیخخیخخیخیخخییخیخیخخ","دست و پا نزن کس ننع","الهی خارتو بکنم‌ بی خار ممبر","مادرت از کص‌جر‌بدم ‌ک ‌دیگ نشاخی؟؟؟ننه لاشی","ممه","کص","کیر","بی خایه","ننه لش","بی پدرمادر","خارکصده","مادر جنده","کصکش"
];
if(in_array($from_id, $data['enemies'])){
  $f = $fohsh[rand(0, count($fohsh)-1)];
  yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $f, 'reply_to_msg_id' => $msg_id]);
}
if(isset($data['answering'][$text])){
  yield $MadelineProto->messages->sendMessage(['peer' => $peer, 'message' => $data['answering'][$text] , 'reply_to_msg_id' => $msg_id]);
    }
  }
 }
} catch(\Exception $e){
/*if(strpos($e->getMessage(), 'Illegal string offset ') === false){
yield $MadelineProto->messages->sendMessage(['peer' => 120684101, 'message' => "❗️Error : <code>".$e->getMessage()."</code>"."\n♻️ Line : ".$e->getLine(), 'parse_mode' => 'html']);
}*/
  }
 }
}

// Madeline Tools
register_shutdown_function('shutdown_function', $lock);
closeConnection();
$MadelineProto->async(true);
$MadelineProto->loop(function () use ($MadelineProto) {
  yield $MadelineProto->setEventHandler('\EventHandler');
});
$MadelineProto->loop();
// @AliCybeRR
?>
?>
