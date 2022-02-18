<?php

function db_connect(){
 $result = new mysqli('localhost', 'maiarose_user_singularity', 'ItKjGHHwQIw?', 'maiarose_singularity');
// $result = new mysqli('localhost', 'root', 'maia2015', 'singularity_limalocal');
   if (!$result)
      return false;
   return $result;
}

function db_result_to_array($result){
   $res_array = array();
   for ($count=0; $row = $result->fetch_assoc(); $count++)
     $res_array[$count] = $row;
   return $res_array;
}

function get_pass_admin($username){
   $conn = db_connect();
   $query = "SELECT password_usuario FROM usuarios WHERE user_usuario = '$username'"; 
   $result = @$conn->query($query);
   if (!$result)
     return false;
   $num_cats = @$result->num_rows;
   if ($num_cats ==0)
      return false;  
   $row = $result->fetch_object();
   return $row->password_usuario;
}


function tep_validate_password($plain, $encrypted) {
    if (tep_not_null($plain) && tep_not_null($encrypted)) {
      $stack = explode(':', $encrypted);
      if (sizeof($stack) != 2) return false;
      if (md5($stack[1] . $plain) == $stack[0]) {
        return true;
      }
    }
    return false;
 }

function tep_not_null($value) {
    if (is_array($value)) {
      if (sizeof($value) > 0) {
        return true;
      } else {
        return false;
      }
    } else {
      if ( (is_string($value) || is_int($value)) && ($value != '') && ($value != 'NULL') && (strlen(trim($value)) > 0)) {
        return true;
      } else {
        return false;
      }
    }
  }

function tep_encrypt_password($plain) {
    $password = '';
    for ($i=0; $i<10; $i++) {
      $password .= tep_rand();
    }
    $salt = substr(md5($password), 0, 2);
    $password = md5($salt . $plain) . ':' . $salt;
    return $password;
  }

function tep_rand($min = null, $max = null) {
    static $seeded;
    if (!isset($seeded)) {
      mt_srand((double)microtime()*1000000);
      $seeded = true;
    }
    if (isset($min) && isset($max)) {
      if ($min >= $max) {
        return $min;
      } else {
        return mt_rand($min, $max);
      }
    } else {
      return mt_rand();
    }
  }

function get_pass_cliente($email){
   $conn = db_connect();
   $query = "SELECT password_cliente FROM clientes WHERE email_cliente = '$email'"; 
   $result = @$conn->query($query);
   if (!$result)
     return false;
   $num_cats = @$result->num_rows;
   if ($num_cats ==0)
      return false;  
   $row = $result->fetch_object();
   return $row->password_cliente;
}


?>