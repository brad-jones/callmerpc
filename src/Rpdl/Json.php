<?php
////////////////////////////////////////////////////////////////////////////////
//    _________         __   __      _____        __________                    
//    \_   ___ \_____  |  | |  |    /     \   ____\______   \______   ____      
//    /    \  \/\__  \ |  | |  |   /  \ /  \_/ __ \|       _/\____ \_/ ___\     
//    \     \____/ __ \|  |_|  |__/    Y    \  ___/|    |   \|  |_> >  \___     
//     \______  (____  /____/____/\____|__  /\___  >____|_  /|   __/ \___  >    
//            \/     \/                   \/     \/       \/ |__|        \/     
// =============================================================================
//          Designed and Developed by Brad Jones <brad @="bjc.id.au" />         
// =============================================================================
////////////////////////////////////////////////////////////////////////////////

namespace Gears\CallMeRpc\Rpdl;

class Json extends MethodList
{
	/**
	 * Method: __construct
	 * =========================================================================
	 * Fairly simple really, all we do is take the array that the base
	 * MethodList class creats for us, strip out the docblock to make the
	 * payload a little lighter and then output it as json for any client to
	 * consume and turn into some nice stub methods.
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
		// Make sure the path gets set
		parent::__construct($path);
		
		// We don't care for the docblock stuff so lets get ride of it
		$rpdl = $this->Get(); $json_rpdl = [];
		foreach ($rpdl as $methodname => $method)
		{
			$json_rpdl[$methodname] = $method['params'];
		}
		
		// Output the new array as json
		header('Content-type: application/json;');
		echo json_encode($json_rpdl);
	}
}
