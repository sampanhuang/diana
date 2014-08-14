/**
 * 禁用页面
 */
function forbiddenPage(){
    $("<div class=\"datagrid-mask\" style=\"background:#666666;\"></div>").css({display:"block",width:$("body")[0].offsetWidth+10,height:$(window).height()}).appendTo("body");
    $("<div class=\"datagrid-mask-msg\"></div>").html("正在处理，请稍候……").appendTo("body").css({display:"block",left:($(document.body).outerWidth(true) - 190) / 2,top:($(window).height() - 45) / 2});
}
/**
 * 释放页面
 * @return
 */
function releasePage(){
    $(".datagrid-mask,.datagrid-mask-msg").hide();
}
/**
 * 增加一个选项卡
 * @param subtitle 标题
 * @param url URL
 */
function addTab(subtitle,url,refresh){
    if(!$('#tabs').tabs('exists',subtitle)){
        $('#tabs').tabs('add',{
            style:{overflow:'hidden'},
            title:subtitle,
            content:createFrame(url),
            closable:true
        });
    }else{
        if(refresh){
            var currTab = parent.$('#tabs').tabs('getTab', subtitle);
            var iframe = parent.$(currTab.panel('options').content);
            var src = iframe.attr('src');
            if(src){
                $('#tabs').tabs('update', { tab: currTab, options: { content: createFrame(src)} });
            }
        }
        $('#tabs').tabs('select',subtitle);
    }
}
/**
 * 给父增加一个选项卡
 * @param subtitle 标题
 * @param url URL
 */
function addTabWithParent(subtitle,url,refresh){
    if(!parent.$('#tabs').tabs('exists',subtitle)){
        parent.$('#tabs').tabs('add',{
            style:{overflow:'hidden'},
            title:subtitle,
            content:createFrame(url),
            closable:true
        });
    }else{
        if(refresh){
            var currTab = parent.$('#tabs').tabs('getTab', subtitle);
            var iframe = parent.$(currTab.panel('options').content);
            parent.$('#tabs').tabs('update', { tab: currTab, options: { content: createFrame(url)} });
        }
        parent.$('#tabs').tabs('select',subtitle);
    }
}
/**
 * 创建一个框架
 * @param url
 * @returns {string}
 */
function createFrame(url)
{
    var s = '<iframe scrolling="auto" frameborder="0"  src="'+url+'" style="width:100%;height:99%;"></iframe>';
    return s;
}
/**
 * 全选或是全部取消
 * @param object obj 对象
 * @param gridId string 数据网格ID
 */
function gridSelectAll(obj,gridId)
{
    buttonText = $(obj).linkbutton('options')['text'];
    if(buttonText == '不选'){
        $('#' + gridId).datagrid('clearChecked');
        $(obj).linkbutton({text:'全选'});
    }else{
        $('#' + gridId).datagrid('selectAll');
        $(obj).linkbutton({text:'不选'});
    }
}