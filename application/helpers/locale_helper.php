<?php

/*
 * Currency locale
 */

function get_type_sale($type){
    if($type==1){
        $name = "Bán hàng";
    }else if($type == 2){
        $name = "Trả hàng";
    }else if($type == 3){
        $name = "Hàng hủy";
    }else if($type == 4){
        $name = "Sổ thu của nhà cung cấp";
    }else if($type == 5){
        $name = "Sổ thu của khách hàng";
    }else if($type == 6){
        $name = "Sổ thu khác";
    }else if($type == 55){
        $name = "Sổ chi khách hàng";
    }else{
        $name = "Khác";
    }
    return $name;
}

function get_type_receiving($type){
    if($type==1){
        $name = "Nhập hàng";
    }else if($type == 2){
        $name = "Nhập bao bì";
    }else if($type == 3){
        $name = "Tái chế";
    }else if($type == 4){
        $name = "Sổ chi nhà cung cấp";
    }else if($type == 5){
        $name = "Sổ chi khách hàng";
    }else if($type == 6){
        $name = "Sổ chi khác";
    }else{
        $name = "Trả lại nhà cung cấp";
    }
    return $name;
}

function get_link_by_type($type,$id){
     if($type==1){
        $link = anchor("sales/receipt/".$id, '<span>Xem chi tiết</span>',array('target' => '_blank'));
    }else if($type == 2){
        $link = "";
    }else if($type == 3){
        $link = "";
    }else if($type == 4){
        $link = "";
    }else if($type == 5){
        $link = "";
    }else if($type == 6){
        $link = "";
    }else{
        $link = "";
    }
    return $link;
}

function get_link_by_type_receiving($type,$id){
     if($type==1){
        $link = anchor("receivings/receipt/".$id, '<span>Xem chi tiết</span>',array('target' => '_blank'));
    }else if($type == 2){
        $link = anchor("receivings/receipt/".$id, '<span>Xem chi tiết</span>',array('target' => '_blank'));
    }else if($type == 3){
        $link = "";
    }else if($type == 4){
        $link = "";
    }else if($type == 5){
        $link = "";
    }else if($type == 6){
        $link = "";
    }else{
        $link = "";
    }
    return $link;
}

function currency_side()
{
    $config = get_instance()->config;
    $fmt = new \NumberFormatter($config->item('number_locale'), \NumberFormatter::CURRENCY);
    $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $config->item('currency_symbol'));
    return !preg_match('/^¤/', $fmt->getPattern());
}

function quantity_decimals()
{
    $config = get_instance()->config;
    return $config->item('quantity_decimals') ? $config->item('quantity_decimals') : 0;
}

function totals_decimals()
{
	$config = get_instance()->config;
	return $config->item('currency_decimals') ? $config->item('currency_decimals') : 0;
}

function to_currency_kg($number)
{
    return to_decimals($number, 'currency_decimals')." Đ/1Kg";
}

function to_currency($number)
{
    //return to_decimals($number, 'currency_decimals')." Đ";
    return to_decimals($number, 'currency_decimals');
}

function dd($arr)
{
   echo "<pre>"; print_r($arr); echo "</pre>";
   exit;
}

function to_currency_no_money($number)
{
    return to_decimals($number, 'currency_decimals');
}

function to_tax_decimals($number)
{
	// taxes that are NULL, '' or 0 don't need to be displayed
	// NOTE: do not remove this line otherwise the items edit form will show a tax with 0 and it will save it
    if(empty($number))
    {
        return $number;
    }
	
    return to_decimals($number, 'tax_decimals');
}

function to_quantity_decimals($number)
{
    return to_decimals($number, 'quantity_decimals');
}

function to_decimals($number, $decimals, $type=\NumberFormatter::DECIMAL)
{
	// ignore empty strings and return
	// NOTE: do not change it to empty otherwise tables will show a 0 with no decimal nor currency symbol
    if(!isset($number))
    {
        return $number;
    }
	
    $config = get_instance()->config;
    $fmt = new \NumberFormatter($config->item('number_locale'), $type);
    $fmt->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, $config->item($decimals));
    $fmt->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, $config->item($decimals));
    if (empty($config->item('thousands_separator')))
    {
        $fmt->setAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
    }
    $fmt->setSymbol(\NumberFormatter::CURRENCY_SYMBOL, $config->item('currency_symbol'));
    return $fmt->format($number);
}

function parse_decimals($number)
{
    // ignore empty strings and return
    if(empty($number))
    {
        return $number;
    }

    $config = get_instance()->config;
    $fmt = new \NumberFormatter( $config->item('number_locale'), \NumberFormatter::DECIMAL );
    if (empty($config->item('thousands_separator')))
    {
        $fmt->setAttribute(\NumberFormatter::GROUPING_SEPARATOR_SYMBOL, '');
    }
    return $fmt->parse($number);
}

/*
 * Time locale conversion utility
 */

function dateformat_momentjs($php_format)
{
    $SYMBOLS_MATCHING = array(
        'd' => 'DD',
        'D' => 'ddd',
        'j' => 'D',
        'l' => 'dddd',
        'N' => 'E',
        'S' => 'o',
        'w' => 'e',
        'z' => 'DDD',
        'W' => 'W',
        'F' => 'MMMM',
        'm' => 'MM',
        'M' => 'MMM',
        'n' => 'M',
        't' => '', // no equivalent
        'L' => '', // no equivalent
        'o' => 'YYYY',
        'Y' => 'YYYY',
        'y' => 'YY',
        'a' => 'a',
        'A' => 'A',
        'B' => '', // no equivalent
        'g' => 'h',
        'G' => 'H',
        'h' => 'hh',
        'H' => 'HH',
        'i' => 'mm',
        's' => 'ss',
        'u' => 'SSS',
        'e' => 'zz', // deprecated since version $1.6.0 of moment.js
        'I' => '', // no equivalent
        'O' => '', // no equivalent
        'P' => '', // no equivalent
        'T' => '', // no equivalent
        'Z' => '', // no equivalent
        'c' => '', // no equivalent
        'r' => '', // no equivalent
        'U' => 'X'
    );

    return strtr($php_format, $SYMBOLS_MATCHING);
}

function dateformat_bootstrap($php_format)
{
    $SYMBOLS_MATCHING = array(
        // Day
        'd' => 'dd',
        'D' => 'd',
        'j' => 'd',
        'l' => 'dd',
        'N' => '',
        'S' => '',
        'w' => '',
        'z' => '',
        // Week
        'W' => '',
        // Month
        'F' => 'MM',
        'm' => 'mm',
        'M' => 'M',
        'n' => 'm',
        't' => '',
        // Year
        'L' => '',
        'o' => '',
        'Y' => 'yyyy',
        'y' => 'yy',
        // Time
        'a' => 'p',
        'A' => 'P',
        'B' => '',
        'g' => 'H',
        'G' => 'h',
        'h' => 'HH',
        'H' => 'hh',
        'i' => 'ii',
        's' => 'ss',
        'u' => ''
    );

    return strtr($php_format, $SYMBOLS_MATCHING);
}

?>
