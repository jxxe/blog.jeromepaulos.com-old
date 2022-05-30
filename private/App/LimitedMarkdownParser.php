<?php

namespace Blog\App;

use Parsedown;

class LimitedMarkdownParser extends Parsedown {

    public function __construct() {
        $this->setSafeMode(true);
        $this->BlockTypes = [];
        unset($this->InlineTypes['!']);
        $this->inlineMarkerList = str_replace('!', '', $this->inlineMarkerList);
    }

}