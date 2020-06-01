<?php
echo "Hello world";
include "Telegram.php";

$telegram = new Telegram('1195437027:AAGEvKgzU1ZsSpK7M5wQFzKp75sI3Tqa3zY');
$data = $telegram->getData();
$message = $data['message'];
$chat_id = $message['chat']['id'];
$text = $message['text'];
$fileName = 'users/step.txt';
/*$telegram->sendMessage([
    'chat_id' => $chat_id,
    'text' => json_encode($data, JSON_PRETTY_PRINT)
]);*/
$orderTypes = [
    "1kg - 50 000 so'm",
    "1.5kg(1L) - 75 000 so'm",
    "4.5kg(3L) - 220 000 so'm",
    "7.5kg(5L) - 370 000 so'm"
];

switch ($text) {
    case "/start":
        showStart();
        break;
    case "🍯 Batafsil ma'lumot":
        showAbout();
        break;
    case "🍯 Buyurtma berish":
        showOrder();
        break;
    case "✈️ Yetkazib berish ✈":
        showSendType();
        break;
    case "🍯 Borib olish 🍯":
        showGo();
        break;
    case "Lokatsiya jo'nata olmayman":
        showGo();
        break;
    case "Boshqa buyurtma berish":
        showStart();
        break;
    case "⬅️ Orqaga":
        switch (file_get_contents('users/step.txt')) {
            case "typesOrder":
                homeMenu();
                break;
            default:
                $telegram->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => 'hello'
                ]);

        }
        break;
    default:
        if (in_array($text, $orderTypes, true)) {
            file_put_contents('users/massa.txt', $text);
            askContact();
        } else {
            switch (file_get_contents('users/step.txt')) {
                case 'phone':
                    saveContact();
                    showDeliveryType();
                    break;
                case 'location':
                    saveLocation();
                    break;

            }
        }
        break;
}
function showStart()
{
    global $telegram, $chat_id, $message;
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("🍯 Batafsil ma'lumot")],
        //Second row
        [$telegram->buildKeyBoardButton("🍯 Buyurtma berish")],
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'text' => "Assalom alaykum, " . $message['chat']['first_name'] . " " . $message['chat']['last_name'] . "\nUshbu bot orqali siz BeeO asal-arichilik firmasidan tabiiy asal va  asal mahsulotlarini sotib olishingiz mumkin!");
    $telegram->sendMessage($content);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Mening ismim Jamshid, ko`p yillardan beri oilaviy arichilik bilan shug`illanib kelamiz!\nBeeO -asalchilik firmamiz mana 3 yildirki, Toshkent shahri aholisiga toza, tabiiy asal yetkizib bermoqda va ko`plab xaridorlarga ega bo`ldik, shukurki, shu yil ham arichiligimizni biroz kengaytirib siz azizlarning ham dasturxoningizga tabiiy-toza asal yetkazib berishni niyat qildik!");
    $telegram->sendMessage($content);
}

function showAbout()
{
    global $telegram, $chat_id;
    $content = array('chat_id' => $chat_id, 'parse_mode' => 'html', 'text' => "Biz haqimizda ma'lumot. <a href='https://telegra.ph/Biz-haqimizda-05-28'>Batafsil</a> ");
    $telegram->sendMessage($content);
}

function showOrder()
{
    global $telegram, $chat_id;
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("1kg - 50 000 so'm")],
        //Second row
        [$telegram->buildKeyBoardButton("1.5kg(1L) - 75 000 so'm")],
        //Third row
        [$telegram->buildKeyBoardButton("4.5kg(3L) - 220 000 so'm")],
        //Fourth row
        [$telegram->buildKeyBoardButton("7.5kg(5L) - 370 000 so'm")],

        [$telegram->buildKeyBoardButton("⬅️ Orqaga")],
    ];
    file_put_contents('users/step.txt', 'typesOrder');
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Buyurtma berish uchun hajmlardan birini tanlang yoki o'zingiz hohlagan hajmni kiriting. ");
    $telegram->sendMessage($content);
}

function askContact()
{
    global $telegram, $chat_id;
    file_put_contents('users/step.txt', 'phone');
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("Raqamni jo'natish", true)],
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Hajm tanlandi, endi telefon raqamingnizni kiritsangiz. ");
    $telegram->sendMessage($content);
}

function saveContact()
{
    global $text, $message;
    if (!empty($message['contact']['phone_number'])) {
        file_put_contents('users/phone.txt', $message['contact']['phone_number']);
    } else {
        file_put_contents('users/phone.txt', $text);
    }
}

function showDeliveryType()
{
    global $telegram, $chat_id;
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("✈️ Yetkazib berish ✈")],
        //Second row
        [$telegram->buildKeyBoardButton("🍯 Borib olish 🍯")],
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Bizda Toshkent shahri bo'ylab yetkazib berish xizmati mavjud. Yoki,\no'zingiz tashrif buyurib olib ketishingiz mumkin!\nManzil: Toshkent sh, Olmazor tum. Talabalar shaharchasi.");
    $telegram->sendMessage($content);
}

function showSendType()
{
    global $telegram, $chat_id;
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("Lokatsiyani jo'natish", false, $request_location = true)],
        //Second row
        [$telegram->buildKeyBoardButton("Lokatsiya jo'nata olmayman")],
    ];
    file_put_contents('users/step.txt', 'location');
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Yaxshi, endi, lokatsiya jo'nating!");
    $telegram->sendMessage($content);
}

function showGo()
{
    global $telegram, $chat_id;
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("Boshqa buyurtma berish")],
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Sizning buyurtmangiz qabul qilindi. Tez orada siz bilan bog'lanamiz.\n Murojaatingiz uchun rahmat! 😊");
    $telegram->sendMessage($content);
}

function saveLocation()
{
    global $chat_id, $message, $telegram;
    if (!empty($message['location'])) {
        file_put_contents('users/location.txt', $message['location']['latitude'] . ' ' . $message['location']['longitude']);
        $option = [
            //First row
            [$telegram->buildKeyBoardButton("Boshqa buyurtma berish")],
        ];
        $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
        $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "Sizning buyurtmangiz qabul qilindi. Tez orada siz bilan bog'lanamiz.\n Murojaatingiz uchun rahmat! 😊");
        $telegram->sendMessage($content);
    }
}

function homeMenu(){
    global $telegram, $chat_id, $message;
    $option = [
        //First row
        [$telegram->buildKeyBoardButton("🍯 Batafsil ma'lumot")],
        //Second row
        [$telegram->buildKeyBoardButton("🍯 Buyurtma berish")],
    ];
    $keyb = $telegram->buildKeyBoard($option, $onetime = false, $resize = true);
    $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb);
    $telegram->sendMessage($content);
}
?>