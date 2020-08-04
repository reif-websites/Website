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

include(__DIR__ . '/../../../sys/main.php');

global $REIF;
if(file_exists($REIF["root"] . "/users/" . hash("sha3-512", hash("sha3-512", $_POST["username"])) . ".json")) {
    $type = get_user_type($_POST["username"]);
    echo "{\"type\": $type}";
} else {
    http_response_code(404); // not the perfect statuscode, but I don't know a better one.
    echo "{\"valid\": false}";
}