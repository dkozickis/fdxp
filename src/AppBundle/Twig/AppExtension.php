<?php

namespace AppBundle\Twig;


class AppExtension extends \Twig_Extension {

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('strip_script', array($this, 'scriptFilter'), array(
                'is_safe' => array('html')
            )),
        );
    }

    public function scriptFilter($html)
    {
        if(!empty($html)) {
            $doc = new \DOMDocument();
            $doc->loadHTML($html);
            $doc->removeChild($doc->doctype);
            $doc->replaceChild($doc->firstChild->firstChild, $doc->firstChild);

            $script_tags = $doc->getElementsByTagName('script');

            $length = $script_tags->length;

            for ($i = 0; $i < $length; $i++) {
                $script_tags->item($i)->parentNode->removeChild($script_tags->item($i));
            }

            return $doc->saveHTML();
        }else{
            return $html;
        }
    }

    public function getName() {
        return 'app_extension';
    }

}