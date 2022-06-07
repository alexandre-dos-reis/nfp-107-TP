<?php
return array(
	"siteUrl" => "http://127.0.0.1:8090/",
	"database" => [
		"type" => "mysql",
		"dbName" => "click-and-collect-ubiquity",
		"serverName" => "127.0.0.1",
		"port" => "3309",
		"user" => "root",
		"password" => "password",
		"options" => [],
		"cache" => false
	],
	"sessionName" => "appubiquity",
	"namespaces" => [],
	"templateEngine" => 'Ubiquity\\views\\engine\\Twig',
	"templateEngineOptions" => [
		"cache" => false,
		'extensions' => ['\\Twig\\Extension\\DebugExtension'],
		'debug' => true
	],
	"test" => false,
	"debug" => false,
	"logger" => function () {
		return new \Ubiquity\log\libraries\UMonolog("app-ubiquity", \Monolog\Logger::INFO);
	},
	"di" => ["@exec" => ["jquery" => function ($controller) {
		return \Ajax\php\ubiquity\JsUtils::diSemantic($controller);
	}]],
	"cache" => ["directory" => "cache/", "system" => "Ubiquity\\cache\\system\\ArrayCache", "params" => []],
	"mvcNS" => ["models" => "models", "controllers" => "controllers", "rest" => ""]
);
