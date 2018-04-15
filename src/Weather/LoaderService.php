<?php
/**
 * Created by PhpStorm.
 * User: vitalijus
 * Date: 18.4.12
 * Time: 17.27
 */

namespace App\Weather;


use App\Model\Weather;
use App\GoogleApi\WeatherService;
use Symfony\Component\Cache\Simple\FilesystemCache;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\LessThanOrEqual;


class LoaderService
{

    /** * @var WeatherService */
    private $googleWeatherService;

    /** * @var FilesystemCache */
    private $cacheService;

    /**
     * LoaderService constructor.
     * @param WeatherService $googleWeatherService
     * @param FilesystemCache $cacheService
     */
    public function __construct(WeatherService $googleWeatherService, FilesystemCache $cacheService)
    {
        $this->googleWeatherService = $googleWeatherService;
        $this->cacheService = $cacheService;
    }


    /**
     * @param \DateTime $day
     * @return Weather
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \Exception
     */

    public function loadWeatherByDate(\DateTime $validDay): Weather
    {

        $cacheKey = $this->getCacheKey($validDay);
        if ($this->cacheService->has($cacheKey)) {
            echo "from Cache";
            $weather = $this->cacheService->get($cacheKey);
        } else {
            echo "from API";
            $weather = $this->googleWeatherService->getDay($validDay);
            $this->cacheService->set($cacheKey, $weather);
        }

        return $weather;
    }

    /** @param \DateTime $day
     * @return string
     */
    public function getCacheKey(\DAteTime $validDay): string
    {
        return $validDay->format('Y-m-d');
    }

    /**
     * @param $day
     * @return string
     */

    public function validateDate($day)
    {
        $validator = Validation::createValidator();
        $violations = $validator->validate($day, [
            new Date(),
            new GreaterThanOrEqual(date('Y-m-d')),
            new LessThanOrEqual(date('Y-m-d', strtotime('+60days')))
        ]);
        if (0 !== count($violations)) {

            foreach ($violations as $violation) {
                echo $violation->getMessage() . '<br>';
            }
        } else {return $day;}
    }

}