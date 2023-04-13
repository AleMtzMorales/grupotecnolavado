<?php
//
class Constants
{
//¡¡¡RECORDAR CAMBIAR EL HOST AL DOMINIO DEL HSOTING O SERVIDOR!!!
  const HOST = 'http://localhost/ventas/app';    // protocolo + host, example: https://milavanderia.com

  public static function getHost()
  {
    return self::HOST;
  }
}
