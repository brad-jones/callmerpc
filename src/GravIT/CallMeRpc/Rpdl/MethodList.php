<?php
////////////////////////////////////////////////////////////////////////////////
//    _________         __   __      _____        __________                    
//    \_   ___ \_____  |  | |  |    /     \   ____\______   \______   ____      
//    /    \  \/\__  \ |  | |  |   /  \ /  \_/ __ \|       _/\____ \_/ ___\     
//    \     \____/ __ \|  |_|  |__/    Y    \  ___/|    |   \|  |_> >  \___     
//     \______  (____  /____/____/\____|__  /\___  >____|_  /|   __/ \___  >    
//            \/     \/                   \/     \/       \/ |__|        \/     
// =============================================================================
//         Designed and Developed by Brad Jones <bj @="gravit.com.au" />
// =============================================================================
////////////////////////////////////////////////////////////////////////////////

namespace GravIT\CallMeRpc\Rpdl;

class MethodList
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
	 * 
	 * Parameters:
	 * -------------------------------------------------------------------------
	 * $path - The directory that the method files are stored in.
	 * 
	 * Throws:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	public function __construct($path)
	{
		// We don't bother doing any checks this time.
		// We assume if you called this directly you were
		// smart enough to check yourself.
		$this->path = $path;
	}
	
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
	 * Throws:
	 * -------------------------------------------------------------------------
	 * n/a
	 * 
	 * Returns:
	 * -------------------------------------------------------------------------
	 * n/a
	 */
	protected function Get()
	{
		// This is what we will return
		$methods = [];
		
		// Loop through all our method files
		$objects = new \RecursiveIteratorIterator
		(
			new \RecursiveDirectoryIterator($this->path)
		);
		foreach($objects as $file => $object)
		{
			// Strip out some files from the list
			if
			(
				strpos($file, $_SERVER['SCRIPT_NAME']) === false AND
				strpos($file, '/.') === false AND
				strpos($file, '/..') === false
			)
			{
				// Include the method file
				require($file);
				
				// Extract the method name from the filename
				$path_parts = pathinfo($file);
				$methodname = str_replace
				(
					'/',
					'\\',
					str_replace
					(
						$this->path,
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
