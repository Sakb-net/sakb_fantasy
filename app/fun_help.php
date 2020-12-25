<?php
function Time_Elapsed_String($datetime, $lang = "", $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;
    if ($lang == "ar" || $lang == "Arabic" || $lang == "arabic") {
        $string = array(
            'y' => 'سنة',
            'm' => 'شهر',
            'w' => 'أسبوع',
            'd' => 'يوم',
            'h' => 'ساعة',
            'i' => 'دقيقة',
            's' => 'ثانية',
        );
    } else {
        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
    }
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            if ($lang == "ar" || $lang == "Arabic" || $lang == "arabic") {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            }
        } else {
            unset($string[$k]);
        }
    }
    if (!$full)
        $string = array_slice($string, 0, 1);
    if ($lang == "ar" || $lang == "Arabic" || $lang == "arabic") {
        return $string ? 'منذ ' . implode(', ', $string) : 'الآن';
    } else {
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}

function Wo_ShareFile($data = array(), $type = 0) {
    $allowed = '';
    if (!file_exists('uploads/files')) {
        @mkdir('uploads/files', 0777, true);
    }
    if (!file_exists('uploads/photos')) {
        @mkdir('uploads/photos', 0777, true);
    }
    if (!file_exists('uploads/videos')) {
        @mkdir('uploads/videos', 0777, true);
    }
    if (!file_exists('uploads/sounds')) {
        @mkdir('uploads/sounds', 0777, true);
    }
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = $data['file'];
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = $data['name'];
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = $data['name'];
    }
    if (empty($data)) {
        return false;
    }
    if (isset($data['types'])) {
        $allowed = $data['types'];
    } else {
        $allowed = 'jpg,png,jpeg,gif';
    }
    $new_string = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif') {
        $folder = 'photos';
        $fileType = 'image';
    } else if ($file_extension == 'mp4' || $file_extension == 'mov' || $file_extension == 'webm' || $file_extension == 'flv') {
        $folder = 'videos';
        $fileType = 'video';
    } else if ($file_extension == 'm4a' || $file_extension == 'mp3' || $file_extension == 'wav') {
        $folder = 'sounds';
        $fileType = 'soundFile';
    } else {
        $folder = 'files';
        $fileType = 'file';
    }
    if (empty($folder) || empty($fileType)) {
        return false;
    }
    $dir = "uploads/{$folder}";
    $filename = $dir . '/' . md5(time()) . "_{$fileType}.{$file_extension}";
    $second_file = pathinfo($filename, PATHINFO_EXTENSION);
    if (move_uploaded_file($data['file'], $filename)) {
        if ($second_file == 'jpg' || $second_file == 'jpeg' || $second_file == 'png' || $second_file == 'gif') {
            $check_file = getimagesize($filename);
            if (!$check_file) {
                unlink($filename);
            }
            $last_data = array();
            $last_data['filename'] = $filename;
            $last_data['name'] = $data['name'];
            return $last_data;
        }
    }
}

function GetSize_Image($source_url, $width, $height) {
    if (!empty($source_url)) {
        return $source_url; //eman delete
        $allimg_new = explode('uploads', $source_url);
        if (count($allimg_new) >= 2) {
            $ext = pathinfo($allimg_new[1], PATHINFO_EXTENSION);
            $img_new = explode('.' . $ext, $allimg_new[1]);
            $img_folder = explode('/', $img_new[0]);
            if (!empty($img_folder) && count($img_folder) == 3) {
                $value_new_image = $allimg_new[0] . 'uploads/' . $img_folder[1] . '/thumbs/' . $img_folder[2] . '-w-' . $width . '-h-' . $height . '.' . $ext;
            } else {
                $value_new_image = $allimg_new[0] . 'uploads/thumbs' . $img_new[0] . '-w-' . $width . '-h-' . $height . '.' . $ext;
            }
            return $value_new_image;
        } else {
            return $source_url;
        }
    } else {
        return $source_url;
    }
}

function Compress_Modify_Image($file_name, $nameImage, $state = 0) {
    //https://porject.com/uploads/thumbs/cppthumbnail-w-677-h-359.jpg
    $source_url = 'uploads/' . $nameImage;
    if (@getimagesize($source_url)) {
        $img_new = explode('.', $nameImage);
        $img_new_file = explode('/', $img_new[0]);
        $destination_url = $img_new[0] . $img_new[1];
        $info = getimagesize($source_url);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source_url);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source_url);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source_url);
        }
        list($width_min, $height_min) = getimagesize($source_url);
        if ($state == 1) {
            $array_width_height = array(
                '61' => 61
            );
        } else {
            $array_width_height = array(
                '2300' => 1000,
                '677' => 359,
                '273' => 182,
                '960' => 250,
                '61' => 61,
                '276' => 245,
                '130' => 130,
                '403' => 269,
                '479' => 270,
                '486' => 324,
            );
        }
        foreach ($array_width_height as $new_width_min => $new_height_min) {
            $new_width_min_name = $new_width_min;
            $new_height_min_name = $new_height_min;
            $quality = 100;
            $source_min = imagecreatetruecolor($new_width_min, $new_height_min);  //create frame compress image
            //uploads/thumbs/cppthumbnail-w-677-h-359.jpg
            if (count($img_new_file) == 2) {
                $destination_url = 'uploads/' . $img_new_file[0] . '/thumbs/' . $img_new_file[1] . '-w-' . $new_width_min_name . '-h-' . $new_height_min_name . '.' . $img_new[1];
            } else {
                $destination_url = 'uploads/thumbs/' . $img_new[0] . '-w-' . $new_width_min_name . '-h-' . $new_height_min_name . '.' . $img_new[1];
            }
            //compress image
            imagecopyresampled($source_min, $image, 0, 0, 0, 0, $new_width_min, $new_height_min, $width_min, $height_min);
            imagejpeg($source_min, $destination_url, $quality); //copy image to folder
            $im2 = imagecrop($source_min, ['x' => 0, 'y' => 0, 'width' => $new_width_min, 'height' => $new_height_min]);
            if ($im2 !== FALSE) {
                imagepng($im2, $destination_url);
                imagedestroy($im2);
            }
            imagedestroy($source_min);
        }
    } else {
        $destination_url = '';
    }
    return $destination_url;
}

