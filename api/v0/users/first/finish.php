<?php

/*
Copyright (C) 2020 Aaron Dewes

This file is part of Reif.

Reif is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Reif is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Reif.  If not, see <https://www.gnu.org/licenses/>.
*/

include(__DIR__ . '/../../../../sys/main.php');

global $REIF;

function invalid($code) {
    http_response_code($code);
    echo "{\"valid\": false}";
}

if(file_exists($REIF["root"] . "/REIF_LOGIN_CODE")) {
    $userCode = file_get_contents($REIF["root"] . "/REIF_LOGIN_CODE");
    $userCode = str_replace("\n", "", $userCode);
    $userCode = str_replace(" ", "", $userCode);
    $username = str_split($userCode, 128)[0];
    $password = str_split($userCode, 129)[1];
    $userfile = $REIF["root"] . "/users/$username.json";
    if(file_exists($userfile) && $username != "") {
        $userdata = json_decode(file_get_contents($userfile), true);
        if($userdata["password"] != $password) {
            invalid(403);
        } else {
            $userdata["disabled"] = false;
            touch($REIF["root"] . "/SETUP_FINISHED");
            $users = scandir($REIF["root"] . "/users/");
            foreach($users as $number => $name) {
                unlink($REIF["root"] . "/users/$name");
            }
            file_put_contents($userfile, json_encode($userdata, true));
            unlink($REIF["root"] . "/REIF_LOGIN_CODE");
            echo "{\"valid\": true}";
        }
    } else {
        invalid(403);
    }
} else {
    invalid(403);
}