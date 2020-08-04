<?php

function create_head($title) {
    global $REIF;
    $name = $REIF["config"]["name"];
    $theme = $REIF["config"]["theme"];
    return "<head><title>$name - $title</title><link rel='stylesheet' type='text/css' href='../themes/$theme/pages.css'></head>";
}

function create_header() {
    global $REIF;
    $name = $REIF["config"]["name"];
    $logo = $REIF["config"]["logo"];
    return "<header><img src='$logo' alt='logo'><h1>$name</h1></header>";
}

function create_navigation() {
    global $REIF;
    $nav = "<nav>";
    $nav_groups = array();
    $nav_pages = array();
    foreach(scandir($REIF["root"] . "/content") as $number => $found) {
        if(ends_with($found, ".json")) {
            $page = json_decode(file_get_contents($REIF["root"] . "/content/$found"), true);
            $pageinfo = array(
                "displayName" => $page["displayName"],
                "fileName" => substr($found, 0, -5) . ".php"
            );
            if($page["navGroup"] !== false) {
                if(!is_array($nav_groups[$page["navGroup"]])) {
                    $nav_groups[$page["navGroup"]] = array();
                }
                array_push($nav_groups[$page["navGroup"]], $pageinfo);
            } else {
                array_push($nav_pages, $pageinfo);
            }
        }
    }
    foreach($nav_pages as $number => $page) {
        $name = $page["displayName"];
        $link = $page["fileName"];
        if($page["fileName"] == "index.php") {
            $link .= "' class='start";
        }
        $nav .= "<a href='$link'><button>$name</button></a>";
    }
    foreach($nav_groups as $name => $groupinfo) {
        $group = "<div class='group'><button>$name</button><div class='list'>";
        foreach($groupinfo as $number => $page) {
            $name = $page["displayName"];
            $link = $page["fileName"];
            $group .= "<a href='$link'><button>$name</button></a>";
        }
        $group .= "</div></div>";
        $nav .= $group;
    }
    $nav .= "</nav>";
    return $nav;
}

function display_page($name) {
    global $REIF;
    $info = json_decode(file_get_contents($REIF["root"] . "/content/$name.json"), true);
    $page = "<html>";
    $page .= create_head($info["displayName"]);
    $page .= "<body>";
    $page .= create_header();
    $page .= create_navigation();
    $page .= file_get_contents($REIF["root"] . "/content/$name.txt");
    $page .= "</body>";
    $page .= "</html>";
    echo $page;
    return;
}

function current_page() {
    global $REIF;
    $basepath = str_replace($_SERVER["DOCUMENT_ROOT"], "", $REIF["root"]);
    $file = str_replace("$basepath/content/", "", $_SERVER["REQUEST_URI"]);
    if($file == "") {
        $file = "index.php";
    }
    $file = substr($file, 0, -4);
    display_page($file);
    return;
}

function page_list() {
    global $REIF;
    $pages = array();
    foreach(scandir($REIF["root"] . "/content") as $number => $found) {
        if(ends_with($found, ".json")) {
            array_push($pages, substr($found, 0, -5));
        }
    }
    return $pages;
}

function create_page($name, $displayName, $navGroup, $content, $user) {
    global $REIF;
    if(!file_exists($REIF["root"] . "/content/$name.txt")) {
        $pageinfo = array();
        $infofile = $REIF["root"] . "/content/$name.json";
        $contentfile = $REIF["root"] . "/content/$name.txt";
        $phpfile = $REIF["root"] . "/content/$name.php";
        if(get_user_type($user) < 2) {
            $pageinfo["owner"] = $user;
        }
        $pageinfo["displayName"] = $displayName;
        $pageinfo["navGroup"] = $navGroup;
        file_put_contents($infofile, json_encode($pageinfo, true));
        file_put_contents($contentfile, $content);
        file_put_contents($phpfile, file_get_contents($REIF["root"] . "/content/index.php"));
    } else {
        return false;
    }
}