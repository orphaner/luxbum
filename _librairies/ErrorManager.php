<?php

$incErrorManager = true;

class ErrorManager {

    var $errorCounter = Array(0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0);

    var $errorMessage = '';
    var $errorEscape = '';
    var $errorLog = '';
    var $errorAlarme = '';

    var $errorTrackingLevel = 1;
    var $numberError = 0;
    var $maxErrorReport = 0;

    var $errorManagerSystem = true;

    //Constructeur-----------------------------------------------------
    function ErrorManager($errorManagerSystem = '', $level = '', $escape = '', $file = '', $alarme = ''){
		$this -> SetErrorSystem($errorManagerSystem);
		$this -> SetErrorLevel($level);
		$this -> SetErrorEscape($escape);
		$this -> SetErrorAlarme($alarme);
		$this -> SetErrorLog($file);
        }

	//Setting ErrorManager---------------------------------------------------

	function SetErrorSystem($arg = ''){
		if (defined('ERROR_MANAGER_SYSTEM') && ! $arg) $arg = ERROR_MANAGER_SYSTEM;
        $this -> errorManagerSystem = $arg;

        if ($this -> errorManagerSystem != 'off') $this -> errorManagerSystem = true;
        else $this -> errorManagerSystem = false;
		}

	function SetErrorLevel($arg = ''){
		if (defined('ERROR_MANAGER_LEVEL') && ! $arg) $arg = ERROR_MANAGER_LEVEL;
		if ($arg) $this -> errorTrackingLevel = $arg;
		}

	function SetErrorEscape($arg = ''){
		if (defined('ERROR_MANAGER_ESCAPE') && ! $arg) $arg = ERROR_MANAGER_ESCAPE;
		if ($arg && ! $this -> SetErrorOut($arg)) $this -> errorEscape = '';
		}

    function SetErrorAlarme($arg = ''){
    	if (defined('ERROR_MANAGER_ALARME') && ! $arg) $arg = ERROR_MANAGER_ALARME;
    	if ($arg) $this -> errorAlarme = $arg;
    	}

	function SetErrorLog($arg = ''){
		if (defined('ERROR_MANAGER_LOG') && ! $arg) $arg = ERROR_MANAGER_LOG;
		if ($arg) $this -> errorLog = $arg;
		}

    //Paramétrage -----------------------------------------------------------

    function SetErrorLock($func){
        if (strtolower($func) == 'actived') $func = true;
        if (strtolower($func) == 'desactived') $func = false;

        $this -> errorManagerSystem = $func;
        return true;
        }

    function SetErrorOut($url){
        if (is_file($url) || ereg('http://', $url)) {
            $this -> errorEscape = $url;
            return true;
            }
        else return false;
        }

    //Gestionnaire -----------------------------------------------------------

    function ErrorTracker($warning, $message, $func = '', $file = '', $line = ''){

        switch ($warning){
            case 1:
                $type = "Low warning";
                break;
            case 2:
                $type = "Warning";
                break;
            case 3:
                $type = "Notification";
                break;
            case 4:
                $type = "Error";
                break;
            case 5:
                $type = "Emergency break";
                break;
            default:
                $type = "Unknown error";
                $warning = 0;
            }

        $this -> numberError ++;
        if (++ $this -> errorCounter[$warning] > 0 && $warning > $this -> maxErrorReport) $this -> maxErrorReport = $warning;

        if ($this -> numberError > 1) $pre = "\t<li>";
        else $pre = "\n<ul>\n\t<li>";

        $this -> errorMessage .= $pre.$type.' no '.$this -> errorCounter[$warning].' ';

        if ($func) $this -> errorMessage .= 'on <b>'.$func.'</b> ';
        if ($file) $this -> errorMessage .= 'in file <b>'.$file.'</b> ';
        if ($line) $this -> errorMessage .= 'on line <b>'.$line.'</b> ';

        $this -> errorMessage .= ': <br><ul><li><i>'.$message.'</i><br><br></ul>'."\n";
        $this -> ErrorChecker();
        }


    function ErrorChecker($level = ''){
        if ($level == '') $level = $this -> errorTrackingLevel;

        if ($this -> maxErrorReport >= $level) {
            $message = 'The '.date('<b>d/M/Y </b> H:i:s')."<br />\n".'ErrorManager report, you\'ve got '.$this -> numberError.' error(s), see below to correct:'."\n<br>\n".$this -> errorMessage."\n</ul>";

            if ($this -> errorManagerSystem) {

				if ($this -> errorAlarme) {
					$tab = explode(',', $this -> errorAlarme);
					while (list($key, $val) = each($tab)){
				        if (! preg_match('/^(.+)@(.+)\.(.+)$/s', $val)) {
				        	$message .= "<p style='color:red;'>Your ERROR_MANAGER_ALARME mails configurations has got a mistake and was disabled.</p>";
				        	$this -> errorAlarme = '';
				        	}
						}

					if ($this -> errorAlarme) @mail($this -> errorAlarme, '[ErrorManager][Alarm]', $message);
	            	}

				if ($this -> errorLog) {
	                $ouv = @fopen($this -> errorLog, 'a');
	                @fputs($ouv, strip_tags($message));
	                @fclose($ouv);
	            	}

                if ($this -> errorEscape) header('location: '.$this -> errorEscape);
                else {
                    print($message);
                    exit;
                    }
                }
            else {
                if (strtoupper($level) == 'GET') return $message;
                else return false;
                }
            }
        else return true;
        }
    }
?>