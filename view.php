<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
/**
 * View page for the Video Player activity.
 *
 * @package    mod_videoplayer
 * @copyright  2025 Jose Erasmo Moreno Salgado - Elearning Cloud  <jose@elearningcloud.org>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Resto de tu código...

require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/locallib.php');

$id = required_param('id', PARAM_INT); // Course Module ID
$cm = get_coursemodule_from_id('videoplayer', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
$videoplayer = $DB->get_record('videoplayer', array('id' => $cm->instance), '*', MUST_EXIST);

require_login($course, true, $cm);

$context = context_module::instance($cm->id);
$PAGE->set_url('/mod/videoplayer/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($videoplayer->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

echo $OUTPUT->header();
// Título eliminado para que no se repita dos veces
// echo $OUTPUT->heading($videoplayer->name);

// Extraer ID de Google Drive
if (preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $videoplayer->video_url, $matches)) {
    $drive_id = $matches[1];
    echo '<div style="max-width: 800px; margin: auto">';
    echo '<iframe 
    src="https://drive.google.com/file/d/' . $drive_id . '/preview" 
    width="100%" 
    height="480" 
    allow="autoplay; fullscreen" 
    allowfullscreen
    sandbox="allow-scripts allow-same-origin"
    style="border:none;">
</iframe>';
    echo '</div>';
} else {
    echo html_writer::div(get_string('invalidurl', 'videoplayer'), 'alert alert-danger');
}

// Mostrar descripción
echo $OUTPUT->box(format_module_intro('videoplayer', $videoplayer, $cm->id), 'generalbox mod_introbox', 'videoplayerintro');

echo $OUTPUT->footer();
