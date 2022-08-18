<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/icecreamgame/backup/moodle2/restore_icecreamgame_stepslib.php'); // Because it exists (must)

class restore_icecreamgame_activity_task extends restore_activity_task {

    protected function define_my_settings() {

    }

    protected function define_my_steps() {
        // Choice only has one structure step
        $this->add_step(new restore_icecreamgame_activity_structure_step('icecreamgame_structure', 'icecreamgame.xml'));
    }

    static public function define_decode_contents() {
        $contents = array();
        return $contents;
    }

    static public function define_decode_rules() {
        $rules = array();
        return $rules;
    
    }
    public static function define_restore_log_rules() {
        $rules = array();
        return $rules;
    }

    public static function define_restore_log_rules_for_course() {
        $rules = array();
        return $rules;
    }
}