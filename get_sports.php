<?php

$sports = ["NFL","NBA","MLB","NHL","NCAA Football","NCAA Basketball","Soccer","EPL","La Liga","Serie A","Bundesliga","MLS","UFC","Boxing","Tennis","Golf","Formula 1","MotoGP","Cricket","Rugby"];

$q = $_GET["q"] ?? "";
$q = trim($q);
$hint = "";

if ($q !== "") {
    $len = strlen($q);
    foreach ($sports as $sport) {
        if (strncasecmp($q, $sport, $len) === 0) {
            if ($hint === "") {
                $hint = $sport;
            } else {
                $hint .= ", " . $sport;
            }
        }
    }
}

echo $hint === "" ? "No suggestions" : $hint;
