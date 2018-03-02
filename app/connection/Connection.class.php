<?php

abstract class Connection {

    private static $host = HOST;
    private static $user = USER;
    private static $pass = PASS;
    private static $base = BASE;

    /** @var PDO retorna um objeto PDO */
    private static $connect = null;

    private static function conectar() {
        try {
            if (self::$connect == null) {
                //cada banco tem o seu formato de DSN
                $dsn = 'mysql:host=' . self::$host . ';dbname=' . self::$base . ';';
                //configura como o meu banco vai trabalhar , neste caso configura os caracteres com UTF8
                $options = [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'];
                self::$connect = new PDO($dsn, self::$user, self::$pass, $options);
            }
        } catch (Exception $ex) {
            backErro($ex->getCode(), $ex->getMessage(), $ex->getFile(), $ex->getLine());
            die;
        }
        self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //aqui retornanmos a conexão
        return self::$connect;
    }

    protected static function getConnection() {
        //usa-se self para apontar para static
        return self::conectar();
    }

}
