<?php


class NotreDameHappinessModule extends KGOModule {

    /*
     *  The initializeForPageConfigObjects_ methods below don't need to do much, they simply check if a feed has been configured
     *  The $objects configured in the page objdefs will take control from here
     */

    private $submittedData;
    private $shortNames;
    private $descriptions = NULL;

    protected function initializeForPageConfigObjects_index(KGOUIPage $page, $objects) {
        if (!($feed = $this->getFeed())) {
            return;
        }

        $page->appendToRegionContents('content', $this->getForm('voteUp'));
        $page->appendToRegionContents('content', $this->getForm('voteDown'));
    }

    protected function initializeForPage_vote(KGOUIPage $page) {
        if (!($feed = $this->getFeed())) {
            return;
        }
        
        $page->appendToRegionContents('content', $this->getForm('voteUp'));
        $page->appendToRegionContents('content', $this->getForm('voteDown'));

       
    }
    protected function initializeForm_voteUp() {
        $objdef = $this->getConfig('page-voteUp');
        $controller = KGOFormController::factory($objdef, $this);
        $controller->setAllowEmpty(true);
        return $controller;
    }
    protected function initializeForm_voteDown() {
        $objdef = $this->getConfig('page-voteDown');
        $controller = KGOFormController::factory($objdef, $this);
        $controller->setAllowEmpty(true);
        return $controller;
    }

    protected function processForm_voteUp($form) {
        if($form->isValid()) {
            $submittedValues = $form->getSubmittedValues();

            $url = "https://whispering-atoll-2315.herokuapp.com/happiness/";
            $data = $submittedValues;

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $result = json_decode($response, true);
        }
    }

    protected function processForm_voteDown($form) {
        if($form->isValid()) {
            $submittedValues = $form->getSubmittedValues();

            kgo_debug($submittedValues, true, true);

            $url = "https://whispering-atoll-2315.herokuapp.com/happiness/";
            $data = $submittedValues;

            // use key 'http' even if you send the request to https://...
            $options = array(
                'http' => array(
                    'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method'  => 'POST',
                    'content' => http_build_query($data),
                ),
            );
            $context  = stream_context_create($options);
            $response = file_get_contents($url, false, $context);
            $result = json_decode($response, true);
        }
    }

    public function getHappinessPercent() {
        $feeds = $this->getAllFeeds();
        $feed = $feeds["happiness"];
        $ret = $feed->getRetriever();
        $data = $ret->getData();
        return $data["happiness"];
    }
}