function Compress_ImageSquare($source_url) {
    if (@getimagesize($source_url)) {
        $img_new = explode('.', $source_url);
//        $destination_url = $img_new[0] . '_compress.' . $img_new[1];
        $info = getimagesize($source_url);
        if ($info['mime'] == 'image/jpeg') {
            $image = imagecreatefromjpeg($source_url);
        } elseif ($info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($source_url);
        } elseif ($info['mime'] == 'image/png') {
            $image = imagecreatefrompng($source_url);
        }
        list($width_min, $height_min) = getimagesize($source_url);
        $quality = 100;
        $new_width_min = $width_min;
        $new_height_min = $height_min;
        if ($new_width_min < $new_height_min) {
            $new_height_min = $new_width_min;
        }
        if ($new_height_min < $new_width_min) {
            $new_width_min = $new_height_min;
        }
        if ($new_height_min >= 1000 || $new_height_min >= '1000') {
            $new_width_min = $new_height_min = 500;
        }
        $source_min = imagecreatetruecolor($width_min, $height_min);  //create frame compress image
        $destination_url = $source_url;
        imagecopyresampled($source_min, $image, 0, 0, 0, 0, $new_width_min, $new_height_min, $width_min, $height_min);
        imagejpeg($source_min, $destination_url, $quality); //copy image to folder

        $im2 = imagecrop($source_min, ['x' => 0, 'y' => 0, 'width' => $new_width_min, 'height' => $new_height_min]);
        if ($im2 !== FALSE) {
            imagepng($im2, $destination_url);
            imagedestroy($im2);
        }
        imagedestroy($source_min);
    } else {
        $destination_url = '';
    }
    return $destination_url;
}

function Compress_Image($source_url) {
    $img_new = explode('.', $source_url);
    $destination_url = $img_new[0] . '_compress.' . $img_new[1];
    $info = getimagesize($source_url);
    if ($info['mime'] == 'image/jpeg') {
        $image = imagecreatefromjpeg($source_url);
    } elseif ($info['mime'] == 'image/gif') {
        $image = imagecreatefromgif($source_url);
    } elseif ($info['mime'] == 'image/png') {
        $image = imagecreatefrompng($source_url);
    }
    list($width_min, $height_min) = getimagesize($source_url);
    $array_width_height = array(
        '350' => 80,
        '500' => 80,
        '1000' => 50,
    );
    foreach ($array_width_height as $new_width_min => $quality) {
        $new_width_min_name = $new_width_min;
        if ($width_min < $new_width_min) {
            $new_width_min = $width_min;
            $new_height_min = $height_min;
            $quality = 80;
        } else {
            $new_height_min = ($height_min / $width_min) * $new_width_min;
        }
        $source_min = imagecreatetruecolor($new_width_min, $new_height_min);  //create frame compress image
        $destination_url = $img_new[0] . '_compress_W' . $new_width_min_name . '.' . $img_new[1];
        //compress image
        imagecopyresampled($source_min, $image, 0, 0, 0, 0, $new_width_min, $new_height_min, $width_min, $height_min);
        imagejpeg($source_min, $destination_url, $quality); //copy image to folder
    }
    return $destination_url;
}

function sendPushNotification($fcm_token, $action, $message, $title) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"to\" : \"$fcm_token\",\n \t\"data\":{\n \t\t\"action\" : \"$action\"\n \t}\n  \n \t\"notification\" : \n \t{\n    \"body\" : \"$message\",\n    \"title\" : \"$title\",\n    \"sound\" : \"default\"\n    }\n  }",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: e878db90-0c24-d7d0-275f-440b5d06974d"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotificationTopicsToken($array_fcm_token, $type_subscribe, $action, $message, $title) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"to\" : \"/topics/$type_subscribe\",\n \"registration_tokens\" : \"$array_fcm_token\",\n  \"priority\" : \"high\",\n \t\"data\":{\n \t\t\"action\" : \"$action\"\n \t}\n  \"notification\" : {\n    \"body\" : \"$message\",\n    \"title\" : \"$title\"\n  }\n}",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 78c2d39e-8b77-f989-e36b-bcf28705a51f"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotificationTopics($type_subscribe, $action, $message, $title) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"to\" : \"/topics/$type_subscribe\",\n \"priority\" : \"high\",\n \t\"data\":{\n \t\t\"action\" : \"$action\"\n \t}\n   \"notification\" : {\n    \"body\" : \"$message\",\n    \"title\" : \"$title\"\n  }\n}",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 78c2d39e-8b77-f989-e36b-bcf28705a51f"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotificationData($fcm_token, $action, $title) {
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"content_available\" : true,\n    \"to\" : \"$fcm_token\",\n \t\"data\":{\n \t\t\"action\" : \"sign_out\"\n \t}\n  }",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 2cbcd306-d723-313f-6149-eca163d05953"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotificationSubscribe($array_fcm_token, $type_subscribe) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://iid.googleapis.com/iid/v1:batchAdd",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"to\" : \"/topics/$type_subscribe\",\n\n \t\"registration_tokens\" : [\"$array_fcm_token\"]\n  }",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 78c2d39e-8b77-f989-e36b-bcf28705a51f"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotificationUNSubscribe($array_fcm_token, $type_subscribe) {

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://iid.googleapis.com/iid/v1:batchRemove",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"to\" : \"/topics/$type_subscribe\",\n\n \t\"registration_tokens\" : [\"$array_fcm_token\"]\n  }",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 11cc6200-6cc4-cc64-6abf-622fefcbe26a"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotificationSubscribe_try($array_fcm_token, $type_subscribe, $action, $message, $title) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n  \"to\" : \"/topics/$type_subscribe\",\n \"registration_tokens\" : \"$array_fcm_token\",\n  \"priority\" : \"high\",\n \t\"data\":{\n \t\t\"action\" : \"$action\"\n \t}\n  \"notification\" : {\n    \"body\" : \"$message\",\n    \"title\" : \"$title\"\n  }\n}",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bGtWk5H1OLxx1lbyAGIOUAvDc8A23O2yXf6B_cTD0hQKPK5z-qn4G1LdIKXaCwl6n7HT3TggCjGYzSaCSZfzjXdGWtptwrFP7o4tHYRXAqicvaUFBWgILEOGEhjIIcgqjI6Pp6f",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 78c2d39e-8b77-f989-e36b-bcf28705a51f"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function sendPushNotification_Script($array_fcm_token, $type_subscribe) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://iid.googleapis.com/iid/v1:batchAdd",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => "{\n    \"to\" : \"/topics/$type_subscribe\",\n\n \t\"registration_tokens\" : [\"$array_fcm_token\"]\n  }",
        CURLOPT_HTTPHEADER => array(
            "authorization: key=emanAAAAxMeao5o:APA91bHITdvazyM4ZPZLtgweU0V4izHA7qsrNZM4UqqD17O-_oMf0NHmZOBZMA0braT0Bg1Uf6DGam1pvmZbOiiBVfuAlQL3H9IEuueoMtux-JRV-620BxjKALMpfwtkRXzvDBdXJ5Lq",
            "cache-control: no-cache",
            "content-type: application/json",
            "postman-token: 78c2d39e-8b77-f989-e36b-bcf28705a51f"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
//        echo "cURL Error #:" . $err;
        return "cURL Error #:" . $err;
    } else {
//        echo $response;
        return $response;
    }
}

