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
 * Prints an instance of mod_icecreamgame.
 *
 * @package     mod_icecreamgame
 * @copyright   2022 Universtity of Stuttgart <dirk.vaeth@ims.uni-stuttgart.de>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/../../lib/externallib.php');

// Course module id.
$id = optional_param('id', 0, PARAM_INT);

// Activity instance id.
$i = optional_param('i', 0, PARAM_INT);

if ($id) {
    $cm = get_coursemodule_from_id('icecreamgame', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('icecreamgame', array('id' => $cm->instance), '*', MUST_EXIST);
} else {
    $moduleinstance = $DB->get_record('icecreamgame', array('id' => $i), '*', MUST_EXIST);
    $course = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm = get_coursemodule_from_instance('icecreamgame', $moduleinstance->id, $course->id, false, MUST_EXIST);
}

require_login($course, true, $cm);

$modulecontext = context_module::instance($cm->id);

// TODO add event back
// $event = \mod_icecreamgame\event\course_module_viewed::create(array(
//     'objectid' => $moduleinstance->id,
//     'context' => $modulecontext
// ));
// $event->add_record_snapshot('course', $course);
// $event->add_record_snapshot('icecreamgame', $moduleinstance);
// $event->trigger();

$PAGE->set_url('/mod/icecreamgame/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);

# obtain user token for webservice
$service = $DB->get_record("external_services", array('shortname' => 'moodleservice'), '*', MUST_EXIST);
$user_token = external_generate_token_for_current_user($service);


echo $OUTPUT->header();


// echo '<iframe src="/mod/icecreamgame/dist/index.html" width="100%" height="100%" style="border: none;"/>';
$external = <<< 'EOD'
<meta name="theme-color" content="#000000" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<script type="module">
//assets/index.3eb5738c.js
</script>
    <style type="text/css">
._container_8hyjl_1{display:flex;flex-flow:column nowrap;gap:2rem}h1{text-align:center}h4{margin:0}input::-webkit-outer-spin-button,input::-webkit-inner-spin-button{-webkit-appearance:none!important;margin:0!important}input[type=number]{-moz-appearance:textfield!important}body{margin:5rem;font-family:-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Oxygen,Ubuntu,Cantarell,Fira Sans,Droid Sans,Helvetica Neue,sans-serif;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}

</style>
<noscript>You need to enable JavaScript to run this app.</noscript>
<div id="root"></div>
EOD;
echo str_replace(array("_userid_", "_courseid_", "_icecreamgameid_", "_token_"), array($USER->id, $PAGE->cm->course, $PAGE->cm->instance, $user_token->token), $external);

echo $OUTPUT->footer();