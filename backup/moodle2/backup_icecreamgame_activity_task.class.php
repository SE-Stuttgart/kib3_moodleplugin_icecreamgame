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

require_once($CFG->dirroot.'/mod/icecreamgame/backup/moodle2/backup_icecreamgame_stepslib.php');    // Because it exists (must)

// See https://docs.moodle.org/dev/Backup_API#API_for_activity_modules
class backup_icecreamgame_activity_task extends backup_activity_task {

    protected function define_my_settings() {

    }

    protected function define_my_steps() {
        $this->add_step(new backup_icecreamgame_activity_structure_step('icecreamgame_structure', 'icecreamgame.xml'));
    }

    static public function encode_content_links($content) {
        return $content;
    }
}