<?php

defined('MOODLE_INTERNAL') || die();

function local_enrolhistory_extend_navigation_course($navigation, $course, $context) {
    if (has_capability('moodle/course:viewparticipants', $context)) {
        $url = new moodle_url('/local/enrolhistory/view.php', ['courseid' => $course->id]);
        $node = navigation_node::create(
            get_string('pluginname', 'local_enrolhistory'),
            $url,
            navigation_node::TYPE_CUSTOM,
            null,
            'localenrolhistory',
            new pix_icon('i/report', '') 
        );
        $navigation->add_node($node);
    }
}
