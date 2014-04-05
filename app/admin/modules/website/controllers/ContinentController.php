<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-8-4
 * Time: 上午11:50
 * To change this template use File | Settings | File Templates.
 */
class Website_ContinentController extends Admin_Controller_Action
{
    function init()
    {
        parent::init();

    }

    function indexAction()
    {
        $serviceWebsiteCountry = new Diana_Service_WebsiteCountry();
        if($continents = $serviceWebsiteCountry->getContinents()){
            $this->view->continents = $continents;
        }else{
            $this->setMsgs($serviceWebsiteCountry->getMsgs());
        }
    }

    function countryAction()
    {
        $this->view->continentKey = $continentKey = $this->getRequest()->getParam('continent_key','as');
        $serviceWebsiteCountry = new Diana_Service_WebsiteCountry();
        if($continents = $serviceWebsiteCountry->getContinents()){
            $this->view->continents = $continents;
            if($countries = $serviceWebsiteCountry->getCountriesByContinent($continentKey)){
                $this->view->countries = $countries;
            }else{
                $this->setMsgs($serviceWebsiteCountry->getMsgs());
            }
        }else{
            $this->setMsgs($serviceWebsiteCountry->getMsgs());
        }


    }

}