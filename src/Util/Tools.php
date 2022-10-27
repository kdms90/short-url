<?php

namespace App\Util;


use ArrayObject;
use DateTime;
use Exception;
use FilesystemIterator;
use Gedmo\Sluggable\Util as Sluggable;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Contracts\Translation\TranslatorInterface;
use Traversable;
use ZipArchive;

/**
 * The main controller class.
 *
 * Contain generic method for visitors.
 *
 * @link       http://github.com/kdms90
 *
 * @since      1.0.0
 * @package    App
 * @subpackage App\Util
 * @author     Marius Stanislas KEMAYOU DJEUMENI <kdms1990@gmail.com>
 */
class Tools
{
    /** @var  TranslatorInterface */
    protected $translator;
    private $ip_info_api = "http://www.geoplugin.net/json.gp?ip=";

    /**
     * Tools constructor.
     *
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $src
     * @param $output
     * @param $name
     *
     * @return string
     */
    public function createZip($src, $output, $name)
    {
        // Get real path for our folder
        $rootPath    = realpath($src);
        $outPutDir   = realpath($output);
        $zipFileName = $outPutDir . DIRECTORY_SEPARATOR . $name . '.zip';
        // Initialize archive object
        $zip = new ZipArchive();
        $zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        // Create recursive directory iterator
        /** @var \SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath     = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }
        //close the zip -- done!
        try {
            $zip->close();
        } catch (Exception $exception) {
        }

        return $zipFileName;
    }

    /**
     * Recursive delete folders et files
     *
     * @param $files string files or folder to delete
     *
     * @throws \Exception
     */
    public function removeFiles_($files)
    {
        $files = iterator_to_array($this->toIterator($files));
        $files = array_reverse($files);
        foreach ($files as $file) {
            if (!file_exists($file) && !is_link($file)) {
                continue;
            }

            if (is_dir($file) && !is_link($file)) {
                $this->removeFiles(new FilesystemIterator($file));

                if (true !== @rmdir($file)) {
                    throw new Exception(sprintf('Failed to remove directory "%s".', $file), 0, null, $file);
                }
            } else {
                // https://bugs.php.net/bug.php?id=52176
                if (file_exists($file))
                    @unlink($file);
                if ('\\' === DIRECTORY_SEPARATOR && is_dir($file)) {
                    if (true !== @rmdir($file)) {
                        throw new Exception(sprintf('Failed to remove file "%s".', $file), 0, null, $file);
                    }
                } else {
                    if (true !== @unlink($file)) {
                        throw new Exception(sprintf('Failed to remove file "%s".', $file), 0, null, $file);
                    }
                }
            }
        }
    }

    /**
     * Find all site
     *
     * @param $files
     *
     * @return \ArrayObject
     */
    private function toIterator($files)
    {
        if (!$files instanceof Traversable) {
            $files = new ArrayObject(is_array($files) ? $files : [$files]);
        }

        return $files;
    }

    /**
     * Recursive delete folders et files
     *
     * @param $target string files or folder to delete
     *
     * @throws \Exception
     */
    public function removeFiles($target)
    {
        if (is_dir($target)) {
            $files = glob($target . '*', GLOB_MARK); //GLOB_MARK adds a slash to directories returned

            foreach ($files as $file) {
                $this->removeFiles($file);
            }

            @rmdir($target);
        } else if (is_file($target)) {
            @unlink($target);
        }
    }

    public function numberToWords($number, $locale = 'en')
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion',
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int)$number < 0) || (int)$number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                '$this->numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );

            return false;
        }

        if ($number < 0) {
            return $negative . $this->numberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            [$number, $fraction] = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int)($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string    = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . $this->numberToWords($remainder);
                }
                break;
            default:
                $baseUnit     = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int)($number / $baseUnit);
                $remainder    = $number % $baseUnit;
                $string       = $this->numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= $this->numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words  = [];
            foreach (str_split((string)$fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }

    public function normalizeName($text)
    {
        return $this->slugify($text);
    }

    public function slugify($text)
    {
        return substr(Sluggable\Urlizer::urlize($text, '-'), 0, 250);
    }

    /**
     * Check if a string is a valid date(time)
     *
     * DateTime::createFromFormat requires PHP >= 5.3
     *
     * @param string $format
     * @param string $time (If timezone is invalid, php will throw an exception)
     *
     * @return bool
     */
    public function isValidDateTimeString($format, $time)
    {
        $date = DateTime::createFromFormat($format, $time);

        return $date && DateTime::getLastErrors()["warning_count"] == 0 && DateTime::getLastErrors()["error_count"] == 0;
    }

    /**
     * @param $html
     *
     * @return string
     */
    public function closeTags($html)
    {
        preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
        $openedtags = $result[1];
        preg_match_all('#</([a-z]+)>#iU', $html, $result);
        $closedtags = $result[1];
        $len_opened = count($openedtags);
        if (count($closedtags) == $len_opened) {
            return $html;
        }
        $openedtags = array_reverse($openedtags);
        for ($i = 0; $i < $len_opened; $i++) {
            if (!in_array($openedtags[$i], $closedtags)) {
                $html .= '</' . $openedtags[$i] . '>';
            } else {
                unset($closedtags[array_search($openedtags[$i], $closedtags)]);
            }
        }

        return $html;
    }

    public function getMemoryLimit()
    {
        $memory_limit = @ini_get('memory_limit');

        return $this->getOctets($memory_limit);
    }

    /**
     * getOctet allow to gets the value of a configuration option in octet
     *
     * @return int the value of a configuration option in octet
     * @since 1.5.0
     */
    public function getOctets($option)
    {
        if (preg_match('/[0-9]+k/i', $option)) {
            return 1024 * (int)$option;
        }

        if (preg_match('/[0-9]+m/i', $option)) {
            return 1024 * 1024 * (int)$option;
        }

        if (preg_match('/[0-9]+g/i', $option)) {
            return 1024 * 1024 * 1024 * (int)$option;
        }

        return $option;
    }

    /**
     * @param \DateTime $fromDate
     * @param null $toDate
     *
     * @return string
     */
    public function dateDifference($fromDate, $toDate = null)
    {
        if ($toDate == null)
            $toDate = new DateTime();

        $diff  = abs(strtotime($toDate->format('Y-m-d H:i:s')) - strtotime($fromDate->format('Y-m-d H:i:s')));
        $hours = round($diff / 3600);
        if ($hours >= 24) {
            $days = round($hours / 24);
            if ($days >= 30) {
                $months = round($days / 30);
                if ($months >= 12) {
                    $years = round($months / 12);
                    if ($years > 1)
                        $elapseTime = $this->translator->trans('years', ['%s%' => $years], 'timer');
                    else
                        $elapseTime = $this->translator->trans('year', ['%s%' => $years], 'timer');
                } else {
                    $elapseTime = $this->translator->trans('month', ['%s%' => $months], 'timer');
                    if ($months > 1)
                        $elapseTime = $this->translator->trans('months', ['%s%' => $months], 'timer');
                }
            } else if ($days == 7) {//One week
                $elapseTime = $this->translator->trans('one_week', [], 'timer');
            } else {
                $weeks = round($days / 7);
                if ($weeks > 1)
                    $elapseTime = $this->translator->trans('weeks', ['%s%' => $weeks], 'timer');
                else
                    $elapseTime = $this->translator->trans('days', ['%s%' => $days], 'timer');
            }
        } else {
            if ($hours > 1) {
                $elapseTime = $this->translator->trans('hours', ['%s%' => $hours], 'timer');
            } else if ($hours == 1) {
                $elapseTime = $this->translator->trans('hour', ['%s%' => $hours], 'timer');
            } else {
                $minutes = round(($diff / 3600) * 60);
                if ($minutes < 1)
                    $elapseTime = $this->translator->trans('now', [], 'timer');
                else {
                    $elapseTime = $this->translator->trans('less_one_hour', [], 'timer');
                    if ($minutes < 44) {
                        $elapseTime = $this->translator->trans('minutes', ['%s%' => $minutes], 'timer');
                    }
                }
            }
        }


        return $elapseTime;
    }

    /**
     * @param \DateTime $fromDate
     *
     * @return string
     */
    public function elapseTime($fromDate)
    {
        $toDate = new DateTime();
        $diff   = abs(strtotime($toDate->format('Y-m-d H:i:s')) - strtotime($fromDate->format('Y-m-d H:i:s')));
        $hours  = round($diff / 3600);
        if ($hours >= 24) {
            $days = round($hours / 24);
            if ($days >= 30) {
                $months = round($days / 30);
                if ($months >= 12) {
                    $years = round($months / 12);
                    if ($years > 1)
                        $elapseTime = $this->translator->trans('years_ago', ['%s%' => $years], 'timer');
                    else
                        $elapseTime = $this->translator->trans('year_ago', ['%s%' => $years], 'timer');
                } else {
                    $elapseTime = $this->translator->trans('month_ago', ['%s%' => $months], 'timer');
                    if ($months > 1)
                        $elapseTime = $this->translator->trans('months_ago', ['%s%' => $months], 'timer');
                }
            } else if ($days == 7) {//One week
                $elapseTime = $this->translator->trans('one_week_ago', [], 'timer');
            } else {
                $weeks = round($days / 7);
                if ($weeks > 1)
                    $elapseTime = $this->translator->trans('weeks_ago', ['%s%' => $weeks], 'timer');
                else
                    $elapseTime = $this->translator->trans('days_ago', ['%s%' => $days], 'timer');
            }
        } else {
            if ($hours > 1) {
                $elapseTime = $this->translator->trans('hours_ago', ['%s%' => $hours], 'timer');
            } else if ($hours == 1) {
                $elapseTime = $this->translator->trans('hour_ago', ['%s%' => $hours], 'timer');
            } else {
                $minutes = round(($diff / 3600) * 60);
                if ($minutes < 1)
                    $elapseTime = $this->translator->trans('just_now', [], 'timer');
                else {
                    $elapseTime = $this->translator->trans('less_one_hour_ago', [], 'timer');
                    if ($minutes < 44) {
                        if ($minutes < 2)
                            $elapseTime = $this->translator->trans('minute_ago', ['%s%' => $minutes], 'timer');
                        else
                            $elapseTime = $this->translator->trans('minutes_ago', ['%s%' => $minutes], 'timer');
                    }
                }
            }
        }


        return $elapseTime;
    }


    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     * @param int $daysNumbers
     *
     * @return float
     */
    public function dateDifferenceInMonths($fromDate, $toDate, $daysNumbers = 30)
    {
        if ($daysNumbers < 1)
            $daysNumbers = 1;
        $days = $this->dateDifferenceInDay($fromDate, $toDate);
        return round($days / ($daysNumbers), 2);
    }

    /**
     * @param \DateTime $fromDate
     * @param \DateTime $toDate
     *
     * @return float
     */
    public function dateDifferenceInDay($fromDate, $toDate)
    {
        if (!($fromDate instanceof DateTime))
            $fromDate = new DateTime();
        if (!($toDate instanceof DateTime))
            $toDate = new DateTime();
        $datediff = abs(strtotime($toDate->format('Y-m-d H:i:s')) - strtotime($fromDate->format('Y-m-d H:i:s')));

        return round($datediff / (60 * 60 * 24), 2);
    }

    public function randomReference($motif = 'APP_')
    {
        $characters = '0123456789';
        $string     = $motif;
        for ($p = 0; $p < strlen($characters); $p++) {
            $string .= $characters[mt_rand(0, 7)];
        }

        return $string;
    }

    public function extractUrlHost($url)
    {
        return str_replace('www.', '', parse_url($url, PHP_URL_HOST));
    }

    /**
     * Get Visitor IP Address
     * @return mixed
     */
    public function getVisIpAddr() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /**
     * Get visitor country Name
     * @return string countryName
     */
    public function getCurrentVisitorCountryName(){
        $vis_ip = $this->getVisIPAddr();
        if($vis_ip == "127.0.0.1")
            return "cameroon";
        // Use JSON encoded string and converts
        // it into a PHP variable
        $ipInfos = @json_decode(file_get_contents( $this->ip_info_api . $vis_ip));
        return $ipInfos->geoplugin_countryName;
    }

    /**
     * Get visitor country Name
     * @return string countryName
     */
    public function getCurrentVisitorCityName(){
        $vis_ip = $this->getVisIPAddr();
        if($vis_ip == "127.0.0.1")
            return "";
        // Use JSON encoded string and converts
        // it into a PHP variable
        $ipInfos = @json_decode(file_get_contents( $this->ip_info_api . $vis_ip));
        return $ipInfos->geoplugin_city;
    }
}
