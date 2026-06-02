<?php
defined('PLAYTOGET') || exit('Playtoget: access denied!');

class View
{

    public function generate($template_view, $data = null)
    {
        include core::pathTo('views', $template_view);
    }
}

?>
