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

/*
 * This is an example of how to use CallMeRpc, not the code
 * that makes CallMeRpc actually work. For that see the src directory.
 */

// Load our autoloader - i love composer :)
require('../vendor/autoload.php');

// To intialise a new REST/RPC server.
new GravIT\CallMeRpc\Server('./');

// Yep thats it folks
