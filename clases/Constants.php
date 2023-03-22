<?php
//
class Constants
{
//¡¡¡RECORDAR CAMBIAR EL HOST AL DOMINIO DEL HSOTING O SERVIDOR!!!
  const HOST = 'https://grupo-tecnolavado.com.mx';    // protocolo + host, example: https://milavanderia.com
  //const HOST = 'http://localhost/ventas';
  public static function getHost()
  {
    return self::HOST;
  }
}
