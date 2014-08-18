/**
 * 格式化时间
 * @param time 时间戳
 * @return {String}
 */
function formatTimeStamp(time)
{
    if (time > 0){
        return date('Y-m-d H:i:s',time)
    } else {
        return '';
    }
}
/**
 * 布尔值判断
 * @param value
 * @return {String}
 */
function formatBool(value)
{
    if (value == 1){
        return '√';
    } else {
        return '×';
    }
}