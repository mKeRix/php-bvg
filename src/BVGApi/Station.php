<?php

namespace BVGApi;


use BVGApi\Exceptions\ApiException;
use Carbon\Carbon;
use PHPHtmlParser\Dom;

class Station
{
    /**
     * Gets the API endpoint that should be used.
     * Returns a dummy API when the CI environment variable is true.
     *
     * @return string
     */
    private static function getApiEndpoint()
    {
        if (getenv('CI') == true) {
            return 'http://php-bvg-ci.herokuapp.com/';
        }
        else {
            return 'http://mobil.bvg.de/Fahrinfo/bin/stboard.bin/eox';
        }
    }

    /**
     * Searches for station data using a search term.
     *
     * @param string $searchTerm
     * @return array
     * @throws ApiException
     */
    public static function getStations (string $searchTerm)
    {
        // send search data to bvg mobile site
        $payload = ['input' => $searchTerm];
        $response = \Requests::post(self::getApiEndpoint(), [], $payload);

        if ($response->status_code == 200) {
            // our results array
            $stations = [];
            // prepare document
            $dom = new Dom();
            $dom->load($response->body);

            // loop through each suggested station
            foreach ($dom->find('.select a') as $station) {
                // get url parameters of current station for info
                $url = $station->href;
                $query = parse_url($url)['query'];
                parse_str($query, $parameters);
                // push the station information onto our results array
                $stations[] = [
                    'id' => $parameters['input'],
                    'name' => trim($dom->text)
                ];
            }

            // return results
            return $stations;
        }
        else {
            throw new ApiException('Failed getting station data from BVG API');
        }
    }

    /**
     * Gets departures from the given station starting at the given time.
     *
     * @param int $stationID
     * @param Carbon $time
     * @return array
     * @throws ApiException
     */
    public static function getDepartures (int $stationID, Carbon $time)
    {
        // prepare parameters for our request
        $query = [
            'input' => $stationID,
            'boardType' => 'dep',
            'time' => $time->format('H:i'),
            'date' => $time->format('d.m.y')
        ];
        // send it to the bvg mobile site
        $response = \Requests::get(self::getApiEndpoint(), [], $query);

        if ($response->status_code == 200) {
            // our results array
            $departures = [];
            // prepare document
            $dom = new Dom();
            $dom->load($response->body);

            // get date from API
            $date = $dom->find('#ivu_overview_input');
            $date = substr($date->text, strpos($date->text, ':') + 2);
            $date = Carbon::createFromFormat('d.m.y', $date, 'Europe/Berlin');
            // get table data without the first line (header)
            $rows = array_slice($dom->find('.ivu_result_box .ivu_table tr'), 1);
            // loop through each departure in the table
            foreach ($rows as $row) {
                // get columns
                $columns = $row->find('td');
                $time = explode(':', $columns[0]);
                // push the departure onto our results array
                $departures[] = [
                    'time' => $date->hour($time[0])->minute($time[1]),
                    'line' => $columns[1],
                    'direction' => $columns[3]
                ];
            }

            // return results
            return $departures;
        }
        else {
            throw new ApiException('Failed getting station data from BVG API');
        }
    }
}