function GetValueByKeyLetter($key) {
    $arabicLetter = array(
        'ا' => "1a",
        'آ' => "1a",
        'أ' => "1a",
        'إ' => "1a",
        'ب' => "2b",
        'ت' => "3t",
        'ث' => "4t",
        'ج' => "5g",
        'ح' => "6h",
        'خ' => "7k",
        'د' => "8d",
        'ذ' => "9z",
        'ر' => "10r",
        'ز' => "11z",
        'س' => "12s",
        'ش' => "13s",
        'ص' => "14s",
        'ض' => "15d",
        'ط' => "16t",
        'ظ' => "17z",
        'ع' => "18a",
        'غ' => "19g",
        'ف' => "20f",
        'ق' => "21t",
        'ك' => "22k",
        'ل' => "23l",
        'م' => "24m",
        'ن' => "25n",
        'هـ' => "26h",
        'ه' => "26h",
        'و' => "27w",
        'ى' => "28y",
        'ي' => "28y",
        'A' => "a",
        'B' => "b",
        'C' => "c",
        'D' => "d",
        'E' => "e",
        'F' => "f",
        'G' => "g",
        'H' => "h",
        'I' => "i",
        'J' => "j",
        'K' => "k",
        'L' => "l",
        'M' => "m",
        'N' => "n",
        'O' => "o",
        'P' => "p",
        'Q' => "q",
        'R' => "r",
        'S' => "s",
        'T' => "t",
        'U' => "u",
        'V' => "v",
        'W' => "w",
        'X' => "x",
        'Y' => "y",
        'Z' => "z",
    );
    $valueletter = isset($arabicLetter[$key]) ? $arabicLetter[$key] : '';
    return $valueletter;
}

function generateDefaultImage($user_name) {
    $firstLetterupper = $user_name;
    $firstLetterName = mb_substr($firstLetterupper, 0, 1, "UTF-8");
    $firstLettervalue = GetValueByKeyLetter($firstLetterName);
    if (!empty($firstLettervalue)) {
        $firstLetterName = $firstLettervalue;
    }
    //src="http://127.0.0.1:8000/images/flags/sa.png"
    $letterNameImage = '/images/user.png'; // '/images/letterAEName/' . $firstLetterName . '_copy.png'; // route('home') . 
    return $letterNameImage;
}

function generateFilterEmoji($text, $decode = 1) {
//use App\Models\Emoji;
    if ($decode = 1) {
        $result = \App\Models\Emoji::Decode($text);
    } else {
        $result = \App\Models\Emoji::Encode($text);
    }
    return $result;
}

function convertCurrency($amount, $from, $to) {
    $conv_id = "{$from}_{$to}";
    $string = file_get_contents("http://free.currencyconverterapi.com/api/v3/convert?q=$conv_id&compact=ultra");
    $json_a = json_decode($string, true);
    return $amount * round($json_a[$conv_id], 2);
}

function CurrencyType() {
    $type_array = array(
        'KWD' => trans('app.KWDCurrency'),
        'SAR' => trans('app.SARCurrency'),
        'BHD' => trans('app.BHDCurrency'),
        'AED' => trans('app.AEDCurrency'),
        'QAR' => trans('app.QRCurrency'),
        'OMR' => trans('app.OMRCurrency'),
        'JOD' => trans('app.JODCurrency'),
//        'USD' => trans('app.USDCurrency'),
//        'EGP' => trans('app.EGPCurrency'),
//        'IQD' => trans('app.IQDCurrency'),
//        'EUR' => trans('app.EURCurrency'),
//        'GBP' => trans('app.GBPCurrency')
    );
    return $type_array;
}

function Currency_select($data = null) {
    $data_array = array(
        'KWD' => trans('app.KWDCurrency'),
        'SAR' => trans('app.SARCurrency'),
        'BHD' => trans('app.BHDCurrency'),
        'AED' => trans('app.AEDCurrency'),
        'QAR' => trans('app.QARCurrency'),
        'OMR' => trans('app.OMRCurrency'),
        'JOD' => trans('app.JODCurrency'),
//        'USD' => trans('app.USDCurrency'),
//        'EGP' => trans('app.EGPCurrency'),
//        'IQD' => trans('app.IQDCurrency'),
//        'EUR' => trans('app.EURCurrency'),
//        'GBP' => trans('app.GBPCurrency')
    );
    $output = '<select name="type_currency" id="typeCurrency" class="select select2 typeCurrency form-control" style="width:100%">';
    foreach ($data_array as $key => $data_name) {
        $output .= '<option value="' . $key . '"';
        if ($data == $key) {
            $output .= ' selected';
        }
        $output .= '>' . $data_name . '</option>';
    }
    $output .= '</select>';
    return $output;
}

