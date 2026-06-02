<?php

class TimeFormatter extends AbstractValueFormatter
{
    public function formatValue($value)
    {
        //check if value is numeric
        if(!is_numeric($value))
        {
            //value cannot be formatted
            //in this case we return original value
            return $value;
        }
        
        //create human readable time
        return date('H:i', $value);
    }
}

?>