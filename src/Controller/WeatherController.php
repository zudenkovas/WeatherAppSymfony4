<?php

namespace App\Controller;

use App\Model\NullWeather;
use App\Weather\LoaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class WeatherController
 * @package App\Controller
 */

class WeatherController extends AbstractController
{

    public function index($day, LoaderService $loaderService)
    {
        $validDay = $loaderService->validateDate($day);

        try {
            $weather = $loaderService->loadWeatherByDate(new \DateTime($validDay));
        } catch (\Exception $exp) {
            $weather = new NullWeather();
        }



        return $this->render('weather/index.html.twig', [
            'weatherData'=>[
                'date'=> $weather->getDate()->format('Y-m-d'),
                'dayTemp'=> $weather->getDayTemp(),
                'nightTemp' => $weather->getNightTemp(),
                'sky'=> $weather->getSky()
            ]
        ]);


    }
}
