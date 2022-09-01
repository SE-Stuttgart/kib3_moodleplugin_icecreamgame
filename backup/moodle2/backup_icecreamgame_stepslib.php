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

defined('MOODLE_INTERNAL') || die();

// See https://docs.moodle.org/dev/Backup_API#API_for_activity_modules
class backup_icecreamgame_activity_structure_step extends backup_activity_structure_step {
    protected function define_structure() {
        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define backup elements
        $icecreamgame = new backup_nested_element('icecreamgame',  array('id'), array(
            'name', 'timecreated', 'timemodified', 'intro', 'introformat', 'trials', 'randomseed', 'noise'));
        $guesses = new backup_nested_element('guesses');
        $guess = new backup_nested_element('guess', array('id'), array(
            'userid', 'practice', 'guess', 'weather', 'temperature', 'day',
        ));
        $grades = new backup_nested_element('customgrades');
        $grade = new backup_nested_element('customgrade', array('id'), array(
            'userid', 'groupnnum', 'finalgrade'
        )); 

        // Define Tree
        $icecreamgame->add_child($grades);
        $icecreamgame->add_child($guesses);
        $grades->add_child($grade);
        $guesses->add_child($guess);

        // Define sources
        $icecreamgame->set_source_table('icecreamgame', array('id' => backup::VAR_ACTIVITYID));

        if($userinfo) {
            $grade->set_source_table('icecreamgame_grades', array('icecreamgameid' => backup::VAR_PARENTID));
            $guess->set_source_table('icecreamgame_guesses', array('icecreamgameid' => backup::VAR_PARENTID));
        }
        
        $grade->annotate_ids('user', 'userid');
        $guess->annotate_ids('user', 'userid');
        
        return $this->prepare_activity_structure($icecreamgame);
    }
}