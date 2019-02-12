<?php
namespace Framework\Modules\UI;


abstract class Action {

    /**
     * Converts object to array
     * Must be redifined
     *
     * @throws \Exception
     */
    abstract public function toArray();

}

?>