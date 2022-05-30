<?php

namespace Blog\App;

use Exception;
use Highlight\Highlighter;
use ParsedownExtra;

class CustomMarkdownParser extends ParsedownExtra {

    protected function blockFencedCodeComplete($Block): array {
        $Block = parent::blockFencedCodeComplete($Block);
        if($this->safeMode) return $Block;

        if(empty($Block['element']['text']['attributes']['class'])) return $Block;
        $lang = str_replace('language-', '', $Block['element']['text']['attributes']['class']);
        unset($Block['element']['text']['attributes']['class']);

        $highlighter = new Highlighter();
        $highlighter->setClassPrefix('token-');

        try {
            $highlighted = $highlighter->highlight($lang, $Block['element']['text']['text'])->value;
        } catch(Exception) {
            return $Block;
        }

        $Block['element']['text']['rawHtml'] = $highlighted;
        unset($Block['element']['text']['text']);

        return $Block;
    }

    protected function blockHeader($Line) {
        $Block = parent::blockHeader($Line);

        if(!isset($Block['element']['attributes']['id'])) {
            $Block['element']['attributes']['id'] = slugify($Block['element']['text']);
        }

        $Block = [
            'char' => '#',
            'element' => [
                'name' => $Block['element']['name'],
                'handler' => 'element',
                'attributes' => [
                    'id' => $Block['element']['attributes']['id']
                ],
                'text' => [
                    'name' => 'a',
                    'text' => $Block['element']['text'],
                    'attributes' => [
                        'href' => '#' . $Block['element']['attributes']['id']
                    ]
                ]
            ]
        ];

        return $Block;
    }

    protected function blockFencedCode($Line) {
        $Block = parent::blockFencedCode($Line);

        // print '<pre>'; print_r($Block); exit;

        return $Block;
    }


}