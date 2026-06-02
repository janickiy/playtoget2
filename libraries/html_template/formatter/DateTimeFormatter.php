<?php

class DateTimeFormatter extends AbstractValueFormatter
{
    public function formatValue($value)
    {
        $resultDate = null;
        $resultTime = null;
        
        //check if value is numeric
        if(!is_numeric($value))
        {
            //value cannot be formatted
            //in this case we return original value
            return $value;
        }
        
        //create human readable date
        $resultDate = date('d.m.Y', $value); 
        
        //create human readable time
        $resultTime = date('H:i', $value);
        
        return $resultDate . ' ' . $resultTime;
    }
}

?>