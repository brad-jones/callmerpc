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
