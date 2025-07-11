<?php
defined('MOODLE_INTERNAL') || die();

$observers = [
    [
        'eventname' => '\\core\\event\\user_enrolment_updated',
        'callback'  => '\\local_enrolhistory\\event\\observer::user_enrolment_updated',
        'priority'  => 9999,
        'internal'  => false,
    ],
];
