<?php

use Liip\RMT\Information\InformationRequest;
use Liip\RMT\Context;

class UpdateCSSInclusion extends \Liip\RMT\Action\BaseAction
{
    // This will generate a user question (and a command line parameter)
    public function getInformationRequests()
    {
        return array(
            new InformationRequest('css-update', array(
                'description' => 'if the CSS has been modified?',
                'type' => 'yes-no',
                'default' => 'no'
            ))
        );
    }

    // Execute the action
    public function execute()
    {
        // Cancel if the user say no
        if (Context::get('information-collector')->getValueFor('css-update') !== 'y'){
            Context::get('output')->writeln('<error>no</error>');
            return;
        }

        // Inject the version number into the index.html
        $version = Context::get('version-persister')->getTagFromVersion(Context::getInstance()->getParam('new-version'));
        Context::get('output')->write("New version id [<yellow>$version</yellow>] udpated in index.html");
        $this->updateHtml($version);
        $this->confirmSuccess();
    }


    protected function updateHtml($version){
        $file = __DIR__.'/index.html';
        $html = file_get_contents($file);
        $html = preg_replace('/css\/styles\.css\?v=[^"]+/', "css/styles.css?v=$version", $html);
        file_put_contents($file, $html);
    }
}