function PathuploadImage($image) {
    $name = generateRandomToken() . ".png";
    if ($image != '' && $name != '') {
        $path = 'uploads/' . $name;
        if (file_put_contents($path, base64_decode($image))) {
            $default_server = 'https://' . $_SERVER['SERVER_NAME'];
            $path = $default_server . getUrl('') . 'uploads/' . $name;
            Compress_Modify_Image($default_server, $name);
            return $path;
        } else {
            return FALSE;
        }
    }
    return FALSE;
}

function uploadvideo($file) {
    $valid_extensions = ['mov', 'mp4', 'm4a'];
    $response = [];
    $fields = [];
    if (empty($file)) {
        $fields['video'] = 'video';
    } else {
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize() / 1000000;
        if (!in_array($extension, $valid_extensions)) {
            $fields['video_extension'] = 'Error video Extension ';
        }
        if ($size > 50) {
            $fields['video_size'] = ' video Size Must be less than 50 Mb ';
        }
        if (!empty($fields)) {
            $path = '';
            return $path;
        }
    }
    if (!empty($fields)) {
        $path = '';
        return $path;
    }
    if (!empty($file) && in_array($extension, $valid_extensions) && $size < 50) {
        $fileName = md5(rand(10000, 50000) . time()) . '.' . $extension;
        $destinationPath = 'uploads/';  //public_path('/uploads');
        if ($file->move($destinationPath, $fileName)) {
//            $path = $this->basePath . $fileName;
            $default_server = 'https://' . $_SERVER['SERVER_NAME'];
            $path = $default_server . getUrl('') . 'uploads/' . $fileName;
            return $path;
        } else {
            $path = '';
            return $path;
        }
    }
}
function uploadfile($file) {
    $valid_extensions = ['pdf', 'doc', 'docx', 'txt'];
    $response = [];
    $fields = [];
    if (empty($file)) {
        $fields['file'] = 'file';
    } else {
        $extension = $file->getClientOriginalExtension();
        $size = $file->getSize() / 1000000;
        if (!in_array($extension, $valid_extensions)) {
            $fields['file_extension'] = 'Error File Extension ';
        }
        if ($size > 50) {
            $fields['file_size'] = ' File Size Must be less than 50 Mb ';
        }
        if (!empty($fields)) {
            $path = '';
            return $path;
        }
    }
    if (!empty($fields)) {
        $path = '';
        return $path;
    }
    if (!empty($file) && in_array($extension, $valid_extensions) && $size < 50) {
        $fileName = md5(rand(10000, 50000) . time()) . '.' . $extension;
        $destinationPath = 'uploads/';  //public_path('/uploads');
        if ($file->move($destinationPath, $fileName)) {
//            $path = $this->basePath . $fileName;
            $default_server = 'https://' . $_SERVER['SERVER_NAME'];
            $path = $default_server . getUrl('') . 'uploads/' . $fileName;
            return $path;
        } else {
            $path = '';
            return $path;
        }
    }
}

function get_timeVimeVideo($time) {
    $array_num = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9);
    $all_time = round($time);
    $sec = $all_time % 60;
    if (in_array($sec, $array_num)) {
        $sec = '0' . $sec;
    }
    $remind = (int) ($all_time / 60);
    $min = $remind % 60;
    if (in_array($min, $array_num)) {
        $min = '0' . $min;
    }
    $hour = (int) ($remind / 60);
    if (in_array($hour, $array_num)) {
        $hour = '0' . $hour;
    }
    if ($hour == '00') {
        $period = $min . ':' . $sec;
    } else {
        $period = $hour . ':' . $min . ':' . $sec;
    }
    return $period;
}

