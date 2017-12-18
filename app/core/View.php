<?php

class View {

    /**
     * View file
     *
     * @var string
     */
    private $file;

    /**
     * The data of the view
     *
     * @var array
     */
    private $data;

    /**
     * Initialize a new View
     *
     * @param $file
     */
    public function __construct($file, $data = null) {
        $this->file = $file;
        $this->data = $data;
    }

    /**
     * Return the parsed view
     *
     * @return string
     */
    public function __toString() {
        return $this->parseView();
    }

    /**
     * Parse the view into a string
     *
     * @return string
     */
    public function parseView() {
        if (!empty($this->data)) {
            extract($this->data);
        }

        ob_start();
        include (INC_ROOT . '/app/views/' . $this->file . '.php');
        return ob_get_clean();
    }

}
