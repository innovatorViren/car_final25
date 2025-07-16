<?php

namespace App\Models;

use Sentinel;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of YearWiseTrait
 *
 * @author Dalsukh Parmar <parmar.dalsukh@gmail.com>
 */
trait YearWiseTrait {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootYearWiseTrait() {
        if (Sentinel::getUser()) {
            static::addGlobalScope(new YearWiseScope);
        }
    }

    /**
     * Get the name of the column for applying the scope.
     *
     * @return string
     */
    public function getYearIdColumn() {
        return defined('static::YEAR_ID_COLUMN') ? static::YEAR_ID_COLUMN : 'year_id';
    }

    /**
     * Get the fully qualified column name for applying the scope.
     *
     * @return string
     */
    // public function getQualifiedPublishedColumn() {
    //     return $this->getTable() . '.' . $this->getYearIdColumn();
    // }
}
