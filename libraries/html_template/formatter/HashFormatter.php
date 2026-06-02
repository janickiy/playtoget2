<?php

class HashFormatter extends AbstractValueFormatter
{
    public function formatValue($value)
    {
        //convert value to md5 hash
        return md5($value);
    }
}

?>