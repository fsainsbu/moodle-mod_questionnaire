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
 * @package moodlecore
 * @subpackage backup-moodle2
 * @copyright 2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Define all the backup steps that will be used by the backup_questionnaire_activity_task
 */

/**
 * Define the complete choice structure for backup, with file and id annotations
 */
class backup_questionnaire_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // To know if we are including userinfo.
        $userinfo = $this->get_setting_value('userinfo');

        // Define each element separated.
        $questionnaire = new backup_nested_element('questionnaire', array('id'), array(
            'course', 'name', 'intro', 'introformat', 'qtype',
            'respondenttype', 'resp_eligible', 'resp_view', 'opendate',
            'closedate', 'resume', 'navigate', 'grade', 'sid', 'timemodified', 'completionsubmit', 'autonum'));

        $surveys = new backup_nested_element('surveys');

        $survey = new backup_nested_element('survey', array('id'), array(
            'name', 'owner', 'realm', 'status', 'title', 'email', 'subtitle',
            'info', 'theme', 'thanks_page', 'thank_head', 'thank_body'));

        $questions = new backup_nested_element('questions');

        $question = new backup_nested_element('question', array('id'), array(
            'survey_id', 'name', 'type_id', 'result_id', 'length', 'precise',
            'position', 'content', 'required', 'deleted', 'dependquestion', 'dependchoice'));

        $questchoices = new backup_nested_element('quest_choices');

        $questchoice = new backup_nested_element('quest_choice', array('id'), array(
            'question_id', 'content', 'value'));

        $attempts = new backup_nested_element('attempts');

        $attempt = new backup_nested_element('attempt', array('id'), array(
            'qid', 'userid', 'rid', 'timemodified'));

        $responses = new backup_nested_element('responses');

        $response = new backup_nested_element('response', array('id'), array(
            'survey_id', 'submitted', 'complete', 'grade', 'username'));

        $responsebools = new backup_nested_element('response_bools');

        $responsebool = new backup_nested_element('response_bool', array('id'), array(
            'response_id', 'question_id', 'choice_id'));

        $responsedates = new backup_nested_element('response_dates');

        $responsedate = new backup_nested_element('response_date', array('id'), array(
            'response_id', 'question_id', 'response'));

        $responsemultiples = new backup_nested_element('response_multiples');

        $responsemultiple = new backup_nested_element('response_multiple', array('id'), array(
            'response_id', 'question_id', 'choice_id'));

        $responseothers = new backup_nested_element('response_others');

        $responseother = new backup_nested_element('response_other', array('id'), array(
            'response_id', 'question_id', 'choice_id', 'response'));

        $responseranks = new backup_nested_element('response_ranks');

        $responserank = new backup_nested_element('response_rank', array('id'), array(
            'response_id', 'question_id', 'choice_id', 'rank'));

        $responsesingles = new backup_nested_element('response_singles');

        $responsesingle = new backup_nested_element('response_single', array('id'), array(
            'response_id', 'question_id', 'choice_id'));

        $responsetexts = new backup_nested_element('response_texts');

        $responsetext = new backup_nested_element('response_text', array('id'), array(
            'response_id', 'question_id', 'response'));

        // Build the tree.
        $questionnaire->add_child($surveys);
        $surveys->add_child($survey);

        $survey->add_child($questions);
        $questions->add_child($question);

        $question->add_child($questchoices);
        $questchoices->add_child($questchoice);

        $questionnaire->add_child($attempts);
        $attempts->add_child($attempt);

        $attempt->add_child($responses);
        $responses->add_child($response);

        $response->add_child($responsebools);
        $responsebools->add_child($responsebool);

        $response->add_child($responsedates);
        $responsedates->add_child($responsedate);

        $response->add_child($responsemultiples);
        $responsemultiples->add_child($responsemultiple);

        $response->add_child($responseothers);
        $responseothers->add_child($responseother);

        $response->add_child($responseranks);
        $responseranks->add_child($responserank);

        $response->add_child($responsesingles);
        $responsesingles->add_child($responsesingle);

        $response->add_child($responsetexts);
        $responsetexts->add_child($responsetext);

        // Define sources.
        $questionnaire->set_source_table('questionnaire', array('id' => backup::VAR_ACTIVITYID));

        $survey->set_source_table('questionnaire_survey', array('id' => '../../sid'));

        $question->set_source_table('questionnaire_question', array('survey_id' => backup::VAR_PARENTID));

        $questchoice->set_source_table('questionnaire_quest_choice', array('question_id' => backup::VAR_PARENTID));

        // All the rest of elements only happen if we are including user info.
        if ($userinfo) {
            $attempt->set_source_table('questionnaire_attempts', array('qid' => backup::VAR_PARENTID));
            $response->set_source_table('questionnaire_response', array('id' => '../../rid'));
            $responsebool->set_source_table('questionnaire_response_bool', array('response_id' => backup::VAR_PARENTID));
            $responsedate->set_source_table('questionnaire_response_date', array('response_id' => backup::VAR_PARENTID));
            $responsemultiple->set_source_table('questionnaire_resp_multiple', array('response_id' => backup::VAR_PARENTID));
            $responseother->set_source_table('questionnaire_response_other', array('response_id' => backup::VAR_PARENTID));
            $responserank->set_source_table('questionnaire_response_rank', array('response_id' => backup::VAR_PARENTID));
            $responsesingle->set_source_table('questionnaire_resp_single', array('response_id' => backup::VAR_PARENTID));
            $responsetext->set_source_table('questionnaire_response_text', array('response_id' => backup::VAR_PARENTID));
        }

        // Define id annotations.
        $attempt->annotate_ids('user', 'userid');

        // Define file annotations
        $questionnaire->annotate_files('mod_questionnaire', 'intro', null); // This file area hasn't itemid.

        $survey->annotate_files('mod_questionnaire', 'info', 'id'); // By survey->id
        $survey->annotate_files('mod_questionnaire', 'thankbody', 'id'); // By survey->id.

        $question->annotate_files('mod_questionnaire', 'question', 'id'); // By question->id.

        // Return the root element, wrapped into standard activity structure.
        return $this->prepare_activity_structure($questionnaire);
    }
}
