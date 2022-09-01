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

class restore_icecreamgame_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {
        $userinfo = $this->get_setting_value('userinfo');

        $paths = array();
        $paths[] = new restore_path_element('icecreamgame', '/activity/icecreamgame');
        if ($userinfo) {
            $paths[] = new restore_path_element('icecreamgame_grade', '/activity/icecreamgame/customgrades/customgrade');
            $paths[] = new restore_path_element('icecreamgame_guess', '/activity/icecreamgame/guesses/guess');
        }

        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_icecreamgame($data) {
        global $DB;
        $data = (object)$data;
        $data->course = $this->get_courseid();
        $newitemid = $DB->insert_record('icecreamgame', $data);
        $this->apply_activity_instance($newitemid);
    }

    protected function process_icecreamgame_grade($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        $data->icecreamgameid = $this->get_new_parentid('icecreamgame');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $newid = $DB->insert_record('icecreamgame_grades', $data);
        // $this->set_mapping('grades', $oldid, $newid);
    }

    protected function process_icecreamgame_guess($data) {
        global $DB;
        $data = (object)$data;
        $oldid = $data->id;
        $data->icecreamgameid = $this->get_new_parentid('icecreamgame');
        $data->userid = $this->get_mappingid('user', $data->userid);
        $newid = $DB->insert_record('icecreamgame_guesses', $data);    
        // $this->set_mapping('grades', $oldid, $newid);
    }
}
