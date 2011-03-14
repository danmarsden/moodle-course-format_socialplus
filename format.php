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

$strgroups  = get_string('groups');
$strgroupmy = get_string('groupmy');
$streditsummary  = get_string('editsummary');
$editing    = $PAGE->user_is_editing();

if ($forum = forum_get_course_forum($course->id, 'social')) {

    $cm = get_coursemodule_from_instance('forum', $forum->id);
    $context = get_context_instance(CONTEXT_MODULE, $cm->id);

    /// Print Section 1 with general activities (copied from course/format/topics/format.php)
    ///Using section 1 because section 0 contains the forum for this course format.

    $section = 1;
    if (!empty($sections[$section])) {
        $thissection = $sections[$section];
    } else {
        $thissection = new stdClass;
        $thissection->course  = $course->id;   // Create a new section structure
        $thissection->section = $section;
        $thissection->name    = null;
        $thissection->summary  = '';
        $thissection->summaryformat = FORMAT_HTML;
        $thissection->visible  = 1;
        $thissection->id = $DB->insert_record('course_sections', $thissection);
    }
    unset($sections[0]);

    if ($thissection->summary or (!empty($thissection->sequence)) or $PAGE->user_is_editing()) {

        // Note, no need for a 'left side' cell or DIV.
        // Note, 'right side' is BEFORE content.
        echo '<li id="section-0" class="section main clearfix" >';
        echo '<div class="left side">&nbsp;</div>';
        echo '<div class="right side" >&nbsp;</div>';
        echo '<div class="content">';
        if (!is_null($thissection->name)) {
            echo $OUTPUT->heading($thissection->name, 3, 'sectionname');
        }
        echo '<div class="summary">';

        $coursecontext = get_context_instance(CONTEXT_COURSE, $course->id);
        $summarytext = file_rewrite_pluginfile_urls($thissection->summary, 'pluginfile.php', $coursecontext->id, 'course', 'section', $thissection->id);
        $summaryformatoptions = new stdClass();
        $summaryformatoptions->noclean = true;
        $summaryformatoptions->overflowdiv = true;
        echo format_text($summarytext, $thissection->summaryformat, $summaryformatoptions);

        if ($PAGE->user_is_editing() && has_capability('moodle/course:update', $coursecontext)) {
            echo '<a title="'.$streditsummary.'" '.
                 ' href="editsection.php?id='.$thissection->id.'"><img src="'.$OUTPUT->pix_url('t/edit') . '" '.
                 ' class="icon edit" alt="'.$streditsummary.'" /></a>';
        }
        echo '</div>';

        print_section($course, $thissection, $mods, $modnamesused);

        if ($PAGE->user_is_editing()) {
            print_section_add_menus($course, $section, $modnames);
        }

        echo '</div>';
        echo "</li>\n";
    }

    echo '<div class="subscribelink">', forum_get_subscribe_link($forum, $context), '</div>';
    forum_print_latest_discussions($course, $forum, 10, 'plain', '', false);

} else {
    echo $OUTPUT->notification('Could not find or create a social forum here');
}
