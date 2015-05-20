<?php

namespace RAAFPAGE\SiteBundle\Service\Utils;

class StringManipulator
{
    public static function convertToSlug($string)
    {
        $string = strtolower($string);
        $string = html_entity_decode($string);
        $string = str_replace(array('ä','ü','ö','ß'),array('ae','ue','oe','ss'), $string);
        $string = preg_replace('#[^\w\säüöß]#',null,$string);
        $string = preg_replace('#[\s]{2,}#',' ',$string);
        $string = str_replace(array(' '),array('-'),$string);

        return $string;
    }
}
