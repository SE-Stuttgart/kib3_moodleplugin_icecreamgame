<?php

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
