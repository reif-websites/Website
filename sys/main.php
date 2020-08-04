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

include(__DIR__ . '/helper.php');

global $REIF;
$REIF["root"] = dirname(find_file("REIF_ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]));
$REIF["config"] = json_decode(file_get_contents($REIF["root"] . "/config.json"), true);
include(__DIR__ . '/users.php');
include(__DIR__ . '/pages.php');