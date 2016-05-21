<?php
/*
* A strpos variant that accepts an array of $needles - or just a string,
* so that it can be used as a drop-in replacement for the standard strpos,
* and in which case it simply wraps around strpos and stripos so as not
* to reduce performance.
*
* The "m" in "strposm" indicates that it accepts *m*ultiple needles.
*
* Finds the earliest match of *all* needles. Returns the position of this match
* or false if none found, as does the standard strpos. Optionally also returns
* via $match either the matching needle as a string (by default) or the index
* into $needles of the matching needle (if the STRPOSM_MATCH_AS_INDEX flag is
* set).
*
* Case-insensitive searching can be specified via the STRPOSM_CI flag.
* Note that for case-insensitive searches, if the STRPOSM_MATCH_AS_INDEX is
* not set, then $match will be in the haystack's case, not the needle's case,
* unless the STRPOSM_NC flag is also set.
*
* Flags can be combined using the bitwise or operator,
* e.g. $flags = STRPOSM_CI|STRPOSM_NC
*/
define('STRPOSM_CI'            , 1); // CI => "case insensitive".
define('STRPOSM_NC'            , 2); // NC => "needle case".
define('STRPOSM_MATCH_AS_INDEX', 4);
function strposm($haystack, $needles, $offset = 0, &$match = null, $flags = 0) {
    // In the special case where $needles is not an array, simply wrap
    // strpos and stripos for performance reasons.
    if (!is_array($needles)) {
        $func = $flags & STRPOSM_CI ? 'stripos' : 'strpos';
        $pos = $func($haystack, $needles, $offset);
        if ($pos !== false) {
            $match = (($flags & STRPOSM_MATCH_AS_INDEX)
                      ? 0
                      : (($flags & STRPOSM_NC)
                         ? $needles
                         : substr($haystack, $pos, strlen($needles))
                        )
                      );
            return $pos;
        } else    goto strposm_no_match;
    }

    // $needles is an array. Proceed appropriately, initially by...
    // ...escaping regular expression meta characters in the needles.
    $needles_esc = array_map('preg_quote', $needles);
    // If either of the "needle case" or "match as index" flags are set,
    // then create a sub-match for each escaped needle by enclosing it in
    // parentheses. We use these later to find the index of the matching
    // needle.
    if (($flags & STRPOSM_NC) || ($flags & STRPOSM_MATCH_AS_INDEX)) {
        $needles_esc = array_map(
            function($needle) {return '('.$needle.')';},
            $needles_esc
        );
    }
    // Create the regular expression pattern to search for all needles.
    $pattern = '('.implode('|', $needles_esc).')';
    // If the "case insensitive" flag is set, then modify the regular
    // expression with "i", meaning that the match is "caseless".
    if ($flags & STRPOSM_CI) $pattern .= 'i';
    // Find the first match, including its offset.
    if (preg_match($pattern, $haystack, $matches, PREG_OFFSET_CAPTURE, $offset)) {
        // Pull the first entry, the overall match, out of the matches array.
        $found = array_shift($matches);
        // If we need the index of the matching needle, then...
        if (($flags & STRPOSM_NC) || ($flags & STRPOSM_MATCH_AS_INDEX)) {
            // ...find the index of the sub-match that is identical
            // to the overall match that we just pulled out.
            // Because sub-matches are in the same order as needles,
            // this is also the index into $needles of the matching
            // needle.
            $index = array_search($found, $matches);
        }
        // If the "match as index" flag is set, then return in $match
        // the matching needle's index, otherwise...
        $match = (($flags & STRPOSM_MATCH_AS_INDEX)
          ? $index
          // ...if the "needle case" flag is set, then index into
          // $needles using the previously-determined index to return
          // in $match the matching needle in needle case, otherwise...
          : (($flags & STRPOSM_NC)
             ? $needles[$index]
             // ...by default, return in $match the matching needle in
             // haystack case.
             : $found[0]
          )
        );
        // Return the captured offset.
        return $found[1];
    }

strposm_no_match:
    // Nothing matched. Set appropriate return values.
    $match = ($flags & STRPOSM_MATCH_AS_INDEX) ? false : null;
    return false;
}