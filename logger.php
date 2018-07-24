<?php
/*
Class name - Main
Methods -
Public methods - logInfo, logDebug, logError, getInstance
Private methods - log

Constants defined in constants.php 

define('LOGGER_OFF', 4);
define('LOGGER_DEBUG', 3);
define('LOGGER_INFO', 2);
define('LOGGER_ERROR', 1);

Usage - 
Log levels are (to be) setup in constants 
Use the log level from constants.php in the file in which the logger is used.
You can alse pass the log level  for logging through commandline options
based on the log level passed as level in constructor respective logs are printed
Example , if log level is LOGGER_DEBUG , every level less than or equal to debug level will be logged 

Bracket msgs - input is an array of messages that need to be enclosed in squared brackets.
Example - array('Dealer: 1553434233', 'Campaign: 123') becomes [Dealer: 1553434233] - [Campaign: 123] -

extramsg - if its an array , it will be dumped

Example code to use logger 
	$logger = Logger\Main::getInstance(LOGGER_DEBUG);
	$logger->logInfo('This is an info log');
	$logger->logError('This is an error log', array('Bracket msg1', 'Bracket msg2'));
	$logger->logDebug('This is a debug log');

Sample output :
[time] - [INFO] - This is an info log
[time] - [ERROR] - [Bracket msg1] - [Bracket msg2] - This is an error log
[time] - [DEBUG] - This is a debug log
*/
namespace Logger;

class Main {
	private $level;
	private $dateFormat;
	private static $instance;

	private function __construct($level) {
		$this->level = $level;
		$this->dateFormat = 'Y-m-d H:i:s';	
	}

	private function log($level, $msg, $bracketMsgList, $extraMsg) {
		if($this->level == LOGGER_OFF || $level > $this->level) {
			return;
		}

		$this->printFormattedLogMessage($level, $msg, $bracketMsgList, $extraMsg);
	}

	public function logDebug($msg, $bracketMsgList = array(), $extraMsg = NULL) {
		$this->log(LOGGER_DEBUG, $msg, $bracketMsgList, $extraMsg);
	}

	public function newLine($num = 1) {
		for($i = 0; $i < $num; $i++) {
			echo PHP_EOL;
		}
	}

	public function logInfo($msg, $bracketMsgList = array(), $extraMsg = NULL) {
		$this->log(LOGGER_INFO, $msg, $bracketMsgList, $extraMsg);
	}

	public function logError($msg, $bracketMsgList = array(), $extraMsg = NULL) {
		$this->log(LOGGER_ERROR, $msg, $bracketMsgList, $extraMsg);
	}

	private function printFormattedLogMessage($level, $msg, $bracketMsgList, $extraMsg) {
		$time = @date($this->dateFormat);
		$bracketMsgs = '';
		//formatting messages that need to be bracketted
		if(is_array($bracketMsgList) && count($bracketMsgList) > 0) {
			foreach ($bracketMsgList as $key => $bracketMsg) {
				$bracketMsgs .= " [" . $bracketMsg . "] -";
			}
		}

		switch($level) {
			case LOGGER_INFO:
				echo "[$time] - [INFO] -$bracketMsgs $msg" . PHP_EOL;
				break;

			case LOGGER_ERROR:
				echo "[$time] - [ERROR] -$bracketMsgs $msg" . PHP_EOL;
				break;

			case LOGGER_DEBUG:
				echo "[$time] - [DEBUG] -$bracketMsgs $msg" . PHP_EOL;
				break;

			default:
				echo "[$time] - [LOG] -$bracketMsgs $msg" . PHP_EOL;
				break;
		}

		if($extraMsg !== NULL) {
			if(is_array($extraMsg) || is_object($extraMsg)) {
				print_r($extraMsg);
				echo PHP_EOL;
			} else {
				echo $extraMsg . PHP_EOL;
			}
		}
	}

	private function __clone() {}

	private function __wakeup() {}

	public static function getInstance($level = LOGGER_INFO) {
		if(!isset(self::$instance)) {
			self::$instance = new self($level);
		}
		return self::$instance;
	}

	public function getCurretLogLevel() {
		return $this->level;
	}
}
