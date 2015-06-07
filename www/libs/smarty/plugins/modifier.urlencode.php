<?php
/**
 * urlencode字符串
 *
 * @param int $string
 * @return string 返回urlencode后的字符串
 */
function smarty_modifier_urlencode($string)
{
    return urlencode($string);
}
?>