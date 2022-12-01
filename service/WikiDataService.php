<?php

class WikiDataService
{
    public const SPARQL_ENDPOINT_URL = 'https://query.wikidata.org/sparql';
    public const WIKIDATA_API_URL = 'https://www.wikidata.org/w/api.php';
    public const WIKIPEDIA_API_URL = 'https://fr.wikipedia.org/w/api.php';

    public static function query(string $wikipediaUrl): array
    {
        // Decode url beforehand to avoid any partial encoding
        $wikipediaUrl = urldecode($wikipediaUrl);
        $baseInfo = self::fetchBaseInfo($wikipediaUrl);
        if ($baseInfo['entityUrl']) {
            $entityId = self::extractLastPartOfUri($baseInfo['entityUrl']);
            $imageUrl = self::fetchImageUrl($entityId);
        }
        $pageTitle = self::extractLastPartOfUri($wikipediaUrl);
        $introduction = self::fetchIntroduction($pageTitle);

        return array_merge($baseInfo, [
            'image' => isset($imageUrl) ? $imageUrl : null,
            'introduction' => $introduction,
        ]);
    }

    protected static function fetchBaseInfo($wikipediaUrl): array
    {
        $url = self::SPARQL_ENDPOINT_URL . '?query=' . self::buildUrlEncodedQuery($wikipediaUrl);
        $response = wp_remote_get($url, [
            'timeout' => 30,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => 'Wikibiographie/1.0 (https://productionsrhizome.org; wikibiographie@productionsrhizome.org)',
            ]
        ]);
        $body = json_decode($response['body'], true);
        $rawDateOfBirth = isset($body['results']['bindings'][0]['dateOfBirthLabel']['value']) ? $body['results']['bindings'][0]['dateOfBirthLabel']['value'] : null;
        $rawDateOfDeath = isset($body['results']['bindings'][0]['dateOfDeathLabel']['value']) ? $body['results']['bindings'][0]['dateOfDeathLabel']['value'] : null;
        if ($rawDateOfBirth) {
            $dateOfBirth = date_format(date_create($rawDateOfBirth), "Y/m/d");
        }
        if ($rawDateOfDeath) {
            $dateOfDeath = date_format(date_create($rawDateOfDeath), "Y/m/d");
        }

        return [
            'firstName' => isset($body['results']['bindings'][0]['firstNameLabel']['value']) ? $body['results']['bindings'][0]['firstNameLabel']['value'] : null,
            'lastName' => isset($body['results']['bindings'][0]['lastNameLabel']['value']) ? $body['results']['bindings'][0]['lastNameLabel']['value'] : null,
            'pseudonym' => isset($body['results']['bindings'][0]['pseudonymLabel']['value']) ? $body['results']['bindings'][0]['pseudonymLabel']['value'] : null,
            'dateOfBirth' => isset($dateOfBirth) ? $dateOfBirth : null,
            'placeOfBirth' => isset($body['results']['bindings'][0]['placeOfBirthLabel']['value']) ? $body['results']['bindings'][0]['placeOfBirthLabel']['value'] : null,
            'dateOfDeath' => isset($dateOfDeath) ? $dateOfDeath : null,
            'placeOfDeath' => isset($body['results']['bindings'][0]['placeOfDeathLabel']['value']) ? $body['results']['bindings'][0]['placeOfDeathLabel']['value'] : null,
            'occupation' => isset($body['results']['bindings'][0]['itemDescription']['value']) ? $body['results']['bindings'][0]['itemDescription']['value'] : null,
            'website' => isset($body['results']['bindings'][0]['websiteLabel']['value']) ? $body['results']['bindings'][0]['websiteLabel']['value'] : null,
            'entityUrl' => isset($body['results']['bindings'][0]['item']['value']) ? $body['results']['bindings'][0]['item']['value'] : null,
        ];
    }

    protected static function fetchImageUrl(string $entityId): ?string
    {
        $url = self::WIKIDATA_API_URL.'?action=wbgetclaims&property=P18&entity='.$entityId.'&format=json';
        $response = wp_remote_get($url, [
            'timeout' => 30,
        ]);
        $body = json_decode($response['body'], true);
        $fileName = isset($body['claims']['P18'][0]['mainsnak']['datavalue']['value']) ? $body['claims']['P18'][0]['mainsnak']['datavalue']['value'] : null;
        if (empty($fileName)) {
            return 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f7/Defaut_2.svg/langfr-260px-Defaut_2.svg.png';
        }
        $fileName = str_replace(' ', '_', $fileName);
        $hash = md5($fileName);

        return 'https://upload.wikimedia.org/wikipedia/commons/'.$hash[0].'/'.$hash[0].$hash[1].'/'.$fileName;
    }

    protected static function fetchIntroduction(string $pageTitle): ?string
    {
        $url = self::WIKIPEDIA_API_URL.'?format=json&action=query&prop=extracts&exintro&indexpageids&explaintext&redirects=1&titles='.$pageTitle;
        $response = wp_remote_get($url, [
            'timeout' => 30,
        ]);
        $body = json_decode($response['body'], true);
        $pageIds = isset($body['query']['pageids']) ? $body['query']['pageids'] : [];
        $pageId = reset($pageIds);
        if ($pageId) {
            $introduction = isset($body['query']['pages'][$pageId]['extract']) ? $body['query']['pages'][$pageId]['extract'] : null;
        }

        return isset($introduction) ? $introduction : null;
    }

    protected static function extractLastPartOfUri(string $uri): string
    {
        $parts = explode('/', $uri);

        return end($parts);
    }

    protected static function buildUrlEncodedQuery(string $wikipediaUrl): string
    {
        $exploded = explode('/', $wikipediaUrl);
        end($exploded);
        $k = key($exploded);
        $exploded[$k] = urlencode($exploded[$k]);
        // cancelling url encoding of some very specific chars that cause trouble with WikiData querying
        $exploded[$k] = str_replace(['%28', '%29'], ['(', ')'], $exploded[$k]);
        $urlEncoded = implode('/', $exploded);
        $query = "
            SELECT
                ?item
                ?firstNameLabel
                ?lastNameLabel
                ?pseudonymLabel
                ?dateOfBirthLabel
                ?placeOfBirthLabel
                ?dateOfDeathLabel
                ?placeOfDeathLabel
                ?itemDescription
                ?websiteLabel
            
            WHERE {
                <$urlEncoded> schema:about ?item .
                OPTIONAL { ?item wdt:P735 ?firstName}
                OPTIONAL { ?item wdt:P734 ?lastName}
                OPTIONAL { ?item wdt:P742 ?pseudonym}
                OPTIONAL { ?item wdt:P569 ?dateOfBirth }.
                OPTIONAL { ?item wdt:P19 ?placeOfBirth }.
                OPTIONAL { ?item wdt:P570 ?dateOfDeath }.
                OPTIONAL { ?item wdt:P20 ?placeOfDeath }.
                OPTIONAL { ?item wdt:P856 ?website }.
                
                SERVICE wikibase:label { bd:serviceParam wikibase:language \"fr,en\" }
            }
        ";
        return urlencode($query);
    }
}
