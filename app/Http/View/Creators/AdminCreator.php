<?php

namespace App\Http\View\Creators;

use Illuminate\View\View;
use OpenCore\Support\Opencart\Startup;
use Illuminate\Support\Facades\View as ViewFacade;

class AdminCreator
{
    private $inlineContent = null;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function create(View $view)
    {
        $document = Startup::getRegistry('document');

        $sections = ViewFacade::getSections();
        //TODO: temporary solution -> should be moved to app.blade.php
        $document->addStyle(HTTPS_CATALOG . 'core/css/admin.css', 'stylesheet', 'screen');

        foreach($sections as $section => $content) {
            switch($section) {
                case 'meta.title':
                    $document->setTitle($content);
                break;
                case 'meta.description':
                    $document->getDescription($content);
                break;
                case 'meta.keywords':
                    $document->setKeywords($content);
                break;
                case 'styles':
                    if(preg_match_all('/"([^"]+?\.css)"/', $content, $matches))
                    {
                        foreach($matches[1] as $styleLink) {
                            $document->addStyle($styleLink, 'stylesheet', 'screen');
                        }
                    }
                break;
                case 'scripts':
                    if(preg_match_all('/"([^"]+?\.js)"/', $content, $matches))
                    {
                        foreach($matches[1] as $scriptLink) {
                            $document->addScript($scriptLink);
                        }
                    }
                break;
                case 'inline.styles':
                case 'inline.scripts':
                    $this->inlineContent[] = $content;
                break;
            }
        }

        //set default meta title in case the title is missing
        if(!isset($sections['meta.title'])) {
            $document->setTitle(config('app.name'));
        }

        $loader = Startup::getRegistry('load');

        $header = $loader->controller('common/header');
        if(!is_null($this->inlineContent)) {
            $headContent = implode("\n", $this->inlineContent);
            $header = str_replace('</head>', $headContent . '</head>', $header);
        }

        //Add CSRF Token
        $csrf = "<!-- CSRF Token -->
        <meta name=\"csrf-token\" content=\"" . csrf_token() ."\">";
        $header = str_replace('</title>', '</title>' . $csrf, $header);

        $column_left = $loader->controller('common/column_left');
        $footer = $loader->controller('common/footer');

        $view->with('opencart_header', $header);
        $view->with('opencart_column_left', $column_left);
        $view->with('opencart_footer', $footer);
    }
}
