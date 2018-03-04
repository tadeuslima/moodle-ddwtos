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
 * Drag-and-drop words into sentences question definition class.
 *
 * @package   qtype_ddwtos
 * @copyright 2009 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/gapselect/questionbase.php');


/**
 * Represents a drag-and-drop words into sentences question.
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_ddwtos_question extends qtype_gapselect_question_base {

    public function summarise_choice($choice) {
        return $this->html_to_text($choice->text, FORMAT_HTML);
    }

    /* 
    Função para calcular os acertos do usuário independente da ordem das respostas.
    Sobrescreve a função presente na classe QTYPE_GAPSELECT_QUESTION_BASE
    */
    public function get_num_parts_right(array $response)
    {
        if ($this->is_ordered($this->id))
        {
            $numright = 0;
            foreach ($this->places as $place => $notused)
            {
                if (!array_key_exists($this->field($place), $response)) 
                {
                    continue;
                }
                if ($response[$this->field($place)] == $this->get_right_choice_for($place))
                {
                    $numright += 1;
                }
            }
        }
        else
        {
            $qra = array(); //array que guarda as respostas corretas da questão (não necessariamente deordenadas)
            foreach ($this->places as $place => $notused) 
            {
                array_push($qra, $this->get_right_choice_for($place));
            }
    
            $numright = 0;
            foreach($response as $r => $value)
            {
                if(in_array($value, $qra))
                {
                    $numright += 1;
                }
            }
            // print_r($this->is_ordered($this->id));
        }
        return array($numright, count($this->places));
    }

    public function is_ordered($id)
    {
        global $DB;
        $result = $DB->get_record('question_ddwtos', array('id' => $id))->ordered;
        // print_r($this->id);
        return $result;
    }
}


/**
 * Represents one of the choices (draggable boxes).
 *
 * @copyright  2009 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_ddwtos_choice {
    /** @var string Text for the choice */
    public $text;

    /** @var int Group of the choice */
    public $draggroup;

    /** @var bool If the choice can be used an unlimited number of times */
    public $infinite;

    /**
     * Initialize a choice object.
     *
     * @param string $text The text of the choice
     * @param int $draggroup Group of the drop choice
     * @param bool $infinite True if the item can be used an unlimited number of times
     */
    public function __construct($text, $draggroup = 1, $infinite = false) {
        $this->text = $text;
        $this->draggroup = $draggroup;
        $this->infinite = $infinite;
    }

    /**
     * Returns the group of this item.
     *
     * @return int
     */
    public function choice_group() {
        return $this->draggroup;
    }
}
