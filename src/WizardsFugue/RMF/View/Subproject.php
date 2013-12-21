<?php

namespace WizardsFugue\RMF\View;

use \Qafoo\RMF\View;
use \Qafoo\RMF\Request;

class Subproject extends View
{
    public function display(Request $request, $result)
    {
        if( $result instanceof \Exception ){
            throw $result;
        }
        //var_dump($this,$request,$result);
        require $result['index_path'];
    }
}