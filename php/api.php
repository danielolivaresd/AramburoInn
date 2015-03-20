<?php
// This is the API, 2 possibilities: show the app list or show a specific app by id.
// This would normally be pulled from a database but for demo purposes, I will be hardcoding the return values.
/*rooms =[
  {
    id:1,
    name:"101a",
    type:"Individual",
    max_people:2,
    reservations: [1],
    price: 300
  },
  {
    ...
  }]
reservations=[
  {
    id:1,
    maker: "Rodrigo López",
    maker_age: 18,
    maker_email: "rodrigo@gmail.com",
    maker_phone: 12345,
    maker_credit_card: 123412341234123,
    maker_ccv: 159,
    people: ["Rodrigo López", "Javier Martinez"],
    from: new Date(2015,04,04), //4 de Mayo de 2015
    to: new Date(2015,04,06),
    room_id: 1
  },
  {
    
  }]*/

function get_room_list()
{
  
  require ('mysqli_connect.php');
  $q = "SELECT id,  precio, camas, tipo, numeroHabitacion, comentarios FROM habitaciones ";   
  $result = @mysqli_query ($dbcon, $q);
  $room_array  = array();
  $c = 0;
  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
     
      
      $room_array = array("id" => $row['id'],"precio" => $row['precio'], "tipo" => $row['tipo'], "numeroHabitacion" => $row['numeroHabitacion'], "comentarios" => $row['comentarios']);
    $room_list [$c] = array($room_array);
     $c = $c +1;
   
  }
  mysqli_free_result ($result);
  
  return $room_list;
}

function get_room_by_id($id) {
  require ('mysqli_connect.php');
  $room_info = array();
  $q = "SELECT id,  precio, camas, tipo, numeroHabitacion, comentarios FROM habitaciones WHERE id='$id' ";
  
  $result = @mysqli_query ($dbcon, $q);
 
  if($result) {
    
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $room_info = array("id" => $row['id'],"precio" => $row['precio'], "tipo" => $row['tipo'], "numeroHabitacion" => $row['numeroHabitacion'], "comentarios" => $row['comentarios']);
    
    } //end while
  } //end if

mysqli_free_result ($result);
  return $room_info;
}

function get_app_by_id($id)
{
  $app_info = array();

  // normally this info would be pulled from a database.
  // build JSON array.
  switch ($id){
    case 1:
      $app_info = array("app_name" => "Web Demo", "app_price" => "Free", "app_version" => "2.0"); 
      break;
    case 2:
      $app_info = array("app_name" => "Audio Countdown", "app_price" => "Free", "app_version" => "1.1");
      break;
    case 3:
      $app_info = array("app_name" => "The Tab Key", "app_price" => "Free", "app_version" => "1.2");
      break;
    case 4:
      $app_info = array("app_name" => "Music Sleep Timer", "app_price" => "Free", "app_version" => "1.9");
      break;
  }

  return $app_info;
}

function get_app_list()
{
  //normally this info would be pulled from a database.
  //build JSON array
  $app_list = array(array("id" => 1, "name" => "Web Demo"), array("id" => 2, "name" => "Audio Countdown"), array("id" => 3, "name" => "The Tab Key"), array("id" => 4, "name" => "Music Sleep Timer")); 

  return $app_list;
}

$possible_url = array("get_app_list", 
  "get_app", 
  "get_room", 
  "get_room_list", 
  "post_room", 
  "delete_room",
  "autenthicate",
  "reserva",
  "get_reservacion");

$value = "An error has occurred ";

function get_user_by_uname_and_passsword($uname, $psword)
{
  require ('mysqli_connect.php');
  $user_info = array();

  $sql = "SELECT * FROM admin WHERE uname = '$uname' and psword = '$psword' ";//= \'".$uname."\' and `psword` = \'".$psword."\'";
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
  mysqli_free_result ($result);
  return $user_info;
}

function get_reservacion_by_codigo($codigo)
{
  require('mysqli_connect.php');
  $infoReserva = array();
  //$infoReservaList = array();

  $sql = "select reservaciones.fechaEntrada,
  reservaciones.fechaSalida,
  reservaciones.codigoReserva,
  reservaciones.comentarios,
  habitaciones.* 
  from habitaciones
  inner join habitacionreserva on habitaciones.id = habitacionreserva.idHabitacion
  inner join reservaciones on reservaciones.id = habitacionreserva.idReservacion
  where reservaciones.codigoReserva = '$codigo'";

  $result = @mysqli_query ($dbcon, $sql);

  if($result)
  {
    $c = 0;
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
    {
      $infoReserva = array(
        "fechaEntrada" => $row['fechaEntrada'],
        "fechaSalida" => $row['fechaSalida'],
        "codigoReserva" => $row['codigoReserva'],
        "comentarios" => $row['comentarios'],
        "id" => $row['id'],
        "precio" => $row['precio'],
        "camas" => $row['camas'],
        "tipo" => $row['tipo'],
        "numeroHabitacion" => $row['numeroHabitacion'],
        "mantenimiento" => $row['mantenimiento'],
        "comentarios" => $row['comentarios']
        );
      $infoReservaList [$c] = array($infoReserva);
      $c = $c +1;
    }
  }

  mysqli_free_result ($result);
  
  return $infoReservaList;
}

