<?php

namespace AppBundle\Tests\Utils;

use AppBundle\Utils;

class GeoUtilsTest extends \PHPUnit_Framework_TestCase{

    public function testFirst()
    {
        $geoUtils = new Utils\GeoUtils();

        $return = $geoUtils->fetchDataID('EY171');

        $this->assertNotEmpty($return);
    }
}