<?php
namespace local_enrolhistory\event;
// ini_set('error_log', '/tmp/moodle_debug.log');
// error_log('=== observer.php carregado ===');

defined('MOODLE_INTERNAL') || die();

class observer {
    public static function user_enrolment_updated(\core\event\user_enrolment_updated $event) {
        global $DB, $USER;

        // error_log('=== observer chamado ===');
        // error_log('Evento: ' . json_encode($event->get_data()));

        // Estado anterior da inscrição
        $oldrecord = $event->get_record_snapshot('user_enrolments', $event->objectid);

        if (!$oldrecord) {
            // error_log('=== record_snapshot não retornou ===');
            return;
        }

        $record = (object)[
            'userid' => $oldrecord->userid,
            'courseid' => $event->courseid,
            'old_timestart' => $oldrecord->timestart,
            'old_timeend' => $oldrecord->timeend,
            'changedby' => $USER->id,
            'timechanged' => time()
        ];

        // error_log('=== inserindo registro: ' . json_encode($record));
        $DB->insert_record('local_enrolhistory', $record);
    }
}
