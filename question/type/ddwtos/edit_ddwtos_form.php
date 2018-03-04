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
 * Defines the editing form for the drag-and-drop words into sentences question type.
 *
 * @package   qtype_ddwtos
 * @copyright 2009 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/gapselect/edit_form_base.php');


/**
 * Drag-and-drop words into sentences editing form definition.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_ddwtos_edit_form extends qtype_gapselect_edit_form_base {
    public function qtype() {
        return 'ddwtos';
    }

    protected function data_preprocessing_choice($question, $answer, $key) {
        $question = parent::data_preprocessing_choice($question, $answer, $key);
        $options = unserialize($answer->feedback);
        $question->choices[$key]['choicegroup'] = $options->draggroup;
        $question->choices[$key]['infinite'] = $options->infinite;
        return $question;
    }

    protected function choice_group($mform) {
        $grouparray = parent::choice_group($mform);
        $grouparray[] = $mform->createElement('checkbox', 'infinite', ' ',
                get_string('infinite', 'qtype_ddwtos'), null,
                array('size' => 1, 'class' => 'tweakcss'));
        return $grouparray;
    }

    //DEMANDA
    /**
     * Defines form elements for answer choices.
     *
     * @param object $mform The Moodle form object being built
     */
    protected function definition_answer_choice(&$mform) {

        $mform->addElement('header', 'choicehdr', get_string('choices', 'qtype_gapselect'));
        $mform->setExpanded('choicehdr', 1);

        $mform->addElement('checkbox', 'ordered', get_string('ordered', 'qtype_ddwtos'));
        $mform->setDefault('ordered', 0);
        $mform->addHelpButton('ordered', 'ordered', 'qtype_ddwtos');

        $mform->addElement('checkbox', 'shuffleanswers', get_string('shuffle', 'qtype_gapselect'));
        $mform->setDefault('shuffleanswers', 0);

        $textboxgroup = array();
        $textboxgroup[] = $mform->createElement('group', 'choices',
                get_string('choicex', 'qtype_gapselect'), $this->choice_group($mform));

        if (isset($this->question->options)) {
            $countanswers = count($this->question->options->answers);
        } else {
            $countanswers = 0;
        }

        if ($this->question->formoptions->repeatelements) {
            $defaultstartnumbers = QUESTION_NUMANS_START * 2;
            $repeatsatstart = max($defaultstartnumbers, QUESTION_NUMANS_START,
                    $countanswers + QUESTION_NUMANS_ADD);
        } else {
            $repeatsatstart = $countanswers;
        }

        $repeatedoptions = $this->repeated_options();
        $mform->setType('answer', PARAM_RAW);
        $this->repeat_elements($textboxgroup, $repeatsatstart, $repeatedoptions,
                'noanswers', 'addanswers', QUESTION_NUMANS_ADD,
                get_string('addmorechoiceblanks', 'qtype_gapselect'), true);
    }

    public function set_data($question) {
        global $DB;
        question_bank::get_qtype($question->qtype)->set_default_options($question);

        $question->ordered = $DB->get_record('question_ddwtos', array('questionid' => $question->id))->ordered;

        // Subclass adds data_preprocessing code here.
        $question = $this->data_preprocessing($question);

        parent::set_data($question);
    }
    //FIM DEMANDA
}
