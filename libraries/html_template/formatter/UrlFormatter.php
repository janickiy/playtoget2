<?php

class UrlFormatter extends AbstractValueFormatter
{
    public function formatValue($value) 
    {
        return urlencode($value);
    }
}

?>