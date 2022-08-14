<?php

namespace App\Tools;

class Content
{
    public function shapeSpace_truncate_string_at_word($string, $limit, $break = '.', $pad = '...')
    {
        $string = strip_tags($string);
        if (strlen($string) <= $limit) {
            return $string;
        }

        if (false !== ($max = strpos($string, $break, $limit))) {
            if ($max < strlen($string) - 1) {
                $string = substr($string, 0, $max).$pad;
            }
        }

        return $string;
    }
}
