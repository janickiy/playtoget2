<?php

class TextFormatter extends AbstractValueFormatter
{
    public function formatValue($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

?>