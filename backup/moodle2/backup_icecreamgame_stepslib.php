<?php

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