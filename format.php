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
 * social format with full section at top.
 *
 * @package   format_socialplus
 * @author    Dan Marsden <dan@danmarsden.com>
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$streditsummary  = get_string('editsummary');

if ($forum = forum_get_course_forum($course->id, 'social')) {

    $cm = get_coursemodule_from_instance('forum', $forum->id);
    $context = context_module::instance($cm->id);

    $course = course_get_format($course)->get_course();
    $course->numsections = 1;
    course_create_sections_if_missing($course, range(0, 2));

    $renderer = $PAGE->get_renderer('format_socialplus');

    $renderer->print_section_page($course, null, null, null, null, 1);

    echo '<div class="subscribelink">', forum_get_subscribe_link($forum, $context), '</div>';
    forum_print_latest_discussions($course, $forum, 10, 'plain', '', false);

} else {
    echo $OUTPUT->notification('Could not find or create a social forum here');
}
// Include course format js module
$PAGE->requires->js('/course/format/topics/format.js');