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
if(file_exists($REIF["root"] . "/REIF_LOGIN_CODE")) {
    $username = file_get_contents($REIF["root"] . "/REIF_LOGIN_CODE");
    $userfile = $REIF["root"] . "/users/$username.json";
    if(file_exists($userfile) && $username != "") {
        $userinfo = json_decode(file_get_contents($userfile), true);
        $userinfo["disabled"] = false;
        file_put_contents($userfile, json_encode($userinfo, true));
    } else {
        http_response_code(403);
        echo "{\"valid\": false}";
    }
} else {
    http_response_code(403);
    echo "{\"valid\": false}";
}