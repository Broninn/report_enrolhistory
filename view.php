<?php
// ini_set('error_log', '/tmp/moodle_debug.log');
// error_log('===Acessou até aqui===');
require_once('../../config.php');

$courseid = required_param('courseid', PARAM_INT);
require_login($courseid);
$context = context_course::instance($courseid);
require_capability('moodle/course:viewparticipants', $context);

$PAGE->set_url('/local/enrolhistory/view.php', ['courseid' => $courseid]);
$PAGE->set_context($context);
$PAGE->set_title(get_string('pluginname', 'local_enrolhistory'));
$PAGE->set_heading(get_string('pluginname', 'local_enrolhistory'));

echo $OUTPUT->header();

global $DB;
$records = $DB->get_records_sql("
    SELECT h.*, u.firstname, u.lastname, c.fullname AS coursename, cu.firstname AS changedby_firstname, cu.lastname AS changedby_lastname
    FROM {local_enrolhistory} h
    JOIN {user} u ON h.userid = u.id
    JOIN {course} c ON h.courseid = c.id
    JOIN {user} cu ON h.changedby = cu.id
    WHERE h.courseid = ?
    ORDER BY h.timechanged DESC
", [$courseid]);

if (!$records) {
    echo $OUTPUT->notification(get_string('norecords', 'local_enrolhistory'));
} else {
    $table = new html_table();
    $table->head = ['Aluno', 'Curso', 'Início antigo', 'Fim antigo', 'Alterado por', 'Data da alteração'];

    foreach ($records as $r) {
        $table->data[] = [
            fullname((object) ['firstname' => $r->firstname, 'lastname' => $r->lastname]),
            $r->coursename,
            userdate($r->old_timestart),
            userdate($r->old_timeend),
            fullname((object) ['firstname' => $r->changedby_firstname, 'lastname' => $r->changedby_lastname]),
            userdate($r->timechanged)
        ];
    }

    echo html_writer::table($table);
}

echo $OUTPUT->footer();
