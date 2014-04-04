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

/*
 * This is an example of how to use CallMeRpc, not the code
 * that makes CallMeRpc actually work. For that see the src directory.
 */

// Note for this example we are not using composer
// hence these manual require statments.
require('../src/Server.php');
require('../src/Rpdl/MethodList.php');
require('../src/Rpdl/Json.php');
require('../src/Rpdl/Html.php');

// To intialise a new REST/RPC server.
new Gears\CallMeRpc\Server('./');

// Yep thats it folks

/*
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 * PLEASE NOTE FOR SECURITY REASONS I WOULD HIGHLY RECOMMEND
 * YOU PLACE YOUR FUNCTION FILES OUTSIDE OF YOUR WEB SERVER
 * DOCUMENT ROOT OR DISALLOW ACCESS TO THEM IN SOME FASHION.
 * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
 */
