<?php
// This is the API, 2 possibilities: show the app list or show a specific app by id.
// This would normally be pulled from a database but for demo purposes, I will be hardcoding the return values.

function get_user_by_uname_and_passsword($uname, $psword)
{
  $user_info = array();

  $sql = "SELECT * FROM `admin` WHERE `uname` = \'".$uname."\' and `psword` = \'".$psword."\'";
  $result = @mysqli_query ($dbcon, $sql);

  if ($result) {
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $user_info = array(
      "user_id" => $row['user_id'],
      "uname" => $row['uname'],
      "email" => $row['email'],
      "user_level" => $row['user_level'],
      "psword" => $row['psword'],
      "fname" => $row['fname']
    ); 
    }
  }
  return $user_info;
}

$possible_url = array("autenthicate", "get_app");

$value = "An error has occurred, url does not defined";

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  switch ($_GET["action"])
    {
      case "get_user_by_uname":
        $value = get_user_by_uname();
        break;
      case "autenthicate":
        if (isset($_GET["uname"],$_GET["psword"]))
          $value = get_user_by_uname_and_passsword($_GET["uname"],$_GET["psword"]);
        else
          $value = "Missing argument";
        break;
    }
}

//return JSON array
exit(json_encode($value));
?>