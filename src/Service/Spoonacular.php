<?php

namespace App\Service;

class Spoonacular
{
    const URL = 'https://api.spoonacular.com';
    const KEY = '3c1ca942c5394954b1409e4e0eff18c9';

    public function search(string $query)
    {
        $url = 'recipes/search?query='.urlencode(trim($query));

        return $this->getResponse($url);
    }

    public function searchById(int $id)
    {
        $url = "recipes/{$id}/information?includeNutrition=false";

        return $this->getResponse($url);
    }

    public function searchByIngredients(string $query)
    {
        $url = 'recipes/findByIngredients?ingredients='.trim($query);

        return $this->getResponse($url);
    }

    public function getResponse(string $url)
    {

        $curl = curl_init(self::URL.'/'.$url.'&apiKey='.self::KEY);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($curl);
        if (curl_error($curl)) {
            throw new \Exception("Impossible d'accéder à l'API: ". curl_strerror(curl_errno($curl)));
        }

        return json_decode($return, true);
    }
}