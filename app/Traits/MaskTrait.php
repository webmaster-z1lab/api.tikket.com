<?php
/**
 * Created by Olimar Ferraz
 * webmaster@z1lab.com.br
 * Date: 11/07/2018
 * Time: 15:41
 */

namespace App\Traits;


trait MaskTrait
{
    /**
     * @param        $val
     * @param string $mask
     *
     * @return string
     */
    public function mask($val, string $mask): string
    {
        $maskared = '';

        $k = 0;

        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[ $i ] == '#') {
                if (isset($val[ $k ]))
                    $maskared .= $val[ $k++ ];
            } else {
                if (isset($mask[ $i ]))
                    $maskared .= $mask[ $i ];
            }
        }

        return $maskared;
    }
}
