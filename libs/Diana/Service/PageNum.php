<?php
    /**
     * 分页类
     *
     */
class Diana_Service_PageNum
{
    var $count;
    var $size;
    var $pageCurrent;
    var $pageStep = 1;
    var $pageSpace = 5;
    var $pageCount;
    function __construct($count,$size = 20,$pageCurrent = 1,$pageStep = 1,$pageSpace = 5)
    {
        $this->setConf($count,$size,$pageCurrent,$pageStep,$pageSpace);
    }

    /**
     * 参数设置
     * @param $count 总纪录数
     * @param $size 每页纪录数
     * @param $pageCurrent 当前页
     * @param int $pageStep 下一页和下一页隔几页
     * @param int $pageSpace 间隔
     */
    function setConf($count,$size = 20,$pageCurrent = 1,$pageStep = 1,$pageSpace = 5)
    {
        if(empty($count)){
            return false;
        }
        $this->count = $count;
        $this->size = $size;
        $this->pageCurrent = $pageCurrent;
        $this->pageStep = $pageStep;
        $this->pageSpace = $pageSpace;
        $this->pageCount = ceil($count/$size);
    }

    function getPageNum()
    {
        if(empty($this->count)){
            return false;
        }
        $pageNum = array(
            'first' => 1,
            'last' => $this->pageCount,
            'current' => $this->pageCurrent,
            'prev' => $this->getPagePrev(),
            'next' => $this->getPageNext(),
            'space' => array(
                'start' => $this->getPageStart(),
                'end' => $this->getPageEnd(),
            ),
        );
        return $pageNum;
    }

    /**
     * @return int 上一页
     */
    function getPagePrev()
    {
        $pagePrev = $this->pageCurrent - $this->pageStep;
        if($pagePrev < 1){
            $pagePrev = 1;
        }
        return $pagePrev;
    }

    /**
     * @return int 下一页
     */
    function getPageNext()
    {
        $pageNext = $this->pageCurrent + $this->pageStep;
        if($pageNext > $this->pageCount){
            $pageNext = $this->pageCount;
        }
        return $pageNext;
    }

    function getPageStart()
    {
        $pageStart = $this->pageCurrent - $this->pageSpace;
        if($pageStart < 1){
            $pageStart = 1;
        }
        return $pageStart;
    }

    /**
     * @param $count
     * @param $size
     * @return mixed
     */
    function getPageEnd()
    {
        $pageEnd = $this->pageCurrent + $this->pageSpace;
        if($pageEnd > $this->pageCount){
            $pageEnd = $this->pageCount;
        }
        return $pageEnd;
    }
}