<?php
class Database
{
    public static function Conectar()
    {        
        try
			{
				$conexionn = new PDO('mysql:host=localhost;dbname=rest_eldoradofix;charset=utf8', 'root', '');
	        	$conexionn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);	
	        	return $conexionn;  
			}
				catch(Exception $e)
			{
				die($e->getMessage());
			}
    }
}
?>