function getvimeoMP4Url($link_uri) {
    $video = $lib->request($link_uri);
    if ($video) {
        $body = $video['body'];
        $tags = array();
        foreach ($body['tags'] as $tag) {
            array_push($tags, $tag['tag']);
        }
        $max_thumbnail_size = 0;
        $thumbnail = '';
        foreach ($body['pictures']['sizes'] as $size) {
            if ($size['width'] > $max_thumbnail_size) {
                $max_thumbnail_size = $size['width'];
                $thumbnail = $size['link'];
            }
        }
        $urls = array();
        $qualities = array("sd", "hd", "mobile");
        foreach ($body['files'] as $file) {
            if (in_array($file['quality'], $qualities)) {
                $rate = intval($file['size'] / $body['duration'] * 8);
                $urls[strval($rate)] = $file['link'];
            }
        }
        $aarr = array('name' => $body['name'], 'duration' => $body['duration'], 'width' => $body['width'], 'height' => $body['height'], 'description' => $body['description'], 'tags' => join(',', $tags), 'thumbnail' => $thumbnail, 'urls' => $urls);
        return $aarr;
    } else {
        return null;
    }
}
function url_get_contents($Url) {
    if (!function_exists('curl_init')) {
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function tableView() {
    $limit_array = array(
        10 => '10',
        25 => '25',
        50 => '50',
        100 => '100',
        250 => '250'
    );
    return $limit_array;
}
function countryType() {
    $type_array = array(
        'africa' => trans('app.africa'),
        'asia' => trans('app.asia'),
        'europe' => trans('app.europe'),
        'north_amarica' => trans('app.north_america'),
        'south_america' => trans('app.south_america'),
        'australia' => trans('app.australia')
    );
    return $type_array;
}

function country_array($country = null) {
    return $country_array = array(
        '' => trans('app.choose_country'),
        'SA' => trans('app.saudi_arabia'),
        'EG' => trans('app.egypt'),
        'KW' => trans('app.kuwait'),
        'JO' => trans('app.jordan'),
        'AE' => trans('app.arab_emirates'),
        'YE' => trans('app.yemen'),
        'IQ' => trans('app.iraq'),
        'OM' => trans('app.oman'),
        'PS' => trans('app.palestine'),
        'QA' => trans('app.qatar'),
        'LB' => trans('app.lebanon'),
        'LY' => trans('app.libya'),
        'SY' => trans('app.syria'),
        'MA' => trans('app.morocco'),
        'AW' => trans('app.europe'),
        'HU' => trans('app.mager'),
        'IO' => trans('app.british_indian_ocean'),
        'TF' => trans('app.french_southern'),
        'MX' => trans('app.mexico'),
        'GB' => trans('app.united_kingdom'),
        'NO' => trans('app.norway'),
        'AT' => trans('app.austria'),
        'NE' => trans('app.niger'),
        'IN' => trans('app.india'),
        'US' => trans('app.usa'),
        'los' => trans('app.los_angeles'),
        'JP' => trans('app.japan'),
        'ET' => trans('app.ethiopia'),
        'PK' => trans('app.pakistan'),
        'ID' => trans('app.indonesia'),
        'RU' => trans('app.russia'),
        'FR' => trans('app.france'),
        'MR' => trans('app.mauritania'),
        'GI' => trans('app.gibraltar'),
        'IR' => trans('app.iran'),
        'IT' => trans('app.italia'),
        'SD' => trans('app.sudan'),
        'SE' => trans('app.sweden'),
        'EH' => trans('app.western_desert'),
        'SO' => trans('app.somalia'),
        'CN' => trans('app.china'),
        'DZ' => trans('app.algeria'),
        'BH' => trans('app.bahrain'),
        'AZ' => trans('app.azerbaijan'),
        'AM' => trans('app.armenia'),
        'ES' => trans('app.spain'),
        'AU' => trans('app.australia'),
        'AF' => trans('app.afghanistan'),
        'AL' => trans('app.albania'),
        'DE' => trans('app.germany'),
        'AG' => trans('app.antigua_barbuda'),
        'AO' => trans('app.angola'),
        'BS' => trans('app.bahamas'),
        'BR' => trans('app.brazil'),
        'PT' => trans('app.portugal'),
            /* 'AI' => 'أنجويلا',
              'AD' => 'أندورا',
              'UY' => 'أورجواي',
              'UZ' => 'أوزبكستان',
              'UG' => 'أوغندا',
              'UA' => 'أوكرانيا',
              'IE' => 'أيرلندا',
              'IS' => 'أيسلندا',
              'ER' => 'اريتريا',
              'EE' => 'استونيا',
              'AR' => 'الأرجنتين',
              'EC' => 'الاكوادور',
              'DK' => 'الدانمرك',
              'CV' => 'الرأس الأخضر',
              'SV' => 'السلفادور',
              'SN' => 'السنغال',
              'VA' => 'الفاتيكان',
              'PH' => 'الفيلبين',
              'AQ' => 'القطب الجنوبي',
              'CM' => 'الكاميرون',
              'CG' => 'الكونغو - برازافيل',
              'GR' => 'اليونان',
              'PG' => 'بابوا غينيا الجديدة',
              'PY' => 'باراجواي',
              'PW' => 'بالاو',
              'BW' => 'بتسوانا',
              'PN' => 'بتكايرن',
              'BB' => 'بربادوس',
              'BM' => 'برمودا',
              'BN' => 'بروناي',
              'BE' => 'بلجيكا',
              'BG' => 'بلغاريا',
              'BZ' => 'بليز',
              'BD' => 'بنجلاديش',
              'PA' => 'بنما',
              'BJ' => 'بنين',
              'BT' => 'بوتان',
              'PR' => 'بورتوريكو',
              'BF' => 'بوركينا فاسو',
              'BI' => 'بوروندي',
              'PL' => 'بولندا',
              'BO' => 'بوليفيا',
              'PF' => 'بولينيزيا الفرنسية',
              'PE' => 'بيرو',
              'TZ' => 'تانزانيا',
              'TH' => 'تايلند',
              'TW' => 'تايوان',
              'TM' => 'تركمانستان',
              'TR' => 'تركيا',
              'TT' => 'ترينيداد وتوباغو',
              'TD' => 'تشاد',
              'TG' => 'توجو',
              'TV' => 'توفالو',
              'TK' => 'توكيلو',
              'TO' => 'تونجا',
              'TN' => 'تونس',
              'TL' => 'تيمور الشرقية',
              'JM' => 'جامايكا',
              'GD' => 'جرينادا',
              'GL' => 'جرينلاند',
              'AX' => 'جزر أولان',
              'AN' => 'جزر الأنتيل الهولندية',
              'TC' => 'جزر الترك وجايكوس',
              'KM' => 'جزر القمر',
              'KY' => 'جزر الكايمن',
              'MH' => 'جزر المارشال',
              'MV' => 'جزر الملديف',
              'UM' => 'جزر الولايات المتحدة البعيدة الصغيرة',
              'SB' => 'جزر سليمان',
              'FO' => 'جزر فارو',
              'VI' => 'جزر فرجين الأمريكية',
              'VG' => 'جزر فرجين البريطانية',
              'FK' => 'جزر فوكلاند',
              'CK' => 'جزر كوك',
              'CC' => 'جزر كوكوس',
              'MP' => 'جزر ماريانا الشمالية',
              'WF' => 'جزر والس وفوتونا',
              'CX' => 'جزيرة الكريسماس',
              'BV' => 'جزيرة بوفيه',
              'IM' => 'جزيرة مان',
              'NF' => 'جزيرة نورفوك',
              'HM' => 'جزيرة هيرد وماكدونالد',
              'CF' => 'جمهورية افريقيا الوسطى',
              'CZ' => 'جمهورية التشيك',
              'DO' => 'جمهورية الدومينيك',
              'CD' => 'جمهورية الكونغو الديمقراطية',
              'ZA' => 'جمهورية جنوب افريقيا',
              'GT' => 'جواتيمالا',
              'GP' => 'جوادلوب',
              'GU' => 'جوام',
              'GE' => 'جورجيا',
              'GS' => 'جورجيا الجنوبية وجزر ساندويتش الجنوبية',
              'DJ' => 'جيبوتي',
              'JE' => 'جيرسي',
              'DM' => 'دومينيكا',
              'RW' => 'رواندا',
              'BY' => 'روسيا البيضاء',
              'RO' => 'رومانيا',
              'RE' => 'روينيون',
              'ZM' => 'زامبيا',
              'ZW' => 'زيمبابوي',
              'CI' => 'ساحل العاج',
              'WS' => 'ساموا',
              'AS' => 'ساموا الأمريكية',
              'SM' => 'سان مارينو',
              'PM' => 'سانت بيير وميكولون',
              'VC' => 'سانت فنسنت وغرنادين',
              'KN' => 'سانت كيتس ونيفيس',
              'LC' => 'سانت لوسيا',
              'MF' => 'سانت مارتين',
              'SH' => 'سانت هيلنا',
              'ST' => 'ساو تومي وبرينسيبي',
              'LK' => 'سريلانكا',
              'SJ' => 'سفالبارد وجان مايان',
              'SK' => 'سلوفاكيا',
              'SI' => 'سلوفينيا',
              'SG' => 'سنغافورة',
              'SZ' => 'سوازيلاند',
              'SR' => 'سورينام',
              'CH' => 'سويسرا',
              'SL' => 'سيراليون',
              'SC' => 'سيشل',
              'CL' => 'شيلي',
              'RS' => 'صربيا',
              'CS' => 'صربيا والجبل الأسود',
              'TJ' => 'طاجكستان',
              'GM' => 'غامبيا',
              'GH' => 'غانا',
              'GF' => 'غويانا',
              'GY' => 'غيانا',
              'GN' => 'غينيا',
              'GQ' => 'غينيا الاستوائية',
              'GW' => 'غينيا بيساو',
              'VU' => 'فانواتو',
              'VE' => 'فنزويلا',
              'FI' => 'فنلندا',
              'VN' => 'فيتنام',
              'FJ' => 'فيجي',
              'CY' => 'قبرص',
              'KG' => 'قرغيزستان',
              'KZ' => 'كازاخستان',
              'NC' => 'كاليدونيا الجديدة',
              'HR' => 'كرواتيا',
              'KH' => 'كمبوديا',
              'CA' => 'كندا',
              'CU' => 'كوبا',
              'KR' => 'كوريا الجنوبية',
              'KP' => 'كوريا الشمالية',
              'CR' => 'كوستاريكا',
              'CO' => 'كولومبيا',
              'KI' => 'كيريباتي',
              'KE' => 'كينيا',
              'LV' => 'لاتفيا',
              'LA' => 'لاوس',
              'LU' => 'لوكسمبورج',
              'LR' => 'ليبيريا',
              'LT' => 'ليتوانيا',
              'LI' => 'ليختنشتاين',
              'LS' => 'ليسوتو',
              'MQ' => 'مارتينيك',
              'MO' => 'ماكاو الصينية',
              'MT' => 'مالطا',
              'ML' => 'مالي',
              'MY' => 'ماليزيا',
              'YT' => 'مايوت',
              'MG' => 'مدغشقر',
              'MK' => 'مقدونيا',
              'MW' => 'ملاوي',
              'ZZ' => 'منطقة غير معرفة',
              'MN' => 'منغوليا',
              'MU' => 'موريشيوس',
              'MZ' => 'موزمبيق',
              'MD' => 'مولدافيا',
              'MC' => 'موناكو',
              'MS' => 'مونتسرات',
              'MM' => 'ميانمار',
              'FM' => 'ميكرونيزيا',
              'NA' => 'ناميبيا',
              'NR' => 'نورو',
              'NP' => 'نيبال',
              'NG' => 'نيجيريا',
              'NI' => 'نيكاراجوا',
              'NZ' => 'نيوزيلاندا',
              'NU' => 'نيوي',
              'HT' => 'هايتي',
              'HN' => 'هندوراس',
              'NL' => 'هولندا',
              'HK' => 'هونج كونج الصينية',
              'BA' => 'البوسنة والهرسك',
              'GA' => 'الجابون',
              'ME' => 'الجبل الأسود', */
    );
}

function countryName($field = null) {
    $field_array = country_array();
    if (isset($field_array[$field])) {
        return $field_array[$field];
    } else {
        return "";
    }
}

function country_select($country = null) {
    $country_array = country_array();
    $output = '<select name="address" id="country" class=" input-box form-control select select2 user_country" style="height:50px;width:100%">';
    foreach ($country_array as $key => $country_name) {
        $output .= '<option value="' . $key . '"';
        if ($country == $key) {
            $output .= ' selected';
        }
        $output .= '>' . $country_name . '</option>';
    }
    $output .= '</select>';
    return $output;
}

function city_array($api = 0, $country = null) {
    $country_start = [];
    if ($api == 0) {
        $country_start = array('' => trans('app.choose_city'));
    }
    $country_array = array(
        'TihamaQahtan' => 'تهامة قحطان',
        'Herica' => 'الحريضة',
        'HoutaSudair' => 'حوطة سدير',
        'Tanuma' => 'تنومة',
        'Dyer' => 'الداير',
        'Riyadh' => 'الرياض',
        'Alkharag ' => 'الخرج',
        'Mecca' => 'مكة',
        'Jeddah' => 'جدة',
        'Taif' => 'الطائف',
        'Medina' => 'المدينة المنورة',
        'AdDammam' => 'الدمام',
        'Dhahran' => 'الظهران',
        'Khobar' => 'الخبر',
        'Hofuf' => 'الهفوف',
        'Rama' => 'الرس',
        'Onaiza' => 'عنيزة',
        'Bakariya' => 'البكيرية',
        'Tabuk' => 'تبوك',
        'Arar' => 'عرعر',
        'Abha' => 'أبها',
        'KhamisMushait' => 'خميس مشيط',
        'AlBahah' => 'الباحة',
        'Belgrishi' => 'بلجرشي',
        'Najran' => 'نجران',
        'Jazan' => 'جازان',
        'Hail' => 'حائل',
        'AlJawf' => 'الجوف',
        'Buraydah' => 'بريدة',
        'Sbia' => 'صبيا',
        'Qatif' => 'القطيف',
        'Muzahmiyah' => 'المزاحمية',
        'Diriyah' => 'الدرعية',
        'Ehsaa' => 'الإحساء',
        'ELmoznb' => 'المذنب',
        'Badaa' => 'البدائع',
        'Shamsia' => 'الشماسية',
        'Qunfudah' => 'القنفذة',
        'Leith' => 'الليث',
        'Rabigh' => 'رابغ',
        'ElJom' => 'الجموم',
        'Khulais' => 'خليص',
        'AlKaml' => 'الكامل',
        'Khorama' => 'الخرمة',
        'Rania' => 'رنية',
        'Torba' => 'تربة',
        'Yanbu' => 'ينبع',
        'Ola' => 'العلا',
        'AlMahd' => 'المهد',
        'Badr' => 'بدر',
        'Khyber' => 'خيبر',
        'Dawadmi' => 'الدوادمي',
        'Majmaa' => 'المجمعة',
        'Quwayia' => 'القويعية',
        'WadiAl-Dawasir' => 'وادي الدواسر',
        'Aflaj' => 'الأفلاج',
        'Zulfi' => 'الزلفي',
        'Blonde' => 'شقراء',
        'HoutaBaniTamim' => 'حوطة بني تميم',
        'Afif' => 'عفيف',
        'Alsalil' => 'السليل',
        'Darmah' => 'ضرماء',
        'Ramah' => 'رماح',
        'Hanakiyah' => 'الحناكيه',
        'Thadec' => 'ثادق',
        'Harela' => 'حريملاء',
        'ElRarik' => 'الحريق',
        'ElGhat' => 'الغاط',
        'HafrAl-Batin' => 'حفر الباطن',
        'AlJubail' => 'الجبيل',
        'Khafji' => 'الخفجي',
        'RasTanura' => 'رأس تنورة',
        'Abqaiq' => 'بقيق',
        'ElNairiya' => 'النعيرية',
        'ElkaryElolia' => 'قريه العليا',
        'Skaka' => 'سكاكا',
        'Qurays' => 'القريات',
        'DomatAl-Jandal' => 'دومة الجندل',
        'Bekaa' => 'بقعاء',
        'Ghazala' => 'الغزالة',
        'AbuArish' => 'أبو عريش',
        'Samtah' => 'صامطه',
        'ElHarth' => 'الحرث',
        'Dammad' => 'ضمد',
        'Al-Rayth' => 'الريث',
        'Pich' => 'بيش',
        'Fursan' => 'فرسان',
        'BaniMalik-Algeria' => 'بني مالك -الدائر',
        'ALMosarh' => 'أحد المسارحه',
        'Eidebi' => 'العيدابي',
        'AlArida' => 'العارضه',
        'AlDarb' => 'الدرب',
        'Sharoura' => 'شرورة',
        'Hobona' => 'حبونا',
        'BadrSouth' => 'بدر الجنوب',
        'Yadma' => 'يدمه',
        'Thar' => 'ثار',
        'Khabash' => 'خباش',
        'Kharjir' => 'الخرخير',
        'Almond' => 'المندق',
        'AlMahwah' => 'المخواة',
        'AlAgate' => 'العقيق',
        'Kalwa' => 'قلوه',
        'AlCora' => 'القرى',
        'Bisha' => 'بيشة',
        'AlNamas' => 'النماص',
        'Mhael' => 'محائل',
        'SarraObaida' => 'سراة عبيدة',
        'Tthlith' => 'تثليث',
        'RegalElma' => 'رجال المع',
        'Rufaida' => 'احد رفيدة',
        'SouthDhahran ' => 'ظهران الجنوب',
        'Balqarn' => 'بلقرن',
        'Almgarda' => 'المجاردة',
        'Rafha' => 'رفحاء',
        'Tarif' => 'طريف',
        'Alwagh' => 'الوجه',
        'Duba' => 'ضباء',
        'Taima' => 'تيماء',
        'Amalge' => 'أملج',
        'hakl' => 'حقل',
        'Asiyah' => 'الأسياح',
        'AlNaphaeya' => 'النبهائيه',
        'EyonElgwa' => 'عيون الجواء',
        'RiyadhExpert' => 'رياض الخبراء',
        'AlRoboa' => 'الربوعة',
        'Alhakw' => 'الحقو',
        'Adam' => 'أضم',
        'AlQuoz' => 'القوز',
        'Hall' => 'حلي',
        'Maysan' => 'ميسان',
        'AlAyes' => 'العيص',
        'Artawi' => 'الأرطاوية',
        'Dulm' => 'الدلم',
        'AlHaet' => 'الحائط',
        'Tabarjal' => 'طبرجل',
        'Darba' => 'ضرية',
        'AlForsha' => 'الفرشة',
        'Qaysumah' => 'القيصومة',
        'SabtAlOla' => 'سبت العلايا',
        'AlFawara' => 'الفوارة',
        'Shannan' => 'الشنان',
        'Ammar' => 'حالة عمار',
        'Asir' => 'عسير',
        'Otay' => 'عطى',
        'Nafy' => 'نفي',
        'AlOrdiat' => 'العرضيات',
        'WadyElFare' => 'وادي الفرع',
        'Mok' => 'موق',
        'WadyEbnHASHBEL' => 'وادي بن هشبل',
        'Mastora' => 'مستورة',
        'Shamli' => 'الشملي'
    );
    return $country_array = array_merge($country_start, $country_array);
}

function cityName($field = null) {
    $field_array = city_array();
    if (isset($field_array[$field])) {
        return $field_array[$field];
    } else {
        return "";
    }
}

function cityName_API($api = 0, $lang = 'en', $country = null) {
    $field_array = city_array($api);
    $all_data = [];
    foreach ($field_array as $key => $value) {
        $array['key'] = $key;
        if ($lang == 'en') {
            $array['name'] = $key;
        } else {
            $array['name'] = $value;
        }
        $all_data[] = $array;
    }
    return $all_data;
}

function city_select($country = null) {
    $country_array = city_array();
    $output = '<select name="city" id="country" class=" form-control select select2 user_city" style="width:100%">';
    foreach ($country_array as $key => $country_name) {
        $output .= '<option value="' . $key . '"';
        if ($country == $key) {
            $output .= ' selected';
        }
        $output .= '>' . $country_name . '</option>';
    }
    $output .= '</select>';
    return $output;
}

function getPathViemo($value_vimeo = '') {
    return true;
}

function type_rowChart() {
    //type_row
    $status_array = array(
        null => 'اختروضعيه السكشن',
        'front' => 'امامى',
        'back' => 'خلفى',
        'right' => 'يمين',
        'left' => 'شمال',
    );
    return $status_array;
}

function Num_rowChart() {
    //row
    $status_array = array(
        null => 'اختر رقم المقعد',
        1 => '(1,1)',
        2 => '(2,1)',
        3 => '(3,1)',
        4 => '(4,1)',
        5 => '(5,1)',
        6 => '(6,1)',
        7 => '(7,1)',
        8 => '(8,1)',
        9 => '(9,1)',
        10 => '(10,1)', //
        11 => '(1,2)',
        12 => '(2,2)',
        13 => '(3,2)',
        14 => '(4,2)',
        15 => '(5,2)',
        16 => '(6,2)',
        17 => '(7,2)',
        18 => '(8,2)',
        19 => '(9,2)',
        20 => '(10,2)', //
        21 => '(1,3)',
        22 => '(2,3)',
        23 => '(3,3)',
        24 => '(4,3)',
        25 => '(5,3)',
        26 => '(6,3)',
        27 => '(7,3)',
        28 => '(8,3)',
        29 => '(9,3)',
        30 => '(10,3)', //
        31 => '(1,4)',
        32 => '(2,4)',
        33 => '(3,4)',
        34 => '(4,4)',
        35 => '(5,4)',
        36 => '(6,4)',
        37 => '(7,4)',
        38 => '(8,4)',
        39 => '(9,4)',
        40 => '(10,4)', //
        41 => '(1,5)',
        42 => '(2,5)',
        43 => '(3,5)',
        44 => '(4,5)',
        45 => '(5,5)',
        46 => '(6,5)',
        47 => '(7,5)',
        48 => '(8,5)',
        49 => '(9,5)',
        50 => '(10,5)', //
        51 => '(1,6)',
        52 => '(2,6)',
        53 => '(3,6)',
        54 => '(4,6)',
        55 => '(5,6)',
        56 => '(6,6)',
        57 => '(7,6)',
        58 => '(8,6)',
        59 => '(9,6)',
        60 => '(10,6)', //
        61 => '(1,7)',
        62 => '(2,7)',
        63 => '(3,7)',
        64 => '(4,7)',
        65 => '(5,7)',
        66 => '(6,7)',
        67 => '(7,7)',
        68 => '(8,7)',
        69 => '(9,7)',
        70 => '(10,7)', //
        71 => '(1,8)',
        72 => '(2,8)',
        73 => '(3,8)',
        74 => '(4,8)',
        75 => '(5,8)',
        76 => '(6,8)',
        77 => '(7,8)',
        78 => '(8,8)',
        79 => '(9,8)',
        80 => '(10,8)', //
        81 => '(1,9)',
        82 => '(2,9)',
        83 => '(3,9)',
        84 => '(4,9)',
        85 => '(5,9)',
        86 => '(6,9)',
        87 => '(7,9)',
        88 => '(8,9)',
        89 => '(9,9)',
        90 => '(10,9)', //
        91 => '(1,10)',
        92 => '(2,10)',
        93 => '(3,10)',
        94 => '(4,10)',
        95 => '(5,10)',
        96 => '(6,10)',
        97 => '(7,10)',
        98 => '(8,10)',
        99 => '(9,10)',
        100 => '(10,10)', //
    );
    return $status_array;
}

function getNum_rowChart($status = null) {
    $output = '';
    $status_array = array(
        1 => '(1,1)',
        2 => '(2,1)',
        3 => '(3,1)',
        4 => '(4,1)',
        5 => '(5,1)',
        6 => '(6,1)',
        7 => '(7,1)',
        8 => '(8,1)',
        9 => '(9,1)',
        10 => '(10,1)', //
        11 => '(1,2)',
        12 => '(2,2)',
        13 => '(3,2)',
        14 => '(4,2)',
        15 => '(5,2)',
        16 => '(6,2)',
        17 => '(7,2)',
        18 => '(8,2)',
        19 => '(9,2)',
        20 => '(10,2)', //
        21 => '(1,3)',
        22 => '(2,3)',
        23 => '(3,3)',
        24 => '(4,3)',
        25 => '(5,3)',
        26 => '(6,3)',
        27 => '(7,3)',
        28 => '(8,3)',
        29 => '(9,3)',
        30 => '(10,3)', //
        31 => '(1,4)',
        32 => '(2,4)',
        33 => '(3,4)',
        34 => '(4,4)',
        35 => '(5,4)',
        36 => '(6,4)',
        37 => '(7,4)',
        38 => '(8,4)',
        39 => '(9,4)',
        40 => '(10,4)', //
        41 => '(1,5)',
        42 => '(2,5)',
        43 => '(3,5)',
        44 => '(4,5)',
        45 => '(5,5)',
        46 => '(6,5)',
        47 => '(7,5)',
        48 => '(8,5)',
        49 => '(9,5)',
        50 => '(10,5)', //
        51 => '(1,6)',
        52 => '(2,6)',
        53 => '(3,6)',
        54 => '(4,6)',
        55 => '(5,6)',
        56 => '(6,6)',
        57 => '(7,6)',
        58 => '(8,6)',
        59 => '(9,6)',
        60 => '(10,6)', //
        61 => '(1,7)',
        62 => '(2,7)',
        63 => '(3,7)',
        64 => '(4,7)',
        65 => '(5,7)',
        66 => '(6,7)',
        67 => '(7,7)',
        68 => '(8,7)',
        69 => '(9,7)',
        70 => '(10,7)', //
        71 => '(1,8)',
        72 => '(2,8)',
        73 => '(3,8)',
        74 => '(4,8)',
        75 => '(5,8)',
        76 => '(6,8)',
        77 => '(7,8)',
        78 => '(8,8)',
        79 => '(9,8)',
        80 => '(10,8)', //
        81 => '(1,9)',
        82 => '(2,9)',
        83 => '(3,9)',
        84 => '(4,9)',
        85 => '(5,9)',
        86 => '(6,9)',
        87 => '(7,9)',
        88 => '(8,9)',
        89 => '(9,9)',
        90 => '(10,9)', //
        91 => '(1,10)',
        92 => '(2,10)',
        93 => '(3,10)',
        94 => '(4,10)',
        95 => '(5,10)',
        96 => '(6,10)',
        97 => '(7,10)',
        98 => '(8,10)',
        99 => '(9,10)',
        100 => '(10,10)', //
    );
    foreach ($status_array as $key => $status_name) {
        if ($status == $key) {
            $output = $status_name;
        }
    }
    return $output;
}