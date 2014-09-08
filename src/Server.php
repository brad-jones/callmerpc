<?php
////////////////////////////////////////////////////////////////////////////////
//    _________         __   __      _____        __________                    
//    \_   ___ \_____  |  | |  |    /     \   ____\______   \______   ____      
//    /    \  \/\__  \ |  | |  |   /  \ /  \_/ __ \|       _/\____ \_/ ___\     
//    \     \____/ __ \|  |_|  |__/    Y    \  ___/|    |   \|  |_> >  \___     
//     \______  (____  /____/____/\____|__  /\___  >____|_  /|   __/ \___  >    
//            \/     \/                   \/     \/       \/ |__|        \/     
// -----------------------------------------------------------------------------
//          Designed and Developed by Brad Jones <brad @="bjc.id.au" />         
// -----------------------------------------------------------------------------
////////////////////////////////////////////////////////////////////////////////

namespace Gears\CallMeRpc;

/**
 * Class: Server
 * =============================================================================
 * This is the main class that you will use to create your RPC server.
 * It contains all the wonderfull magic that ties everything together.
 */
class Server
{
	/**
	 * Property: path
	 * =========================================================================
	 * This stores the root location of the rpc method files.
	 */
	private $path = '';
	
	/**
	 * Method: __construct
	 * =========================================================================
	 * Starts a new RPC server.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $path - The directory that the method files are stored in.
	 * 
	 * Throws:
	 * -------------------------------------------------------------------------
	 *  - If the path to the RPC methods is invalid.
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * void
	 */
	public function __construct($path)
	{
		// Setup some error handling
		$this->ExceptionHandler();
		
		// Normalise the path
		$this->path = realpath($path);
		
		// Make sure it exists
		if (!is_dir($this->path))
		{
			throw new \Exception
			(
				'The path you have specfied to your RPC Methods is invalid! `'
				.$this->path.'`'
			);
		}
		
		if (isset($_GET['rpdl']))
		{
			// Show the json version of the descriptor page
			new Rpdl\Json($this->path);
		}
		else
		{
			// Get our request data
			$GET_REQUEST = urldecode($_SERVER['QUERY_STRING']);
			$POST_REQUEST = file_get_contents("php://input");
			
			// What type of request is it?
			if (!empty($GET_REQUEST))
			{
				$this->ParseRequest($GET_REQUEST);
			}
			elseif(!empty($POST_REQUEST))
			{
				$this->ParseRequest($POST_REQUEST);
			}
			else
			{
				// Show the html version of the descriptor page
				new Rpdl\Html($this->path);
			}
		}
	}
	
	/**
	 * Method: ParseRequest
	 * =========================================================================
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $request_json - 
	 * 
	 * Throws:
	 * -------------------------------------------------------------------------
	 * 
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	private function ParseRequest($request_json)
	{
		// Decode the json
		$request = json_decode($request_json);
		
		// Do we have a valid method name
		if (isset($request->method))
		{
			// Make sure it does not contain any double dots
			if (strpos($request->method, '..') === false)
			{
				// Build the full file path to the method file
				$method_file = $this->path.'/'.$request->method.'.php';
				
				// Does the file exist
				if (file_exists($method_file))
				{
					// It does so lets include it
					require_once($method_file);
					
					// Swap out any path slashes for the other type
					$funcname = str_replace('/', '\\', $request->method);
					
					// Create the argument array
					$args = [];
					$function = new \ReflectionFunction($funcname);
					foreach ($function->getParameters() as $param)
					{
						if (isset($request->{$param->getName()}))
						{
							$args[$param->getPosition()] =
								$request->{$param->getName()};
						}
						else
						{
							if ($param->isOptional() === false)
							{
								throw new \Exception
								(
									'You are missing a function parameter! '.
									'Please add `'.$param->getName().'` - '.
									'oh yeah I remember now...'
								);
							}
						}
					}
					
					// Invoke the function
					$this->ReturnResponse($function->invokeArgs($args));
				}
				else
				{
					// 404 error
					throw new \Exception
					(
						'You have requested a method that does not exist, '.
						'this is your 404 error!'
					);
				}
			}
			else
			{
				// Hacking attempt
				throw new \Exception
				(
					'Your method name cannot have `..` in it - '.
					'its almost like you are trying to hack in to my server...'.
					'go hack someone who cares!'
				);
			}
		}
		else
		{
			// The method for whatever reason wasn't valid
			throw new \Exception('Invalid method name!');
		}
	}
	
	/**
	 * Method: ReturnResponse
	 * =========================================================================
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $data - 
	 * 
	 * Throws:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	private function ReturnResponse($data)
	{
		header('Content-type: application/json;');
		echo json_encode
		([
			'response' => 'success',
			'results' => $data
		]);
	}
	
	/**
	 * Method: ExceptionHandler
	 * =========================================================================
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
	private function ExceptionHandler()
	{
		set_exception_handler(function($e)
		{
			header('Content-type: application/json;');
			echo json_encode
			([
				'response' => 'fail',
				'message' => $e->getMessage(),
			]);
		});
	}
}
