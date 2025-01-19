<?php 
/* FORMAT FUNCTIONS */
class ReportFunctions{

static function priceFormat($price) {
    return number_format($price, 0, ',', '.');
}

static function dateFormat($inputDateString) {
    try {
        $time = strtotime($inputDateString);
        return date('d-m-Y', $time);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit(1);
    }
}

static function strToDate($inputDateString) {
    try {
        $time = strtotime($inputDateString);
        return date('Y-m-d', $time);
    } catch (Exception $e) {
        echo $e->getMessage();
        exit(1);
    }
}
/* ... */

/* NUMBER TO WORD FUNCTION */
static function numberToWords($number) {
    $hyphen      = ' ';
    $conjunction = '  ';
    $separator   = ' ';
    $negative    = 'Âm ';
    $decimal     = ' phẩy ';
    $dictionary  = array(
        0                   => 'Không',
        1                   => 'Một',
        2                   => 'Hai',
        3                   => 'Ba',
        4                   => 'Bốn',
        5                   => 'Năm',
        6                   => 'Sáu',
        7                   => 'Bảy',
        8                   => 'Tám',
        9                   => 'Chín',
        10                  => 'Mười',
        11                  => 'Mười một',
        12                  => 'Mười hai',
        13                  => 'Mười ba',
        14                  => 'Mười bốn',
        15                  => 'Mười lăm',
        16                  => 'Mười sáu',
        17                  => 'Mười bảy',
        18                  => 'Mười tám',
        19                  => 'Mười chín',
        20                  => 'Hai mươi',
        30                  => 'Ba mươi',
        40                  => 'Bốn mươi',
        50                  => 'Năm mươi',
        60                  => 'Sáu mươi',
        70                  => 'Bảy mươi',
        80                  => 'Tám mươi',
        90                  => 'Chín mươi',
        100                 => 'trăm',
        1000                => 'nghìn',
        1000000             => 'triệu',
        1000000000          => 'tỷ',
        1000000000000       => 'nghìn tỷ',
        1000000000000000    => 'nghìn triệu triệu',
        1000000000000000000 => 'tỷ tỷ'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . self::numberToWords(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . self::numberToWords($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = self::numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];

            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
               $string .= self::numberToWords($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return ucfirst(strtolower($string));
}
/* ... */

/* DATE FUNCTIONS */
static function getCurrentDate() {
    return "ngày " . date('d') . " tháng " . date('m') . " năm " . date('Y');
}

static function getYear($date) {
    return date('Y', strtotime($date));
}

static function getMonth($date) {
    return date('m', strtotime($date));
}

static function lastDayOfMonth($date) {
    return date('t', strtotime($date));
}

// Get weeks and days of week in a month
static function getWeeks($date) {
    $day = 1;
    // Array to store weeks
    $weeks = [];
    // Get the last day of the month
    $lastDay = self::lastDayOfMonth($date);

    // Loop through the days of the month
    $week = 1;
    while ($day <= $lastDay) {
        $weeks[$week][] = self::strToDate(self::getYear($date) . '-' . self::getMonth($date) . '-' . $day);
        $day++;
        // If the day is Monday, increment the week
        if (date('w', strtotime(self::getYear($date) . '-' . self::getMonth($date) . '-' . $day)) == 1) {
            $week++;
        }
    }
    return $weeks;
}

// Get days per month in a year
static function getDaysOfMonth($year) {
    $months = [];
    for($i = 1; $i <= 12; $i++) {
        $months[$i] = [];
        $lastDay = self::lastDayOfMonth(date('Y') . '-' . $i . '-01');
        for($j = 1; $j <= $lastDay; $j++) {
            $months[$i][] = $year . '-' . $i . '-' . $j;
        }
    }
    return $months;
}
}
/* ... */
?>