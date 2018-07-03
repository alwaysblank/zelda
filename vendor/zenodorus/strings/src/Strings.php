<?php namespace Zenodorus;

class Strings
{
    const CLEAN = "/[^[:alnum:]_]/u";
    const SAFE = "/^\w+$/";

  /**
   * Determine if a string contains any "bad" characters.
   *
   * It works by checking for the presence of ONLY "good"
   * characters; not the presense of "bad" characters,
   * which should make it more difficult to trick.
   *
   * @param string $string    The string to check.
   * @param string $regex     (optional) Regex defining "safe" characters.
   * @return string|bool      Return $string if save, bool false if not.
   */
    public static function safe(string $string, string $regex = self::SAFE)
    {
        if (preg_match($regex, $string)) :
            return $string;
        else :
            return false;
        endif;
    }

  /**
   * Clean a string by removing any characters we don't want.
   *
   * @param string $string        The string we're cleaning.
   * @param string $replace       What to replace "bad" characters with.
   * @param string $regex         (optional) The regex determing "good" characters.
   * @return string
   */
    public static function clean(string $string, string $replace = "", string $regex = self::CLEAN)
    {
        if ($replace === null) :
            $replace = "";
        endif;
        if ($regex === null) :
            $regex = self::CLEAN;
        endif;
    
        return preg_replace($regex, $replace, $string);
    }

  /**
   * Replace the first instance of a string in another string,
   * if it exists.
   *
   * @param string $search        The string being to be replaced.
   * @param string $replace       The string to replace $search with.
   * @param string $subject       The string that is being searched for $search.
   * @return string               If $search doesn't exist, this returns $subject unmodified.
   */
    public static function replaceFirst(string $search, string $replace, string $subject)
    {
        $pos = strpos($subject, $search);
        if ($pos !== false) {
            return substr_replace($subject, $replace, $pos, strlen($search));
        }
        return $subject;
    }

  /**
   * Concatenates a string to another string, if it isn't already
   * a substring of it.
   *
   * @param string $add               The string to be added.
   * @param string $existing          The existing string.
   * @param string $concatenateWith   (option) String to concatenate with. Defaults to ' '.
   * @return string                   Returns concatenated string (if $add isn't a substring)
   *                                  and original string (if $add is a substring).
   */
    public static function addNew(string $add, string $existing, string $concatenateWith = ' ')
    {
        if (!strpos($existing, $add)) :
            return sprintf("%s%s%s", $existing, $concatenateWith, $add);
        endif;

        return $existing;
    }
}
