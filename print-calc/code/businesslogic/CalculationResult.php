<?php

namespace PC;

/**
 * CalculationResult
 * Holds the result of any calculation step
 */
class CalculationResult {
    private $value = 0;
    private $comments = "";

    public function setValue($value) {
        $this->value = $value;
    }

    public function getValue() {
        return $this->value;
    }

    public function setComments($comments) {
        $this->comments = $comments;
    }


    public function getComments() {
        return $this->comments;
    }

    /**
     * Adds expression to value, adds comments and return expression result
     * @param mixed $expression
     * @param string $comments
     * @return string
     */
    public function calcAndLog($expression, $comments) {
        $this->value += $expression;
        return $this->log($expression, $comments);
    }

    /**
     * Return expression result, adds comments, but does not affect the value
     * @param mixed $expression
     * @param string $comments
     * @return string
     */
    public function log($expression, $comments) {
        $this->comments .= $comments . strval($expression) . "\n";
        return $expression;
    }

    /**
     * adds value to value and comments to comments.
     * @param CalculationResult $mergeItem
     */
    public function merge(CalculationResult $mergeItem) {
        $this->value += $mergeItem->getValue();
        $this->comments .= $mergeItem->getComments();
    }

}


?>
