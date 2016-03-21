<?php


class StationTest extends PHPUnit_Framework_TestCase
{
    public function testStationSearch()
    {
        $stations = BVGApi\Station::getStations('alexanderplatz');
        $expected = [
            [
                'id' => '9100003',
                'name' => 'S+U Alexanderplatz Bhf (Berlin)'
            ],
            [
                'id' => '9100024',
                'name' => 'S+U Alexanderplatz Bhf/Dircksenstr. (Berlin)'
            ]
        ];

        $this->assertEquals($expected, $stations);
    }

    public function testDepartures()
    {
        $time = \Carbon\Carbon::create(2016, 3, 21, 12, 0, 0, 'Europe/Berlin');
        $departures = BVGApi\Station::getDepartures(9100003, $time);
        $expected = [
            [
                'time' => \Carbon\Carbon::create(2016, 3, 21, 12, 0, 0, 'Europe/Berlin'),
                'line' => 'Tra M6',
                'direction' => 'S Hackescher Markt (Berlin)'
            ],
            [
                'time' => \Carbon\Carbon::create(2016, 3, 21, 12, 0, 0, 'Europe/Berlin'),
                'line' => 'Tra M6',
                'direction' => 'Riesaer Str. (Berlin)'
            ],
            [
                'time' => \Carbon\Carbon::create(2016, 3, 21, 12, 0, 0, 'Europe/Berlin'),
                'line' => 'Tra M2',
                'direction' => 'Am Steinberg'
            ]
        ];

        $this->assertEquals($expected, $departures);
    }

    public function testApiException()
    {
        $this->expectException(\BVGApi\Exceptions\ApiException::class);

        // we can just query some random string here to get a 403
        $stations = BVGApi\Station::getStations('403me');
    }
}
