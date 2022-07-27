<?php

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

    }
}