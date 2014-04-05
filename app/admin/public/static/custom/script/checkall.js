/**
 * 全选及全不选
 *
 */
function checkAll(checkbox)
{
    var checkboxPre = checkbox.id + '_'
    var checkboxPreStrlen = checkboxPre.length;
    var objs = $('input');
    for(var i=0; i<objs.length; i++) {
        if(objs[i].type.toLowerCase() == "checkbox" ){
            if(objs[i].id.toLowerCase().substring(0,checkboxPreStrlen) == checkboxPre){
                if(checkbox.checked == true){
                    objs[i].checked = true;
                }else{
                    objs[i].checked = false;
                }
            }
        }
    }
}
