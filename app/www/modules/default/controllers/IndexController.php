<?php
/**
 * 首页
 *
 */
class IndexController extends Www_Controller_Action
{
	function init()
	{
		parent::init();
		
	}
	
	/**
	 * 首页
	 *
	 */
	function indexAction()
	{
        /*
        $this->redirect('/default/website');
        */
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        if(DIANA_TRANSLATE_CURRENT == 'en-us'){
            $domain = DIANA_DOMAIN_WWW_US;
        }elseif(DIANA_TRANSLATE_CURRENT == 'zh-tw'){
            $domain = DIANA_DOMAIN_WWW_TW;
        }else{
            $domain = DIANA_DOMAIN_WWW_CN;
        }
        $contentIndex = file_get_contents('http://'.$domain.'/default/website/index');
        echo $contentIndex.chr(10).chr(13).'<!--form '.$domain.'-->';
	}

    /**
     * 生成站点地图
     */
    function makeSiteMapsAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图

        $serviceWebsite = new Diana_Service_Website();
        //获取全部分类信息
        $serviceWebsiteCategory = new Diana_Service_WebsiteCategory();
        $allWebsiteCategory = $serviceWebsiteCategory->getAll(null,'website');
        $idWebsiteCategory = array();
        foreach($allWebsiteCategory as $rowWebsiteCategory){
            $tmpCategoryId = $rowWebsiteCategory['category_id'];
            $idWebsiteCategory[$tmpCategoryId] = $tmpCategoryId;
        }
        //获取全部地区信息
        $serviceWebsiteArea = new Diana_Service_WebsiteArea();
        $allWebsiteArea = $serviceWebsiteArea->getAll(null,'website');
        $idWebsiteAreaFather = array();
        $idWebsiteAreaSon = array();
        foreach ($allWebsiteArea as $rowWebsiteArea){
            $tmpAreaId = $rowWebsiteArea['area_id'];
            $tmpAreaFatherId = $rowWebsiteArea['area_fatherId'];
            if(empty($tmpAreaFatherId)){
                $idWebsiteAreaFather[$tmpAreaId] = $tmpAreaId;
            }else{
                $idWebsiteAreaSon[$tmpAreaFatherId][$tmpAreaId] = $tmpAreaId;
            }
        }
        //组织输出格式
        $urls = array(
            array(
                'loc' => '/',
                'lastmod' => date("Y-m-d"),
                'changefreq' => 'daily',
                'priority' => 1,
            )
        );
        foreach($idWebsiteAreaFather as $tmpAreaFartherId){
            $urls[] = array(
                'loc' => '/website/list/area_father/'.$tmpAreaFartherId,
                'lastmod' => date("Y-m-d"),
                'changefreq' => 'weekly',
                'priority' => 0.8,
            );
            foreach($idWebsiteAreaSon as $tmpAreaId){
                $urls[] = array(
                    'loc' => '/website/list/area_father/'.$tmpAreaFartherId.'/area/'.$tmpAreaId,
                    'lastmod' => date("Y-m-d"),
                    'changefreq' => 'weekly',
                    'priority' => 0.6,
                );
                foreach($idWebsiteCategory as $tmpCategoryId){
                    $urls[] = array(
                        'loc' => '/website/list/area_father/'.$tmpAreaFartherId.'/area/'.$tmpAreaId.'/category/'.$tmpCategoryId,
                        'lastmod' => date("Y-m-d"),
                        'changefreq' => 'monthly',
                        'priority' => 0.4,
                    );
                }
            }
        }

        $contentXml = '';
        $contentXml .= '<?xml version="1.0" encoding="UTF-8"?>'.chr(10);
        $contentXml .= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.9">'.chr(10);
        foreach($urls as $tmpUrl){
            $contentXml .= '<url>'.chr(10);
            $contentXml .= '<loc>http://www.haihuamen.com'.$tmpUrl['loc'].'</loc>'.chr(10);
            $contentXml .= '<changefreq>'.$tmpUrl['changefreq'].'</changefreq>'.chr(10);
            $contentXml .= '<priority>'.$tmpUrl['priority'].'</priority>'.chr(10);
            $contentXml .= '</url>'.chr(10);
        }
        $contentXml .= '</urlset>'.chr(10);
        $path = DIANA_APP_DIR.'/public/site-maps.xml';
        if(file_put_contents($path,$contentXml)){
            echo $path;
        }

        /*
    <url>
        <loc><?php echo $url;?></loc>
        <lastmod>2014-04-08T16:40:04+00:00</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
         * */
    }
	
	function testAction()
	{
		//$this->getHelper("layout")->disableLayout();//关闭布局
		$this->getHelper("viewRenderer")->setNoRender();//关闭视图
		$url = $this->getRequest()->getParam("url");
		$snKey = "website_register";
		//判断验证码是否正确
		$snKey = "captcha_".$snKey;
		$sessionNamespace = new Zend_Session_Namespace($snKey);
		$sessionWord = $sessionNamespace->word;
		echo $sessionWord;
	}
	
	/**
	 * 频道
	 *
	 */
	function channelAction()
	{
		//$this->getHelper("layout")->disableLayout();//关闭布局
		//$this->getHelper("viewRenderer")->setNoRender();//关闭视图
		$this->_forward("index","website","default") ;
	}

    function captchaAction()
    {
        $this->getHelper("layout")->disableLayout();//关闭布局
        $this->getHelper("viewRenderer")->setNoRender();//关闭视图
        $snKey = $this->getRequest()->getParam("key");
        //实例化验证码类
        $serviceCaptcha = new Diana_Service_Captcha();
        if ($imgCaptcha = $serviceCaptcha->outputCaptcha($snKey)) {
            header( "Content-type: image/jpeg");
            $PSize = filesize($imgCaptcha);
            $picturedata = fread(fopen($imgCaptcha, "r"), $PSize);
            echo $picturedata;
        }else{
            echo $serviceCaptcha->getMsgs();
        }
    }



	
	
	
}