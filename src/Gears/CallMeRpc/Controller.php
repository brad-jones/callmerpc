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

class Controller
{
	/**
	 * Property: request
	 * =========================================================================
	 * This is where we will keep a copy of the request object.
	 */
	private $request;
	
	/**
	 * Property: response
	 * =========================================================================
	 * This is where we will keep a copy of the response object.
	 */
	private $response;
	
	/**
	 * Property: length
	 * =========================================================================
	 * This is used by ParsePost() to parse a post request.
	 */
	private $length = 0;
	
	/**
	 * Property: getdata
	 * =========================================================================
	 * The raw GET query data assumed to be JSON is stored here.
	 */
	private $getdata = '';
	
	/**
	 * Property: postdata
	 * =========================================================================
	 * The raw POST data assumed to be JSON is stored here.
	 */
	private $postdata = '';
	
	/**
	 * Method: __construct
	 * =========================================================================
	 * This will process each request and decide what to do with it.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $request - The phpreact http request object
	 * $response - The phpreact http response object
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * void
	 */
	public function __construct($request, $response)
	{
		// Set some properties
		$this->request = $request;
		$this->response = $response;
		
		// Output each request object
		Server::$log->addDebug(print_r($this->request, true));
		
		// We don't care for any other requests
		if ($this->request->getPath() == '/')
		{
			// Check for an RPDL request
			if (isset($this->request->getQuery()['rpdl']))
			{
				// Tell the world whats happening
				Server::$log->addInfo('Successful RPDL JSON Request');
				
				// Show the json version of the descriptor page
				$page = new Rpdl\Json();
				$this->response->writeHead(200, ['Content-Type' => 'application/json']);
				$this->response->end($page->Render());
			}
			else
			{
				// Bind our ParseRequest handler to the rpc event
				$this->request->on('rpc', [$this, 'ParseRequest']);
				
				// Work our what sort of request it is
				switch ($this->request->getMethod())
				{
					case 'GET':
						
						// Grab the json request
						$this->getdata = key($this->request->getQuery());
						
						// Do we actually have anything
						if ($this->getdata == '')
						{
							// Tell the world whats happening
							Server::$log->addInfo('Successful RPDL HTML Request');
							
							// Show the HTML human readable version
							// of the descriptor page
							$page = new Rpdl\Html();
							$this->response->writeHead(200, ['Content-Type' => 'text/html']);
							$this->response->end($page->Render());
						}
						else
						{
							// Emit a new rpc event
							$this->request->emit('rpc', [$this->getdata]);
						}
						
					break;
					
					case 'POST':
						
						// Grab the headers
						$headers = $this->request->getHeaders();
						
						// Check we have content length
						if (isset($headers['Content-Length']))
						{
							// Run this for each chunk of data we get
							$this->request->on('data', [$this, 'ParsePost']);
						}
						else
						{
							// Can't process post request because we don't
							// know it's total length. Someone alot smarter
							// than I will hopefully implement full HTTP 1.1
							Server::$log->addError('No Length Provided');
							$this->response->writeHead(411);
							$this->response->end();
						}
						
					break;
				}
			}
		}
		else
		{
			// No Content to return
			$this->response->writeHead(204);
			$this->response->end();
		}
	}
	
	/**
	 * Method: ParsePost
	 * =========================================================================
	 * Okay so the React HTTP component isn't yet up to the HTTP 1.1 spec.
	 * There are some issues parsing chunked transfers. I have noticed that
	 * most clients will still send a Content-Length header even if
	 * Transfer-Encoding: chunked is sent. Hence this is how I am parsing my
	 * post requests for the time being.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $data - A chunk of post data
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * Once we have the last chunk we will fire the rpc event.
	 * Which call ParseRequest.
	 */
	public function ParsePost($data)
	{
		// Add up the length
		$this->length = $this->length + strlen($data);
		
		// Only go as far as we need to
		if ($this->length <= $this->request->getHeaders()['Content-Length'])
		{
			// Get the chuncked data
			$this->postdata .= $data;
			
			// Detect last chunk
			if ($this->length == $this->request->getHeaders()['Content-Length'])
			{
				// Emit a new event
				$this->request->emit('rpc', [$this->postdata]);
			}
		}
	}
	
	/**
	 * Method: ParseRequest
	 * =========================================================================
	 * Okay so at this point it is expected that a JSON message, either via GET
	 * or POST has been provided. This method will parse that JSON message.
	 * And run the appriorate actions.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $json - A JSON string.
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * This will either pass execution on to ReturnResponse()
	 * or throw an Exception.
	 */
	public function ParseRequest($json)
	{
		// Decode the json
		$request = json_decode($json);
		
		// Do we have a valid method name
		if (isset($request->method))
		{
			// Make sure it does not contain any double dots
			if (strpos($request->method, '..') === false)
			{
				// Build the full file path to the method file
				$method_file = Server::$path.'/'.$request->method.'.php';
				
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
								$this->ReturnError
								(
									'You are missing a function parameter! '.
									'Please add `'.$param->getName().'` - '.
									'oh yeah I remember now...'
								);
							}
						}
					}
					
					// Invoke the function
					$results = $function->invokeArgs($args);
					
					// Check for an error
					if (isset($results['error']))
					{
						$this->ReturnError($results['error']);
					}
					else
					{
						$this->ReturnResponse($results, $request);
					}
				}
				else
				{
					// 404 error
					$this->ReturnError
					(
						'You have requested a method that does not exist, '.
						'this is your 404 error!'
					);
				}
			}
			else
			{
				// Hacking attempt
				$this->ReturnError
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
			$this->ReturnError('Invalid method name!');
		}
	}
	
	/**
	 * Method: ReturnResponse
	 * =========================================================================
	 * Okay so if we get to this point we have called one of our methods.
	 * That method will have returned an array. It is our job to encode that
	 * back into JSON and send it back to the client.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $data - A PHP array
	 * $request - The request array
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	private function ReturnResponse($data, $request)
	{
		// Tell the world whats happening
		Server::$log->addInfo('Successful Request', [(array) $request, $data]);
		
		// Send the response
		$this->response->writeHead(200, ['Content-Type' => 'application/json']);
		$this->response->end(json_encode
		([
			'response' => 'success',
			'results' => $data
		]));
	}
	
	/**
	 * Method: ReturnError
	 * =========================================================================
	 * Something bad happened obviously, it is now our job to format the
	 * error into a readable format for the client.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	public function ReturnError($error)
	{
		// Tell the world whats happening
		Server::$log->addWarning('Request Error', [$error]);
		
		// Send the response
		$this->response->writeHead(400, ['Content-Type' => 'application/json']);
		$this->response->end(json_encode
		([
			'response' => 'fail',
			'results' => $error
		]));
	}
}
