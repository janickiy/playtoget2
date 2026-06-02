<?php

class IsEmptyFormatter extends AbstractValueFormatter
{
    public function formatValue($value)
    {
        if($value == '')
        {
            return 'TRUE';
        }
        
        return 'FALSE';
    }
}

?>