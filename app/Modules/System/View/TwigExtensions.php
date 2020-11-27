<?php

namespace App\Modules\System\View;

use App\Traits\CoreTrait;

class TwigExtensions extends \Twig_Extension
{
    use CoreTrait;

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('number_afix', [$this, 'number_afix']),
            new \Twig_SimpleFilter('timeago', [$this, 'timeago']),
            new \Twig_SimpleFilter('userrole', [$this, 'userrole']),
            new \Twig_SimpleFilter('userbyuid', [$this, 'userbyuid']),
            new \Twig_SimpleFilter('userbyid', [$this, 'userbyid']),
            new \Twig_SimpleFilter('secondsToTime', [$this, 'secondsToTime']),
            new \Twig_SimpleFilter('highlight', [$this, 'highlight']),
        ];
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('set_element', [$this, 'set_element']),
            new \Twig_SimpleFunction('is_active_path', [$this, 'is_active_path']),
            new \Twig_SimpleFunction('get_current_path', [$this, 'get_current_path']),
            new \Twig_SimpleFunction('cache', [$this, 'cache']),
            // Lovi's custom functions
            new \Twig_SimpleFunction('mtime', [$this, 'mtime']),
        ];
    }

    // Higlight search term
    public function highlight($string, $term) {
        $term = explode(' ', $term);
        $term = array_map('trim', $term);
        $term = array_filter($term);

        return preg_replace('/'. implode('|', $term) .'/i', '<span class="highlight">\\0</span>', $string);
    }

    public function cache($name)
    {
        return $this->cache->get($name, 'data');
    }

    public function is_active_path($string = '')
    {
        $uri    = $this->container['request']->getUri();
        $router = $this->container['router'];

        $uri_tail = explode( $uri->getBaseUrl().'/', $uri, 2 )[1];

        if (!$string && !$uri_tail) {
            return 'active';
        }
        elseif ($string) {
            $pattern = '/^'. preg_quote($string, '/') .'/';
            return preg_match($pattern, $uri_tail)?'active':'';
        }
        else {
            return '';
        }       
    }

    public function get_current_path()
    {
        $uri    = $this->container['request']->getUri();
        $router = $this->container['router'];

        $uri_tail = explode( $uri->getBaseUrl().'/', $uri, 2 )[1];

        return $uri_tail;
    }

    public function getRangeDateString($timestamp, $dayago = 5)
    {
        if ($timestamp)
        {
            $currentTime = strtotime('today');
            $timestamp   = strtotime($timestamp);
            $days        = round(($timestamp - $currentTime) / 86400);

            switch($days) {
                case '-1';
                    return 'tegnap';
                    break;
                case '-2';
                    return 'tegnapelőtt';
                    break;
                case '0';
                    return 'ma';
                    break;
                case '1';
                    return 'holnap';
                    break;
                case '2';
                    return 'holnapután';
                    break;
                default:

                $date = new \DateTime();
                    if ($days > 0 && $days < $dayago) {
                        return $days .' nap múlva';
                    } else {
                        return strftime('%Y.%m.%d.', $timestamp);
                    }
                    break;
            }
        }
    }    
    ############## FUNCTIONS ##############

    // Add custom function
    public function number_afix ($szam)
    {
        $absz = abs($szam);
          
          switch ($absz % 10) {
            case 1:
            case 2:
            case 4:
            case 7:
            case 9:
               return "$szam-es";
            case 5:
               return "$szam-ös";
            case 6:
               return "$szam-os";
            case 8:
            case 3:
               return "$szam-as";
          }

          switch (($absz / 10) % 10) {
            case 1:
            case 4:
            case 5:
            case 7:
            case 9:
              return "-es";
            case 8:
            case 3:
            case 2:
            case 6:
                return "-as";
          }
          
          if ($absz == 0) {
            return "$szam-ás";
          }
          elseif (1000 <= $absz && $absz < 1000000) {
            return "$szam-es";
          }
          else {
            return "$szam-ós";
          }
      }

    ############## FUNCTIONS ##############

    // Add custom function
    public function set_element ($data, $key, $value)
    {
        // Assign value to $data[$key]
        if (!is_array($data)) {
            return $data;
        }
        $data[$key] = $value;

        return $data;
    }

    ############## FILTERS ##############

    // Add custom filter
    // timeago
    public function timeago ($datetime)
    {
        $time = time() - $datetime;

        $units = [86400 => 'napja',
                   3600 => 'órája',
                     60 => 'perce',
                      1 => 'másodperce'];

        foreach ($units as $unit => $val) 
        {
          if ($time < $unit) continue;
          $numberOfUnits = floor($time / $unit);

          return $numberOfUnits . ' ' . $val;
        }

        return ' kevesebb mint 1 másodperce';
    }

    public function secondsToTime($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds / 60) % 60);
        $seconds = $seconds % 60;

        return $hours > 0 ? "$hours órája ". ($minutes >= 1?$minutes." perce":" ") : ($minutes > 0 ? "$minutes perce" : "$seconds másodperce");
    }

    // userrole
    public function userrole ($perm)
    {
        return $this->role->__invoke($perm);
    }

    // user by uid
    public function userbyuid ($uid)
    {
        return $this->user->getUserByUID($uid);
    }

    public function userbyid ($id)
    {
        return $this->user->getUserByID($id);
    }


    // Lovi's custom functions

    // This method usually used to prevent file caching, by attaching the file modify time as past of the url, like: xy.css?123456789
    public function mtime ($file)
    {
        if (file_exists($file))
            return filemtime($file);

        return 0;
    }

}
