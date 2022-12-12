<?php

$strJsonFileContents = json_decode(file_get_contents("app/Libs/App.setting.json"), true);

if ($strJsonFileContents["development"] == "true" && !empty($_SESSION)) {
    print_r($_GET);
    print_r('<br/>');
    foreach ($_SESSION as $key => $value) {
        if (is_array($value)) {
            print_r($key . " => ");
            print_r('<br/>');
            foreach ($value as $key2 => $value2) {
                if (is_array($value2)) {
                    print_r($key2);
                    print_r(" => ");
                } else {
                    print_r("--");
                    print_r($key2);
                    print_r(" => ");
                    print_r($value2);
                    print_r('<br/>');
                }
            }
        } else {
            print_r($key);
            print_r(" => ");
            print_r($value);
            print_r('<br/>');
        }
    }
    print_r("<hr>");
}


if ($strJsonFileContents["development"] == "true" && !empty($data)) {
    print_r($_GET);
    print_r('<br/>');
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            print_r($key . " => ");
            print_r('<br/>');
            foreach ($value as $key2 => $value2) {
                if (is_array($value2)) {
                    print_r($key2);
                    print_r(" => ");
                } else {
                    print_r("--");
                    print_r($key2);
                    print_r(" => ");
                    print_r($value2);
                    print_r('<br/>');
                }
            }
        } else {
            print_r($key);
            print_r(" => ");
            print_r($value);
            print_r('<br/>');
        }
    }
    print_r("<hr>");
}

?>