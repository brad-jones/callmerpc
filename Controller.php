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
		// Output each request object
		Server::$log->addDebug(print_r($request, true));
		
		$response->writeHead(200, array('Content-Type' => 'text/plain'));
		$response->end("Hello World\n");
	}
}
