
<?php
/// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     mod_icecreamgame
 * @category    admin
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'mod_icecreamgame_getconfig' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'get_config',          //external function name
        'description' => 'Gets the configuration parameters for the ice cream game instance.',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ),

    'mod_icecreamgame_assigngroup' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'assign_group',          //external function name
        'description' => 'Assigns the user to one of the 3 different groups.',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ),

    'mod_icecreamgame_addguess' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'add_guess',          //external function name
        'description' => 'Stores a new user guess.',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ),

    'mod_icecreamgame_sendgrade' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'send_grade',          //external function name
        'description' => 'Send the final grade for the user',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ), 

    'mod_icecreamgame_reset_group' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'reset_group',          //external function name
        'description' => 'Reset all members of a group and their guesses for a given instance',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ), 

    'mod_icecreamgame_get_instance_members' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'get_instance_members',          //external function name
        'description' => 'Return all enrolled users who could take part in the given icecreamgame instance',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ), 

    'mod_icecreamgame_reassign_member' => array(         //web service function name
        'classname'   => 'mod_icecreamgame_external',  //class containing the external function OR namespaced class in classes/external/XXXX.php
        'methodname'  => 'reassign_member',          //external function name
        'description' => 'Reassign member to specific group in the given icecreamgame instance',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        'ajax' => true,        // is the service available to 'internal' ajax calls. 
        'services' => array(),    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
        'capabilities' => '', // comma separated list of capabilities used by the function.
    ), 
);