<?php

namespace App\Libraries\Validations;

class DateRules
{
    /**
     * rule validasi untuk string tanggal
     * 2010-10-23 > 2010-02-12
     */
    public function date_ymd_greater_than_equal_to(string $str, string $field, array $data)
    {
        $date_ours = $str;
        $date_theirs = $data[$field];

        return strtotime($date_ours) >= strtotime($date_theirs);
    }
}
