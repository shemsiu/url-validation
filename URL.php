<?php
/**
 * Vigan Shemsiu
 * mail@shemsiu.com
 * 2015-10-20
 */

namespace Shemsiu;

/**
 * Class URL (Validation Class)
 * @package Shemsiu
 */
class URL
{
    protected $url; //user input url
    protected $validate_https; // true || false
    protected $validate_www; // true || false
    protected $validate_top_level_domain; // true || false
    public static $error_message = []; // String collection for all error messages

    /**
     * @param $url
     * @param bool|false $validate_https
     * @param bool|false $validate_www
     * @param bool|false $validate_top_level_domain
     */
    public function __construct($url, $validate_https = false, $validate_www = true, $validate_top_level_domain = true)
    {
        //We only test the scheme and host and exclude the url parameters.

        $arr = parse_url($url);
        $this->url = $arr['scheme'] . '://' . $arr['host'];
        $this->validate_https = $validate_https;
        $this->validate_www = $validate_www;
        $this->validate_top_level_domain = $validate_top_level_domain;
    }

    /**
     * Our public run method that validate the url.
     * @return bool
     */
    public function validate()
    {
        if (!empty($this->url) && strlen($this->url) >= 12) {
            $this->validate_https();
            $this->validate_www();
            $this->validate_top_level_domain();

            if (empty($this->getErrorMessages())) {
                return true;
            } else {
                return false;
            }
        } else {
            $this->addErrorMessage("Your URL is not valid! Please check that you use correct syntax: http://www.your-url.com", "URL");
            return false;
        }
    }

    /**
     * If true: Check if www is missing in the url.
     * If false: Skip this validation.
     * @return bool
     */
    private function validate_www()
    {
        if ($this->validate_www) {
            $url_arr = explode(".", $this->url);

            if ($this->validate_https) {
                $_http = "https";
            } else {
                $u = explode("://", $this->url);

                if (in_array("https", $u)) {
                    $_http = "https";
                } else {
                    $_http = "http";
                }
            }

            $re = "/(?<=$_http:\\/\\/)(.*)(?=." . end($url_arr) . ")/i";
            preg_match($re, $this->url, $matches);

            if (!empty($matches)) {
                $matches = explode('.', $matches[0]);

                if (in_array("www", $matches)) {
                    //return true;
                } else {
                    $this->addErrorMessage("You are missing www", "WWW");
                    //return false;
                }
            } else {
                $arr = explode("://", $this->url);

                if ($arr[0] == 'http') {
                    $this->addErrorMessage("You have activated validation for https:// and you're missing it!", "WWW");
                } else {
                    if (empty($arr[2])) {
                        $this->addErrorMessage("Your top level domain is empty!", "Top Level Domain");
                    }
                }
                //return false;
            }

        } else {
            //return true;
        }
    }

    /**
     * If true: Check if https is missing in the url.
     * If false: Skip this validation.
     * @return bool
     */
    private function validate_https()
    {
        $arr = explode('://', $this->url);
        $arr[1] = 'destroy';

        if ($this->validate_https) {
            if (in_array('https', $arr)) {
                //https is correctly typed
                //return true;
            } else {
                $this->addErrorMessage("We couldn't find https://", "HTTPS");
                //return false;
            }
        } else {
            //The user doesn't want to check if https:// exists and then we do nothing.
            //return true;
        }
    }

    /**
     * If true: Check wether the top level domain is correct or not.
     * If false: Skip this validation.
     * @return bool
     */
    private function validate_top_level_domain()
    {
        if ($this->validate_top_level_domain) {

            $arr = explode(".", $this->url);

            $domains = array(".aero", ".biz", ".cat", ".com", ".coop", ".edu", ".gov", ".info", ".int", ".jobs", ".mil", ".mobi", ".museum",
                ".name", ".net", ".org", ".travel", ".ac", ".ad", ".ae", ".af", ".ag", ".ai", ".al", ".am", ".an", ".ao", ".aq", ".ar", ".as", ".at", ".au", ".aw",
                ".az", ".ba", ".bb", ".bd", ".be", ".bf", ".bg", ".bh", ".bi", ".bj", ".bm", ".bn", ".bo", ".br", ".bs", ".bt", ".bv", ".bw", ".by", ".bz", ".ca",
                ".cc", ".cd", ".cf", ".cg", ".ch", ".ci", ".ck", ".cl", ".cm", ".cn", ".co", ".cr", ".cs", ".cu", ".cv", ".cx", ".cy", ".cz", ".de", ".dj", ".dk", ".dm",
                ".do", ".dz", ".ec", ".ee", ".eg", ".eh", ".er", ".es", ".et", ".eu", ".fi", ".fj", ".fk", ".fm", ".fo", ".fr", ".ga", ".gb", ".gd", ".ge", ".gf", ".gg", ".gh",
                ".gi", ".gl", ".gm", ".gn", ".gp", ".gq", ".gr", ".gs", ".gt", ".gu", ".gw", ".gy", ".hk", ".hm", ".hn", ".hr", ".ht", ".hu", ".id", ".ie", ".il", ".im",
                ".in", ".io", ".iq", ".ir", ".is", ".it", ".je", ".jm", ".jo", ".jp", ".ke", ".kg", ".kh", ".ki", ".km", ".kn", ".kp", ".kr", ".kw", ".ky", ".kz", ".la", ".lb",
                ".lc", ".li", ".lk", ".lr", ".ls", ".lt", ".lu", ".lv", ".ly", ".ma", ".mc", ".md", ".mg", ".mh", ".mk", ".ml", ".mm", ".mn", ".mo", ".mp", ".mq",
                ".mr", ".ms", ".mt", ".mu", ".mv", ".mw", ".mx", ".my", ".mz", ".na", ".nc", ".ne", ".nf", ".ng", ".ni", ".nl", ".no", ".np", ".nr", ".nu",
                ".nz", ".om", ".pa", ".pe", ".pf", ".pg", ".ph", ".pk", ".pl", ".pm", ".pn", ".pr", ".ps", ".pt", ".pw", ".py", ".qa", ".re", ".ro", ".ru", ".rw",
                ".sa", ".sb", ".sc", ".sd", ".se", ".sg", ".sh", ".si", ".sj", ".sk", ".sl", ".sm", ".sn", ".so", ".sr", ".st", ".su", ".sv", ".sy", ".sz", ".tc", ".td", ".tf",
                ".tg", ".th", ".tj", ".tk", ".tm", ".tn", ".to", ".tp", ".tr", ".tt", ".tv", ".tw", ".tz", ".ua", ".ug", ".uk", ".um", ".us", ".uy", ".uz", ".va", ".vc",
                ".ve", ".vg", ".vi", ".vn", ".vu", ".wf", ".ws", ".ye", ".yt", ".yu", ".za", ".zm", ".zr", ".zw");

            if (in_array("." . end($arr), $domains)) {
                //return true;
            } else {
                $this->addErrorMessage("Your top level domain (.".end($arr).") isn't official and we do not accept it!", "Top Level Domain");
                //return false;
            }
        } else {
            //return true;
        }
    }

    /**
     * Add short message to the string collection of errors
     * @param $message
     * @param $category
     */
    private function addErrorMessage($message, $category)
    {
        self::$error_message[] = "<strong> $category </strong>: $message <br />";
    }

    /**
     * Returning the string collection of errors
     * @return string
     */
    public function getErrorMessages()
    {
        return self::$error_message;
    }
}