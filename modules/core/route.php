<?php
defined('PLAYTOGET') || exit('Playtoget: access denied!');

class Route
{

    static function start()
    {
        try {
            $controller_name = core::database()->escape(Core_Array::getRequest('task', 'news'));
            core::addSetting( array('controller'=> $controller_name ) );
            
            $model_name = 'Model_' . $controller_name;
            $controller_name = 'Controller_' . $controller_name;
            $action_name = 'action_index' . $action_name;
            
            if (! core::requireEx('models', strtolower($model_name) . '.php')){
				header("HTTP/1.1 404 Not Found");
				header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
				exit;               
            }
            if (! core::requireEx('controllers', strtolower($controller_name) . '.php')){
                header("HTTP/1.1 404 Not Found");
				header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
				exit;
            }
            if (class_exists($controller_name)) {

                $controller = core::factory($controller_name);
                if (method_exists($controller, $action_name)) {
                    $controller->$action_name();
                } else {
                    header("HTTP/1.1 404 Not Found");
					header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
					exit;
                }
            } else {
				header("HTTP/1.1 404 Not Found");
				header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
				exit;               
            }
        } catch (ExceptionMySQL $exc) {
            if (DEBUG == 1) {
                echo "<!DOCTYPE html>";
                echo "<html>";
                echo "<head>";
                echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
                echo "<title>Error</title>";
                echo "</head>";
                echo "<body>";
                echo "<p>An error occurred while accessing the MySQL database!</p>";
                echo "<p>" . $exc->getMySQLError() . "<br>" . nl2br($exc->getSQLQuery()) . "</p>";
                echo "<p>Error in file " . $exc->getFile() . " at line " . $exc->getLine() . "</p>";
                echo "</body>";
                echo "</html>";
            } else {
                header('HTTP/1.1 500 Internal Server Error');
                header("Location: http://" . $_SERVER['SERVER_NAME'] . "/500.html");
                exit();
            }
        } catch (Exception $exc) {
            echo "<!DOCTYPE html>";
            echo "<html>";
            echo "<head>";
            echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
            echo "<title>Error</title>";
            echo "</head>";
            echo "<body>";
            echo "<p>" . $exc->getMessage() . "</p>";
            echo "</body>";
            echo "</html>";
        }
    }

    static function ShowMySQLError($error, $query, $msg)
    {
        throw new ExceptionMySQL($error, $query, $msg);
    }

    static function ShowError($msg)
    { 
		if (DEBUG == 1) {
			//var_dump($_REQUEST);
			throw new Exception($msg);
		}
		else{
			header("HTTP/1.1 404 Not Found");
			header("Location: http://" . $_SERVER['SERVER_NAME'] . "/404.html"); 
			exit;
		}
    }
}