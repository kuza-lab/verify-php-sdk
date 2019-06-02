<?php
/**
 * Copyright (c) 2019 Verify Technologies Ltd.
 *
 * @author Phelix Juma <jumaphelix@kuzalab.com>
 */

namespace Kuza\Verify\Helpers;


class DataHelper {

    /**
     * Function to search array data for a specific value by the provided key
     * Returns the found data
     *
     * @param array $arrayData
     * @param string $searchKey
     * @param string $searchValue
     *
     * @return mixed
     */
    public static function searchMultiArrayByKey($arrayData, $searchKey, $searchValue) {

        $foundData = array();
        $size = sizeof($arrayData);
        for ($i = 0; $i < $size; $i++) {
            if ($arrayData[$i][$searchKey] == $searchValue) {
                $foundData[] = $arrayData[$i];
            }
        }
        return $foundData;
    }

    /**
     * Function to search array data for a specific value by the provided key
     * Returns the found object
     *
     * @param array $arrayData
     * @param string $searchKey
     * @param string $searchValue
     *
     * @return array|boolean
     */
    public static function searchMultiArrayByKeyReturnObject($arrayData, $searchKey, $searchValue) {

        $size = is_array($arrayData) ? sizeof($arrayData) : 0;
        for ($i = 0; $i < $size; $i++) {
            if (strtolower($arrayData[$i][$searchKey]) == strtolower($searchValue)) {
                return $arrayData[$i];
            }
        }
        return false;
    }

}