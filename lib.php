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
 * Library of interface functions and constants.
 *
 * @package     mod_icecreamgame
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function icecreamgame_supports($feature) {
    switch ($feature) {
        case FEATURE_MOD_INTRO:
            return true;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_icecreamgame into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_icecreamgame_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function icecreamgame_add_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timecreated = time();

    $id = $DB->insert_record('icecreamgame', $moduleinstance);

    return $id;
}

/**
 * Updates an instance of the mod_icecreamgame in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_icecreamgame_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function icecreamgame_update_instance($moduleinstance, $mform = null) {
    global $DB;

    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;

    return $DB->update_record('icecreamgame', $moduleinstance);
}

/**
 * Removes an instance of the mod_icecreamgame from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function icecreamgame_delete_instance($id) {
    global $DB;

    $exists = $DB->get_record('icecreamgame', array('id' => $id));
    if (!$exists) {
        return false;
    }

    // delete related items 
    $DB->delete_records('icecreamgame_grades', array('icecreamgameid' => $id));
    $DB->delete_records('icecreamgame_guesses', array('icecreamgameid' => $id));

    $DB->delete_records('icecreamgame', array('id' => $id));

    return true;
}

function icecreamgame_grade_item_update($icecreamgame, $grades=NULL) {
    global $CFG;
    if (!function_exists('grade_update')) { //workaround for buggy PHP versions
        require_once($CFG->libdir.'/gradelib.php');
    }

    $params = array('itemname'=>$icecreamgame->name);
    $params['gradetype'] = GRADE_TYPE_VALUE;
    $params['grademin']  = 0;
    $params['grademax']  = 10;

    if ($grades  === 'reset') {
        $params['reset'] = true;
        $grades = NULL;
    }

    return grade_update('mod/icecreamgame', $icecreamgame->course, 'mod', 'icecreamgame', $icecreamgame->id, 0, $grades, $params);
}

function icecreamgame_update_grades($icecreamgame, $userid=0, $nullifnone=true) {
    global $CFG, $DB;
    require_once($CFG->libdir.'/gradelib.php');

    // if ($grades = icecreamgame_get_user_grades($forum, $userid)) {
    //     icecreamgame_grade_item_update($forum, $grades);

    // } else if ($userid and $nullifnone) {
    //     $grade = new stdClass();
    //     $grade->userid   = $userid;
    //     $grade->rawgrade = NULL;
    //     icecreamgame_grade_item_update($forum, $grade);

    // } else {
    //     icecreamgame_grade_item_update($forum);
    // }
}

/**
 * Return grade for given user or all users.
 *
 * @param int $quizid id of quiz
 * @param int $userid optional user id, 0 means all users
 * @return array array of grades, false if none. These are raw grades. They should
 * be processed with quiz_format_grade for display.
 */
// function icecreamgame_get_user_grades($icecreamgame, $userid = 0) {
//     global $CFG, $DB;

//     $params = array($icecreamgame->id);
//     $usertest = '';
//     if ($userid) {
//         $params[] = $userid;
//         $usertest = 'AND u.id = ?';
//     }
//     return $DB->get_records_sql("
//             SELECT
//                 u.id,
//                 u.id AS userid,
//                 qg.grade AS rawgrade,
//                 qg.timemodified AS dategraded,
//                 MAX(qa.timefinish) AS datesubmitted

//             FROM {user} u
//             JOIN {icecreamgame_grades} qg ON u.id = qg.userid

//             WHERE qg.icecreamgameid = ?
//             $usertest
//             GROUP BY u.id, qg.grade, qg.timemodified", $params);
// }