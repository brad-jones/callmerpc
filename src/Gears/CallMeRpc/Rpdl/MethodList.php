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

namespace Gears\CallMeRpc\Rpdl;

abstract class MethodList
{
	/**
	 * Method: Render
	 * =========================================================================
	 * Must be provided by the extending class
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * string
	 */
	abstract public function Render();
	
	/**
	 * Method: Get
	 * =========================================================================
	 * This method generates an array of all the remote procedure methods,
	 * with their parameters and other details. This array is then used
	 * to create both the JSON and HTML5 desciptor files or RPDL my version
	 * of WSDL.
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * array
	 */
	protected function Get()
	{
		// This is what we will return
		$methods = [];
		
		// Loop through all our method files
		$objects = new \RecursiveIteratorIterator
		(
			new \RecursiveDirectoryIterator(\Gears\CallMeRpc\Server::$path)
		);
		
		foreach($objects as $file => $object)
		{
			// Strip out some files from the list
			if
			(
				strpos($file, '/.') === false AND
				strpos($file, '/..') === false
			)
			{
				// Include the method file
				require_once($file);
				
				// Extract the method name from the filename
				$path_parts = pathinfo($file);
				$methodname = str_replace
				(
					'/',
					'\\',
					str_replace
					(
						\Gears\CallMeRpc\Server::$path,
						'',
						$path_parts['dirname']
					).
					'/'.$path_parts['filename']
				);
				
				// First lets just make sure the function exists
				if (function_exists($methodname))
				{
					// Do some reflection on the method
					$refFunc = new \ReflectionFunction($methodname);
					
					// Convert the methodname back to filepath style
					$methodname = str_replace('\\', '/', $methodname);
					$methodname = substr($methodname, 1);
					
					// Add the method to the array
					$methods[$methodname] =
					[
						'docblock' => $refFunc->getDocComment(),
						'params' => []
					];
					
					// Loop through each param
					foreach ($refFunc->getParameters() as $param)
					{
						// Add the param to the method
						$methods[$methodname]['params'][$param->getName()] =
						[
							'optional' => ($param->isOptional()?'true':'false'),
						];
					}
				}
			}
		}
		
		// Sort array
		ksort($methods);
		
		// Return an array of all the methods and their parameters
		return $methods;
	}
}
