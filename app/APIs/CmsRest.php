<?php


namespace App\ApiClients;

use App\Models\Cms;
use App\Models\CmsCoSpace;
use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use App\Models\CmsStat;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;

class CmsRest
{
    /**
     * @var Cms
     */
    private $cms;

    /**
     * @var Client
     */
    private $client;

    /**
     * CmsRest constructor.
     *
     * @param Cms $cms
     */
    public function __construct(Cms $cms)
    {
        $this->cms = $cms;

        $this->client = new Client([
            'base_uri' => "https://{$cms->host}:445",
            'verify' => false,
            'timeout' => 10,
            'connect_timeout' => 10,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'auth' => [
                $cms->username,
                $cms->password
            ],
        ]);
    }

    /**
     *  Wrapper to query the CMS Api
     *
     * @param $uri
     * @return array|mixed
     * @throws GuzzleException
     */
    private function queryCmsApi($uri)
    {
        logger()->debug("CmsRest@queryCmsApi ({$this->cms->host}): Initiating query", [
            'uri' => $uri
        ]);
        try {
            $response = $this->client->get($uri);
            $xml = simplexml_load_string($response->getBody()->getContents());
            $json = json_encode($xml);

            logger()->debug("CmsRest@queryCmsApi ({$this->cms->host}): Received successful response");
            logger()->debug("CmsRest@queryCmsApi ({$this->cms->host}): JSON response", [
                'response' => $json
            ]);
            return json_decode($json, true);

        } catch (RequestException $e) {
            if ($e instanceof ClientException) {
                logger()->error('Cms@queryCmsApi: Authentication Exception', [$e->getMessage()]);
            } elseif ($e instanceof ConnectException) {
                logger()->error('Cms@queryCmsApi: Connection Exception', [$e->getMessage()]);
            } else {
                logger()->error('Cms@queryCmsApi: Unknown Exception', [$e->getMessage()]);
            }
            die();
        }
    }

    /**
     * @throws GuzzleException
     */
    public function getCoSpaces()
    {
        info("CmsRest@getCoSpaces ({$this->cms->host}): Collecting CMS CoSpaces");

        $offset = 0;
        $limit = 1;
        $now = now();

        info("CmsRest@getCoSpaces ({$this->cms->host}): Set initial query params", [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $response = $this->queryCmsApi("/api/v1/coSpaces?offset={$offset}&limit={$limit}");

        $total = $response['@attributes']['total'];
        $limit = 20;
        $iterations = (int) ceil((int) $total / $limit);

        info("CmsRest@getCoSpaces ({$this->cms->host}): Calculated query iterations", [
            'total' => $total,
            'limit' => $limit,
            'iterations' => $iterations
        ]);

        info("CmsRest@getCoSpaces ({$this->cms->host}): Iterating API");
        for($i = 1; $i <= $iterations; $i++) {
            $response = $this->queryCmsApi("/api/v1/coSpaces?offset={$offset}&limit={$limit}");
            foreach($response['coSpace'] as $coSpace) {
                logger()->debug("CmsRest@getCoSpaces ({$this->cms->host}): Collecting CoSpace details", [
                    'coSpace' => $coSpace
                ]);
                $response = $this->queryCmsApi("/api/v1/coSpaces/{$coSpace['@attributes']['id']}");

                logger()->debug("CmsRest@getCoSpaces ({$this->cms->host}): Updating model");
                CmsCoSpace::updateOrCreate([
                    'space_id' =>  $response['@attributes']['id']],
                    [
                        'name' => $response['name'],
                        'ownerId' => $response['ownerId']
                    ]
                )->touch();
            }
            $offset += $limit;
            logger()->debug("CmsRest@getCoSpaces ({$this->cms->host}): Iterating offset", [
                'offset' => $offset
            ]);

            $percentFinished = (int) floor((($i / $iterations) * 100));
            if($percentFinished % 10 === 0) {
                info("CmsRest@getCoSpaces ({$this->cms->host}): $percentFinished% done");
            }
        }

        info("CmsRest@queryCmsApi ({$this->cms->host}): Removing stale accounts");
        CmsCoSpace::where('updated_at', '<', $now)->delete();
    }

    /**
     * @throws GuzzleException
     */
    public function getCmsUserIds()
    {
        info("CmsRest@getCmsUserIds ({$this->cms->host}): Collecting CMS User ID's");

        $offset = 0;
        $limit = 1;
        $now = now();

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Set initial query params", [
            'offset' => $offset,
            'limit' => $limit,
        ]);

        $response = $this->queryCmsApi("/api/v1/users?offset={$offset}&limit={$limit}");

        $total = $response['@attributes']['total'];
        $limit = 20;
        $iterations = (int) ceil((int) $total / $limit);

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Calculated query iterations", [
            'total' => $total,
            'limit' => $limit,
            'iterations' => $iterations
        ]);

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Iterating API");
        for($i = 1; $i <= $iterations; $i++) {
            $response = $this->queryCmsApi("/api/v1/users?offset={$offset}&limit={$limit}");
            foreach($response['user'] as $user) {
                logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Collecting User details", [
                    'coSpace' => $user
                ]);
                $response = $this->queryCmsApi("/api/v1/users/{$user['@attributes']['id']}");
                if($response['email']) {
                    logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Updating model");
                    User::updateOrCreate(
                        ['email' => $response['email']],
                        [
                            'name' => $response['name'],
                            'cms_owner_id' => $response['@attributes']['id']
                        ]
                    )->touch();
                }
            }

            $offset += $limit;
            logger()->debug("CmsRest@getCmsUserIds ({$this->cms->host}): Iterating offset", [
                'offset' => $offset
            ]);

            $percentFinished = (int) floor((($i / $iterations) * 100));
            if($percentFinished % 10 === 0) {
                info("CmsRest@getCmsUserIds ({$this->cms->host}): $percentFinished% done");
            }
        }

        info("CmsRest@getCmsUserIds ({$this->cms->host}): Removing stale accounts");
        User::where('cms_owner_id', '!=', '')->where('updated_at', '<', $now)->delete();
    }
}
