<?php
/**
 * 将一个整形数据转换成IP
 *
 * @param int $ip
 * @return string 返回对应的IP
 */
function smarty_modifier_long2ip($ip)
{
    return long2ip($ip);
}
?>