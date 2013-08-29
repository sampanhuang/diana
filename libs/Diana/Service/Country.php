<?php
/**
 * Created by JetBrains PhpStorm.
 * User: sampan
 * Date: 13-7-30
 * Time: 下午9:54
 * To change this template use File | Settings | File Templates.
 */
class Diana_Service_Country extends  Diana_Service_Abstract
{
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 确认洲与国名是否正确及匹配
     *
     * @param string $continent 洲
     * @param string $country 国
     */
    function checkContinentCountry($continent = null,$country = null)
    {
        if ((empty($continent))&&(!empty($country))) {
            return false;
        }
        if (!empty($continent)) {
            $continentsKey = $this->getContinentsKey();
            if (!in_array($continent,$this->getContinentsKey())) {
                return false;
            }
            if (!empty($country)) {
                $countriesKey = $this->getCountriesKey();
                if (empty($countriesKey[$continent][$country])) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 获取洲代码
     *
     * @return array
     */
    function getContinentsKey()
    {
        //return array("as","eu","na","sa","oa","an","af");
        return array("as","eu","na","sa","oa","an");
    }

    /**
     * 获取国家代码
     * @return array|bool
     */
    function getCountriesKey()
    {
        if (!$configCountry = new Zend_Config_Ini(DIANA_DIR_DATA_CONFIG."/country.ini",DIANA_APP_ENV)) {
            return false;
        }
        $countriesKey = $configCountry->toArray();
        return $countriesKey;
    }

}
