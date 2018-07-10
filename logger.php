<?php
/*
Class name - Logger
Methods -
Public methods - logInfo, logDebug, logError
Private methods - log

Usage - 
Log levels are setup in constants file
Pass  ,log level (level) for logging.
based on the log level passed as level in constructor respective logs are printed
Example , if log level is DEBUG , every level less than or equal to debug level will be logged 

Bracket msgs - input is an array of messages that need to be enclosed in squared brackets.
Example - array('Name: ABC', 'Campaign: 123') becomes [Name: ABC] - [Campaign: 123] -
*/
namespace Logger;
class Logger {

	private $level;
	private $dateFormat;
	private static $instance;
	private function __construct($level) {
		if($level == LOGGER_OFF) return;
		$this->level = $level;
		$this->dateFormat = date("Y-m-d H:i:s");	
	}

	private function log($level,$msg,$bracketMsgList) {
		if($level <= $this->level) {
			$line = $this->getFormattedLogMessage($level,$msg,$bracketMsgList);
			echo $line;
		}
	}

	public function logDebug($msg,$bracketMsgList=array()) {
		$this->log(LOGGER_DEBUG, $msg, $bracketMsgList);
	}

	public function logInfo($msg,$bracketMsgList=array()) {
		$this->log(LOGGER_INFO, $msg, $bracketMsgList);
	}

	public function logError($msg,$bracketMsgList=array()) {
		$this->log(LOGGER_ERROR, $msg, $bracketMsgList);
	}

	private function getFormattedLogMessage($level,$msg,$bracketMsgList) {
		$time = date($this->dateFormat);
		$bracketMsgs = '';
		// formatting messages that need to be bracketted.
		foreach ($bracketMsgList as $key => $bracketMsg) {
			$bracketMsgs .= "[" . $bracketMsg . "] - ";
		}
		switch($level) {
			case LOGGER_INFO:
				return "[$time] - [INFO] - $bracketMsgs $msg" . PHP_EOL;

			case LOGGER_ERROR:
				return "[$time] - [ERROR] - $bracketMsgs $msg" . PHP_EOL;

			case LOGGER_DEBUG:
				return "[$time] - [DEBUG] - $bracketMsgs $msg" . PHP_EOL;

			default:
				return  "[$time] - [LOG] - $bracketMsgs $msg" . PHP_EOL;
		}
	}

	public static function getInstance($level) {
		if(!isset(self::$instance)) {
			self::$instance = new Logger($level);
		}
		return self::$instance;
	}
}
