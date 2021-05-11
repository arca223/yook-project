<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class PartnerService
{
    private $httpClient;
    private $partners = [];
    private $filteredPartners = [];

    const CLIENT_URL = "https://mocki.io/v1/a28c40bf-060e-46d7-bfb5-6d6e798da636";

    public function __construct(HttpClientInterface $client)
    {
        $this->httpClient = $client;
    }

    public function getPartners(): array
    {
        if (empty($this->partners)) {
            $response = $this->httpClient->request('GET', self::CLIENT_URL, []);
            $this->partners = json_decode($response->getContent(), true)['offsetProjects'];
        }

        return $this->partners;
    }

    public function getPartnersByType($type): array
    {
        $partners = $this->getPartners();

        return $this->filterPartnersByType($partners, $type);
    }

    public function getFilteredPartners(): array
    {
        $partners = $this->getPartners();

        //TODO: Refacto the filtering with a constant or helper without hardcode
        foreach ($partners as $partner) {
            switch ($partner['type']) {
                case 1:
                    $this->filteredPartners['1'][] = $partner;
                    break;
                case 2:
                    $this->filteredPartners['2'][] = $partner;
                    break;
                case 3:
                    $this->filteredPartners['3'][] = $partner;
                    break;
                case 4:
                    $this->filteredPartners['4'][] = $partner;
                    break;
                case 5:
                    $this->filteredPartners['5'][] = $partner;
                    break;
                default:
                    break;
            }
        }

        return $this->filteredPartners;
    }

    public function countType12Partners(): int
    {
        $partners = $this->getPartners();
        return count($this->filterPartnersByType($partners, 1))
            + count($this->filterPartnersByType($partners, 2));
    }

    private function filterPartnersByType($partners, $type): array
    {
        return array_filter($partners, function ($partner) use ($type) {
            return $partner['type'] === $type;
        });
    }
}