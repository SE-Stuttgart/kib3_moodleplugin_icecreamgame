<?php
// This file is part of Moodle - https://moodle.org/
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

defined('MOODLE_INTERNAL') || die;
require_once("$CFG->libdir/externallib.php");


// require(__DIR__.'/../../config.php');
// require_login();

class mod_icecreamgame_external extends external_api {

    private static function guess_obj_to_array($guess) {
        return array(
            'datapoint' => array(
                'weather' => $guess->weather,
                'day' => $guess->day,
                'temperature' => $guess->temperature,
            ),
            'guess' => $guess->guess
        );
    }


    public static function get_config_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id'),
                'courseid' => new external_value(PARAM_INT, 'course id'),
                'icecreamgameid' => new external_value(PARAM_INT, 'icecreamgame id'),
            )
        );
    }
    public static function get_config_returns() {
        return new external_single_structure(
            array(
                'trials' => new external_value(PARAM_INT, 'number of allowed trials in game'),
                'randomseed' => new external_value(PARAM_INT, 'random seed for datapoints'),
                'noise' => new external_value(PARAM_FLOAT, 'percentage of noise applied to datapoints'),
                'group' => new external_value(PARAM_INT, 'group id user is assigned to, if any - otherwise, 0'),
                'grade' => new external_value(PARAM_FLOAT, 'user grade, if any - otherwise, 0'),
                'guesses' => new external_multiple_structure(
                    new external_single_structure(array(
                        "datapoint" => new external_single_structure(array(
                            'weather' => new external_value(PARAM_TEXT, 'guess weather'),
                            'day' => new external_value(PARAM_TEXT, 'weekday'),
                            'temperature' => new external_value(PARAM_FLOAT, 'temperature'),
                        ), "variable conditions"),
                        'guess' => new external_value(PARAM_FLOAT, "value guessed by user")
                    ), "user's real guesses"
                )),
                'practiceguesses' => new external_multiple_structure(
                    new external_single_structure(array(
                        "datapoint" => new external_single_structure(array(
                            'weather' => new external_value(PARAM_TEXT, 'guess weather'),
                            'day' => new external_value(PARAM_TEXT, 'weekday'),
                            'temperature' => new external_value(PARAM_FLOAT, 'temperature'),
                        ), "variable conditions"),
                        'guess' => new external_value(PARAM_FLOAT, "value guessed by user")
                    ), "user's practice guesses"
                )),
                'warnings' => new external_warnings(),
            )
        );
    }
    public static function get_config($userid, $courseid, $icecreamgameid) {
        global $DB;
        
        $params = self::validate_parameters(self::get_config_parameters(), array('userid' => $userid, 'courseid' => $courseid, 'icecreamgameid' => $icecreamgameid));
        $warnings = array();
      
        try {
            $icecreamgame = $DB->get_record("icecreamgame", array('course'=>$params['courseid'], 'id'=>$params['icecreamgameid']), $fields='*');
            
            // check if current user is already registered in a group or has a grade
            if($DB->record_exists('icecreamgame_grades', array('userid'=>$params['userid']))) {
                // found user -> fetch info
                $usergrade = $DB->get_record('icecreamgame_grades', array('userid'=>$params['userid']), $fields='*');
            }
            else {
                // user doesn't exist yet - create a new grade entry with empty grade and group
                $usergrade = new stdClass();
                $usergrade->icecreamgameid = $params['icecreamgameid'];
                $usergrade->userid = $params['userid'];
                $usergrade->groupnum = 0;
                $usergrade->finalgrade = 0.0; 
                $usergradeid = $DB->insert_record('icecreamgame_grades', $usergrade, false);
            }

            // load user's guesses and practice guesses
            $guesses = array();
            $practiceguesses = array();
            $dbguesses = $DB->get_records("icecreamgame_guesses", array('icecreamgameid'=>$params['icecreamgameid'], 
                                                                        'userid'=>$params['userid'])); 
            foreach($dbguesses as $guess) { 
                if($guess->practice == 1) {
                    $practiceguesses[] = mod_icecreamgame_external::guess_obj_to_array($guess);
                } else {
                    $guesses[] = mod_icecreamgame_external::guess_obj_to_array($guess);
                }
            }

            return array(
                'trials' => $icecreamgame->trials,
                'randomseed' => $icecreamgame->randomseed,
                'noise' => $icecreamgame->noise,
                'group' => $usergrade->groupnum,
                'grade' => $usergrade->finalgrade,
                'guesses' => $guesses,
                'practiceguesses' => $practiceguesses,
                'warnings' => $warnings
            );
        }
        catch(Exception $e) {
            array_push($warnings, $e->getMessage());
        }
        return array(
            'trials' => 0,
            'randomseed' => 0,
            'noise' => 0,
            'group' => 0,
            'grade' => 0,
            'guesses' => "",
            'practiceguesses' => "",
            'warnings' => $warnings
        );
    }


    public static function assign_group_parameters() {
        return new external_function_parameters(
            array(
                'icecreamgameid' => new external_value(PARAM_INT, 'id of icecreamgame'),
                'userid' => new external_value(PARAM_INT, 'id of guessing user'),
                'group' => new external_value(PARAM_INT, 'group number user is assigned to')
            )
        );
    }
    public static function assign_group_returns() {
        return new external_single_structure(
            array(
                'group' => new external_value(PARAM_INT, 'group number user was assigned to'),
                'warnings' => new external_warnings(),
            )
        );
    }
    public static function assign_group($icecreamgameid, $userid, $group) {
        global $DB; 
        
        $params = self::validate_parameters(self::assign_group_parameters(), array('icecreamgameid'=>$icecreamgameid, 'userid'=>$userid, 'group'=>$group));
        $warnings = array();
        $group = $params['group'];

        try {
            // check if user is already assigned to a group
            $usergrade = $DB->get_record('icecreamgame_grades', array('userid'=>$params['userid'], 'icecreamgameid'=>$params['icecreamgameid']), $fields='*'); 
            if($usergrade->groupnum == 0) {
                // user is not assigned to a group yet, update record
                if(0 <= $group && $group < 4) {
                    // valid group index
                    if($group == 0) {
                        // if user chose random assignment (group 0), choose group with fewest members
                        $group = 1;
                        $lowest_count = $DB->count_records("icecreamgame_grades", array(
                            "icecreamgameid" => $icecreamgameid,
                            "groupnum" => 1)
                        );
                        for($i = 2; $i < 4; $i++) {
                            $group_count = $DB->count_records("icecreamgame_grades", array(
                                "icecreamgameid" => $icecreamgameid,
                                "groupnum" => $i)
                            );
                            if($group_count < $lowest_count) {
                                $lowest_count = $group_count;
                                $group = $i;
                            }
                        } 
                    }

                    $usergrade->groupnum = $group;
                    $DB->update_record('icecreamgame_grades', $usergrade);
                } else {
                    array_push($warnings, "Invalid group number: " . $group . '. Supported group numbers are 1, 2 or 3');
                }
            } else {
                array_push($warnings, 'User is already assigned to a group');
            }
        }
        catch(Exception $e) {
            array_push($warnings, $e->getMessage());
        }
        return array(
            'group' => $group,
            'warnings' => $warnings
        );
    }


    public static function add_guess_parameters() {
        return new external_function_parameters(
            array(
                'icecreamgameid' => new external_value(PARAM_INT, 'id of icecreamgame'),
                'userid' => new external_value(PARAM_INT, 'id of guessing user'),
                'guess' => new external_value(PARAM_TEXT, 'user guess history'),
                'practice' => new external_value(PARAM_BOOL, 'is user currently practicing or in real mode'),
                'weather' => new external_value(PARAM_TEXT, 'weather'),
                'day' => new external_value(PARAM_TEXT, 'day'),
                'temperature' => new external_value(PARAM_TEXT, 'temperature'),
            )
        );
    }
    public static function add_guess_returns() {
        return new external_single_structure(
            array(
                'warnings' => new external_warnings(),
            )
        );
    }
    public static function add_guess($icecreamgameid, $userid, $guess, $practice, $weather, $day, $temperature) {
        global $DB; 
        
        $params = self::validate_parameters(self::add_guess_parameters(), array('icecreamgameid'=>$icecreamgameid, 'userid'=>$userid, 'guess'=>$guess, 
                                                                                'practice'=>$practice, 'weather'=>$weather, 'day'=>$day, 'temperature'=>$temperature));
        $warnings = array();

        try {
            // check if user is already assigned to a group
            $usergrade = $DB->get_record('icecreamgame_grades', array('userid'=>$params['userid'], 'icecreamgameid'=>$params['icecreamgameid']), $fields='*'); 
            if($usergrade->groupnum > 0) {
                $DB->insert_record('icecreamgame_guesses', array(
                    "icecreamgameid"=>$params['icecreamgameid'], 'userid'=>$params['userid'], 'practice'=>$params['practice'], 'guess'=>$params['guess'],
                    'weather'=>$params['weather'], 'temperature'=>$params['temperature'], 'day'=>$params['day']
                ));
            } else {
                array_push($warnings, 'User is not yet assigned to a group');
            }
        }
        catch(Exception $e) {
            array_push($warnings, $e->getMessage());
        }
        return array(
            'warnings' => $warnings
        );
    }


    public static function send_grade_parameters() {
        return new external_function_parameters(
            array(
                'icecreamgameid' => new external_value(PARAM_INT, 'id of icecreamgame'),
                'userid' => new external_value(PARAM_INT, 'id of guessing user'),
                'grade' => new external_value(PARAM_FLOAT, 'guessing result')
            )
        );
    }
    public static function send_grade_returns() {
        return new external_single_structure(
            array(
                'warnings' => new external_warnings(),
            )
        );
    }
    public static function send_grade($icecreamgameid, $userid, $grade) {
        global $DB; 
        
        $params = self::validate_parameters(self::send_grade_parameters(), array('icecreamgameid'=>$icecreamgameid, 'userid'=>$userid, 'grade'=>$grade));
        $warnings = array();

        try {
            // check if user is already assigned to a group
            $usergrade = $DB->get_record('icecreamgame_grades', array('userid'=>$params['userid'], 'icecreamgameid'=>$params['icecreamgameid']), $fields='*'); 
            if(0 < $usergrade->groupnum && $usergrade->groupnum < 4) {
                if($usergrade->finalgrade == 0) {
                    // user has not reported final grade yet -> assign
                    $usergrade->finalgrade = $params['grade'];
                    $DB->update_record('icecreamgame_grades', $usergrade);
                    // TODO add grade to gradebook
                } else {
                    array_push($warnings, 'User already sent final grade');
                }
            } else {
                array_push($warnings, 'User is not yet assigned to a group');
            }
        }
        catch(Exception $e) {
            array_push($warnings, $e->getMessage());
        }
        return array(
            'warnings' => $warnings
        );
    }



    public static function reset_group_parameters() {
        return new external_function_parameters(
            array(
                'icecreamgameid' => new external_value(PARAM_INT, 'id of icecreamgame instance'),
                'group' => new external_value(PARAM_INT, 'number of group'),
            )
        );
    }
    public static function reset_group_returns() {
        return new external_single_structure(
            array(
                'warnings' => new external_warnings(),
            )
        );
    }
    public static function reset_group($icecreamgameid, $group) {
        global $DB; 
        
        $params = self::validate_parameters(self::reset_group_parameters(), array('icecreamgameid'=>$icecreamgameid, 'group'=>$group));
        $warnings = array();

        // find all users from group, s.t. we can remove their guesses
        $assigned_userids = $DB->get_fieldset_select("icecreamgame_grades", "userid", "icecreamgameid = ? AND groupnum = ?", array(
            $icecreamgameid,
            $group
        ));
        if(count($assigned_userids) > 0) {
            // reset guesses
            [$_insql_userids, $_insql_userids_params] = $DB->get_in_or_equal($assigned_userids, SQL_PARAMS_QM, 'userid');
            $_insql_userids = 'userid '. $_insql_userids;
            $DB->delete_records_select("icecreamgame_guesses", $_insql_userids, $_insql_userids_params);

            // reset groups
            $DB->delete_records("icecreamgame_grades", array(
                'icecreamgameid' => $icecreamgameid,
                'groupnum' => $group
            ));
        }
      
        return array(
            'warnings' => $warnings
        );
    }


    public static function reassign_member_parameters() {
        return new external_function_parameters(
            array(
                'icecreamgameid' => new external_value(PARAM_INT, 'id of icecreamgame instance'),
                'group' => new external_value(PARAM_INT, 'number of group to assign user to'),
                'username' => new external_value(PARAM_TEXT, 'user name'),
            )
        );
    }
    public static function reassign_member_returns() {
        return new external_single_structure(
            array(
                'warnings' => new external_warnings(),
            )
        );
    }
    public static function reassign_member($icecreamgameid, $group, $username) {
        global $DB; 
        
        $params = self::validate_parameters(self::reassign_member_parameters(), array('icecreamgameid'=>$icecreamgameid, 'group'=>$group, 'username' => $username));
        $warnings = array();

        // find userid from username
        $userid = $DB->get_field("user", "id", array(
            "username" => $username
        ));

        // reset guesses
        $DB->delete_records("icecreamgame_guesses", array(
            'icecreamgameid' => $icecreamgameid,
            'userid' => $userid
        ));

        // update group & reset grade 
        $user_grade = $DB->get_record("icecreamgame_grades", array(
            "icecreamgameid" => $icecreamgameid,
            "userid" => $userid
        ));
        $user_grade->groupnum = $group;
        $user_grade->finalgrade = 0;
        $DB->update_record("icecreamgame_grades", $user_grade);

      
        return array(
            'warnings' => $warnings
        );
    }


    public static function get_instance_members_parameters() {
        return new external_function_parameters(
            array(
                'icecreamgameid' => new external_value(PARAM_INT, 'id of icecreamgame instance'),
            )
        );
    }
    public static function get_instance_members_returns() {
        return new external_single_structure(
            array(
                'usernames' => new external_multiple_structure(
                    new external_value(PARAM_TEXT, "user names")
                ),
            )
        );
    }
    public static function get_instance_members($icecreamgameid) {
        global $DB; 
        
        $params = self::validate_parameters(self::get_instance_members_parameters(), array('icecreamgameid'=>$icecreamgameid));
        $warnings = array();

        // get course module id for icecreamgame instane
        $module_type_id = $DB->get_field("modules", "id", array(
            "name" => "icecreamgame"
        ));
        $cm_id = $DB->get_field("course_modules", "id", array(
            "module" => $module_type_id,
            "instance" => $icecreamgameid
        ));
        $modulecontext = context_module::instance($cm_id);
        
        // find all users enrolled in the course with this icecream game instance
        $enrolled_users = get_enrolled_users($modulecontext);

        $result = array();
        foreach($enrolled_users as $user) {
            if($user->username !== "kib3_webservice")
                array_push($result, $user->username);
        }

        return array(
            'usernames' => $result
        );
    }
    
}