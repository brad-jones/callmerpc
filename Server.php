<?php
////////////////////////////////////////////////////////////////////////////////
// __________ __             ________                   __________              
// \______   \  |__ ______  /  _____/  ____ _____ ______\______   \ _______  ___
//  |     ___/  |  \\____ \/   \  ____/ __ \\__  \\_  __ \    |  _//  _ \  \/  /
//  |    |   |   Y  \  |_> >    \_\  \  ___/ / __ \|  | \/    |   (  <_> >    < 
//  |____|   |___|  /   __/ \______  /\___  >____  /__|  |______  /\____/__/\_ \
//                \/|__|           \/     \/     \/             \/            \/
// =============================================================================
//         Designed and Developed by Brad Jones <bj @="gravit.com.au" />        
// =============================================================================
////////////////////////////////////////////////////////////////////////////////

namespace Gears\CallMeRpc;

// Import monolog
use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\RotatingFileHandler;
use \Monolog\Processor\IntrospectionProcessor;

/**
 * Class: Server
 * =============================================================================
 * This is the main HTTP Server for CallMeRpc, ie: You no longer need Apache,
 * Nginx or some other Web Server, it is now built in using the reactphp
 * framework.
 */
class Server
{
	/**
	 * Property: log
	 * =========================================================================
	 * This stores a monolog instance. If one is not supplied by the consurmer
	 * of this class we will create our own. If a string has been supplied
	 * instead of a monolong instance we will assume it is a file path.
	 */
	public static $log = './callmerpc.log';
	
	/**
	 * Property: loglevel
	 * =========================================================================
	 * How much detail to you want to see in your log file. We default to debug.
	 */
	private $loglevel = Logger::DEBUG;
	
	/**
	 * Property: timezone
	 * =========================================================================
	 * This stores the timezone that we are running in, I live in Geelong.
	 * So it defaults to GMT+10.
	 */
	private $timezone = 'Australia/Melbourne';
	
	/**
	 * Property: ip
	 * =========================================================================
	 * What IP will we listen to? Defaults to everything.
	 */
	private $ip = '0.0.0.0';
	
	/**
	 * Property: port
	 * =========================================================================
	 * What port will the server listen on?
	 * Defaults to 1337, hey cause we are elite :)
	 */
	private $port = 1337;
	
	/**
	 * Property: path
	 * =========================================================================
	 * This stores the root location of the rpc method files.
	 */
	private $path = './methods';
	
	/**
	 * Method: __construct
	 * =========================================================================
	 * Starts a new RPC server.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $options - An array of values, with keys the same as the properties above
	 * 
	 * Throws:
	 * -------------------------------------------------------------------------
	 *  - If the path to the RPC methods is invalid.
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * void
	 */
	public function __construct($options = array())
	{
		// Output our header
		$this->OutputHeader();
		
		// Set some options
		foreach ($options as $key => $value)
		{
			if (isset($this->{$key}))
			{
				$this->{$key} = $value;
			}
			elseif (isset(self::$key))
			{
				self::$key = $value;
			}
		}
		
		// Setup our logger
		if (!self::$log instanceof Logger)
		{
			// Copy the filepath
			$logfile = self::$log;
			
			// Create a new monolog logger
			self::$log = new Logger('log');
			
			// Log to the console
			self::$log->pushHandler
			(
				new StreamHandler('php://stdout', $this->loglevel)
			);
			
			// As well as to a file
			self::$log->pushHandler
			(
				new RotatingFileHandler($logfile, 7, $this->loglevel)
			);
			
			// This will tell us where the log entry was made from
			self::$log->pushProcessor(new IntrospectionProcessor());
		}
		
		// Set the default timezone
		date_default_timezone_set($this->timezone);
		
		// Setup the HTTP Server
		$loop = React\EventLoop\Factory::create();
		$socket = new React\Socket\Server($loop);
		$http = new React\Http\Server($socket, $loop);
		
		// Pass each request to our request handler
		$http->on('request', function($req, $res)
		{
			new Gears\CallMeRpc\Controller($req, $res);
		});
		
		// Bind the server to an ip and port
		$socket->listen($this->port, $this->ip);
		
		// Tell the world whats happening
		self::$log->addInfo
		(
			'CallMeRpc running at http://'.$this->ip.':'. $this->port.'/'
		);
		
		// Start the loop
		$loop->run();
	}
	
	/**
	 * Method: OutputHeader
	 * =========================================================================
	 * This just spits out a nice looking ASCII Header.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Throws:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	private function OutputHeader()
	{
		echo
			"\n".
			"      _________         __   __      _____        __________                    \n".
			"      \_   ___ \_____  |  | |  |    /     \   ____\______   \______   ____      \n".
			"      /    \  \/\__  \ |  | |  |   /  \ /  \_/ __ \|       _/\____ \_/ ___\     \n".
			"      \     \____/ __ \|  |_|  |__/    Y    \  ___/|    |   \|  |_> >  \___     \n".
			"       \______  (____  /____/____/\____|__  /\___  >____|_  /|   __/ \___  >    \n".
			"              \/     \/                   \/     \/       \/ |__|        \/     \n".
			"\n"
		;
	}
}