function post_room($precio, $camas, $tipo, $numeroHabitacion, $comentarios)
{
  $info = array();
  require ('mysqli_connect.php');
  $a = "SELECT *  FROM habitaciones ";   
  $r = @mysqli_query ($dbcon, $a);
  
  $rows = mysqli_num_rows ($r);
  
  $rows = $rows +1;

  $q = "INSERT INTO habitaciones (id, precio, camas, tipo, numeroHabitacion, comentarios) VALUES ('$rows', '$precio', '$camas', '$tipo', '$numeroHabitacion', '$comentarios' )";
    $result = @mysqli_query ($dbcon, $q); // Make the query
    
    if ($result) { // If it ran OK.
        $info = array("status" =>'The room was successfully registered');
    } else { // If the query did not run OK
    // Error message:
      $info = array("error" =>'Something went wrong');
    } // End of if ($result)
    mysqli_close($dbcon); // Close 
    return $info;
}

function delete_room($id)
{
  $info = array();
  require ('mysqli_connect.php');
  $q = "DELETE FROM habitaciones WHERE id=$id LIMIT 1";    
    $result = @mysqli_query ($dbcon , $q);
    
    if (mysqli_affected_rows($dbcon ) == 1) { // If it ran OK.
    // Print a message:
      $info = array("status" =>'The record has been deleted');
    } else { // If the query did not run OK.
     $info = array("error" =>'The record could not be deleted');
    }
  mysqli_close($dbcon );
  return $info;
}

function do_reservacion($habitacionesIds, $fechaInicial, $fechaFinal, $comentarios)
{
  require('mysqli_connect.php');
  require('uuid.php');
  //separar los id de las habitaciones
    
  $habitaciones = explode("*", $habitacionesIds);
  // generar codigo de reserva
  $codigo = UUID::v4();
  $codigoReserva = array();
  $idReservacion = "-1";

  // insertar reservacion
  $q = "INSERT INTO reservaciones (fechaEntrada,fechaSalida,codigoReserva,comentarios) 
  VALUES ('$fechaInicial','$fechaFinal', '$codigo', '$comentarios')";
  $result = @mysqli_query($dbcon, $q);
  if($result)
  {
    $codigoReserva = array("codigo" => $codigo);
    //obtener id de la reservacion insertada
    $sqlLastId = "SELECT MAX(id) as 'ID' FROM reservaciones";
    $resultIdReserv = @mysqli_query($dbcon, $sqlLastId);
    if($resultIdReserv)
    {
      while ($row = mysqli_fetch_array($resultIdReserv, MYSQLI_ASSOC)) {
        $idReservacion = $row['ID'];
      }
    }
  }
  else
  {
    $codigoReserva = array("codigo" => 'Error al generar la reservacion.'); 
  }

  // insertar relacion reservacion-habitaciones
  foreach ($habitaciones as &$hab) {
    $sql = "INSERT INTO habitacionreserva (idReservacion, idHabitacion) 
    VALUES ('$idReservacion','$hab')";

    $result = @mysqli_query($dbcon, $sql);
  }
  mysqli_close($dbcon);

  // regresar codigo de reservacion
  return $codigoReserva;
}

if (isset($_GET["action"]) && in_array($_GET["action"], $possible_url))
{
  switch ($_GET["action"])
    {

      case "get_app_list":
        $value = get_app_list();
        break;
      case "get_app":
        if (isset($_GET["id"]))
          $value = get_app_by_id($_GET["id"]);
        else
          $value = "Missing argument";
        break;

        case "get_room":
        if (isset($_GET["id"]))
          $value = get_room_by_id($_GET["id"]);
        else
          $value = "Missing argument";
        break;

        case "get_room_list":
          $value = get_room_list();
        break;

        case "autenthicate":
        if (isset($_GET["uname"],$_GET["psword"]))
          $value = get_user_by_uname_and_passsword($_GET["uname"],$_GET["psword"]);
        else
          $value = "Missing argument";
        break;
        //$precio, $camas, $tipo, $numeroHabitacion, $comentarios
        case "post_room":
          if (isset($_GET["precio"],$_GET["camas"],$_GET["tipo"],$_GET["numeroHabitacion"], $_GET["comentarios"] ))
            $value = post_room($_GET["precio"],$_GET["camas"], $_GET["tipo"], $_GET["numeroHabitacion"], $_GET["comentarios"] );
          else
            $value = "Missing argument";
        break;

        case "delete_room":
           if (isset($_GET["id"]))
            $value = delete_room($_GET["id"] );
          else
            $value = "Missing argument";
        break;
        case "reserva":
          if(isset($_GET["habitacionesIds"], $_GET["fechaInicial"], $_GET["fechaFinal"], $_GET["comentarios"]))
            $value = do_reservacion($_GET["habitacionesIds"], $_GET["fechaInicial"], $_GET["fechaFinal"], $_GET["comentarios"]);
          else
            $value = "Missing argument";
        break;
        case "get_reservacion":
          if(isset($_GET["codigo"]))
            $value = get_reservacion_by_codigo($_GET["codigo"]);
          else
            $value = "Missing argument";
    }
}

//http://localhost/simpleIdb/api.php/api.php?action=post_room&precio=10&camas=4&tipo=lala&numeroHabitacion=3&comentarios=lalala

//http://localhost/hotel/api.php/api.php?action=get_room_list
//http://localhost/hotel/api.php/api.php?action=get_room&id=1003
//http://localhost/hotel/api.php/api.php?action=post_room&price=1003&type=double&mini_descr=bonita&n_room=3&thumb=lalala&status=free
//http://localhost/simpleIdb/api.php/api.php?action=delete_room&id=1007
 
//return JSON array
exit(json_encode($value));
?>