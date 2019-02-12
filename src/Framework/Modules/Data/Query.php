<?php
namespace Framework\Modules\Data;

use Framework\Modules\DataType\Boolean;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Connection;


trait Query {


    protected $_aFields = [];

    protected $_aTables = [];

    protected $_aJoins = [];

    protected $_aWheres = [];

    protected $_aWheresAtEnd = [];

    protected $_aHaving = [];

    protected $_aOrderBy = [];

    protected $_aGroupBy = [];

    protected $_sLimit = NULL;

    protected $_sQuery = NULL;

    protected $_isSpecialQuery = FALSE;

    protected $_aSubQuery = [];

    protected $_aUnions = [];

    protected $_bAutoOrderByBasedOnGroupBy = TRUE;

    protected $_bDistinct = FALSE;

    /**
     * Returns the SQL query
     *
     * @return string
     */
    public function getSQL() {

        return $this->_buildQuery();

    }

    public function getAutoOrderBy() {
        return $this->_bAutoOrderByBasedOnGroupBy;
    }

    public function setAutoOrderBy($bAutoOrderByBasedOnGroupBy) {
        $this->_bAutoOrderByBasedOnGroupBy = $bAutoOrderByBasedOnGroupBy;
        return $this;
    }

    public function distinct() {
        $this->_bDistinct = TRUE;
        return $this;
    }

    public function union(Query $oQuery) {
        $this->_aUnions[] = $oQuery;
        return $this;
    }

    public function clearOrderBy() {
        $this->_aOrderBy = [];
        return $this;
    }

    public function clearGroupBy() {
        $this->_aGroupBy = [];
        return $this;
    }

    public function clearSelect() {
        $this->_aFields = [];
        return $this;
    }

    public function groupBy($sFieldName, $bOrderBy = TRUE) {
        $this->_aGroupBy[$sFieldName] = [
            "FieldName" => $sFieldName,
            "OrderBy" => $bOrderBy,
        ];
        return $this;
    }

    public function clearLimit() {
        $this->_sLimit = NULL;
    }

    public function limit($nStart, $nItems = NULL) {
        $this->_sLimit = $nStart;
        if ($nItems !== NULL) {
            $this->_sLimit .= "," . $nItems;
        }
        return $this;
    }

    public function fields() {
        return $this->_aFields;
    }

    /**
     * Indicates this is a select query
     *
     * @param string $aFields
     * @return \Framework\Modules\Data\DataQuery
     */
    public function select($aFields = "*") {

        if (is_array($aFields)) {
            foreach($aFields as $sField) {
                if (array_key_exists(md5($sField), $this->_aFields)) {
                    Log::debug("++++++ duplicate field " . $sField);
                }
            }
            $this->_aFields = array_merge($this->_aFields, $aFields);
        } else {
            $a = explode(",", $aFields);
            foreach($a as $sField) {
                if (array_key_exists(md5($sField), $this->_aFields)) {
                    Log::debug("++++++ duplicate field " . $sField);
                }
            }
            $this->_aFields = array_merge($this->_aFields, $a);
        }
        return $this;

    }

    /**
     * Select from table
     *
     * @param unknown $sTableName
     * @param string $sAlias
     * @return \Framework\Modules\Data\DataQuery
     */
    public function from($sTableName, $sAlias = NULL) {

        $this->_aTables[$sTableName . "_" . $sAlias] = array(
            "TableName" => $sTableName,
            "Alias" => $sAlias
        );
        return $this;

    }

    public function fromSubQuery($oQuery, $sAlias = NULL) {

        $this->_aSubQuery[] = ["Query" => $oQuery, "Alias" => $sAlias];
        return $this;
    }

    /**
     * Add where clause to query
     *
     * @param unknown $criteria
     * @return \Framework\Modules\Data\Query
     */
    public function where($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aWheres[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "AND"
        );

        return $this;

    }

    public function whereAtEnd($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aWheresAtEnd[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "AND"
        );

        return $this;

    }


    public function having($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aHaving[] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "AND"
        );

        return $this;

    }

    public function startWhereGroupAND() {

        $this->_aWheres[] = array(
            "OriginalCriteria" => "",
            "Criteria" => "",
            "LogicalOperator" => "AND",
            "StartGroup" => TRUE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    public function startWhereGroupOR() {

        $this->_aWheres[] = array(
            "OriginalCriteria" => "",
            "Criteria" => "",
            "LogicalOperator" => "OR",
            "StartGroup" => TRUE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    public function endWhereGroup() {

        $this->_aWheres[] = array(
            "OriginalCriteria" => "",
            "Criteria" => "",
            "LogicalOperator" => "OR",
            "StartGroup" => FALSE,
            "EndGroup" => TRUE
        );

        return $this;

    }

    /**
     * HAVING GROUPS
     */
    public function startHavingGroupAND() {

        $this->_aHaving[] = array(
            "OriginalCriteria" => "",
            "Criteria" => "",
            "LogicalOperator" => "AND",
            "StartGroup" => TRUE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    public function startHavingGroupOR() {

        $this->_aHaving[] = array(
            "OriginalCriteria" => "",
            "Criteria" => "",
            "LogicalOperator" => "OR",
            "StartGroup" => TRUE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    public function endHavingGroup() {

        $this->_aHaving[] = array(
            "OriginalCriteria" => "",
            "Criteria" => "",
            "LogicalOperator" => "OR",
            "StartGroup" => FALSE,
            "EndGroup" => TRUE
        );

        return $this;

    }



    /**
     * Add where clause using AND operator
     *
     * @param unknown $criteria
     * @return \Framework\Modules\Data\DataQuery
     */
    public function whereAND($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aWheres[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "AND",
            "StartGroup" => FALSE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    /**
     * Add where clause using AND operator
     *
     * @param unknown $criteria
     * @return \Framework\Modules\Data\DataQuery
     */
    public function havingAND($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aHaving[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "AND",
            "StartGroup" => FALSE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    /**
     * Add where clause using OR operator
     *
     * @param unknown $criteria
     * @return \Framework\Modules\Data\DataQuery
     */
    public function whereOR($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aWheres[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "OR",
            "StartGroup" => FALSE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    /**
     * Add where clause using OR operator
     *
     * @param unknown $criteria
     * @return \Framework\Modules\Data\DataQuery
     */
    public function whereORAtEnd($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aWheresAtEnd[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "OR",
            "StartGroup" => FALSE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    public function havingOR($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aHaving[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s,
            "LogicalOperator" => "OR",
            "StartGroup" => FALSE,
            "EndGroup" => FALSE
        );

        return $this;

    }

    /**
     * Add order by clause to query
     *
     * @param unknown $criteria
     * @return \Framework\Modules\Data\DataQuery
     */
    public function orderBy($criteria) {

        $s = $criteria;
        $index = 0;
        foreach (func_get_args() as $n) {
            // Skip criteria argument
            if ($index >= 1) {
                $s = str_replace("?", addslashes($n), $s);
            }
            $index ++;
        }

        $this->_aOrderBy[$s] = array(
            "OriginalCriteria" => $criteria,
            "Criteria" => $s
        );

        return $this;

    }

    public function getOrderBy() {
        return $this->_aOrderBy;
    }

    public function join($sTableName, $sOnClause) {

        $sJoinKey = md5($sTableName . $sOnClause);

        $this->_aJoins[$sJoinKey] = array(
            "Type" => "JOIN",
            "TableName" => $sTableName,
            "OnClause" => $sOnClause
        );
        return $this;

    }

    public function leftJoin($sTableName, $sOnClause) {

        if (array_has($this->_aJoins, $sTableName)) {
            throw new ExceptionBase("Duplicate join statement");
        }

        $this->_aJoins[$sTableName] = array(
            "Type" => "LEFT JOIN",
            "TableName" => $sTableName,
            "OnClause" => $sOnClause
        );
        return $this;

    }

    /**
     * Show table query
     *
     * @return \Framework\Modules\Data\DataQuery
     */
    public function showTables($sLike = NULL) {

        $this->_isSpecialQuery = TRUE;
        $this->_sQuery = "SHOW TABLES";
        if ($sLike !== NULL) {
            $this->_sQuery .= " LIKE '" . $sLike . "'";
        }
        return $this;

    }

    public function descTable($sTableName) {

        $this->_isSpecialQuery = TRUE;
        $this->_sQuery = "DESC " . $sTableName;

        return $this;

    }

    /**
     * @param bool $bWriteConnexion
     * @return mixed
     */
    public function execute($bWriteConnexion = FALSE) {

        if (!$this->_isSpecialQuery) {
            // Only build query if not a special query
            $this->_sQuery = $this->_buildQuery();

        }

        if ($bWriteConnexion) {
            $rst = DB::connection(config('database.default_write'))->select($this->_sQuery);
        } else {
            $rst = DB::connection(config('database.default_read'))->select($this->_sQuery);
        }
        return $rst;

    }


    /**
     * @param null $callback
     * @param int $nRecords
     * @param bool $bWriteConnexion
     */
    public function executeChunk($callback = NULL, $nRecords = 100, $bWriteConnexion = FALSE) {

        if (!$this->_isSpecialQuery) {
            // Only build query if not a special query
            $this->_sQuery = $this->_buildQuery();

        }

        if ($bWriteConnexion) {
            DB::connection(config('database.default_write'))->select($this->_sQuery)->chunk($nRecords, $callback);
        } else {
            DB::connection(config('database.default_read'))->select($this->_sQuery)->chunk($nRecords, $callback);
        }

    }



    /**
     * Builds the query
     *
     * @return string
     */
    protected function _buildQuery() {


        $s = "SELECT ";

        if ($this->_bDistinct) {
            $s .= "DISTINCT ";
        }

        $bHasSelectList = FALSE;
        $bFirst = TRUE;
        foreach ($this->_aFields as $k => $f) {
            if (! $bFirst) {
                $s .= ",";
            }
            $s .= $f;
            $bFirst = false;
            $bHasSelectList = TRUE;
        }

        if (count($this->_aFields) == 0 && count($this->_aSubQuery) == 0) {
            $s .= " * ";
            $bHasSelectList = TRUE;
        }
        if (count($this->_aTables) > 0) {
            $s .= " FROM ";
            $bFirst = TRUE;
            foreach ($this->_aTables as $k => $t) {
                if (! $bFirst) {
                    $s .= ",";
                }
                $s .= $t["TableName"];
                if ($t["Alias"] != "") {
                    $s .= " AS " . $t["Alias"];
                }
                $bFirst = false;
            }
        }

        foreach($this->_aSubQuery as $aSubQuery) {
            if (count($this->_aFields) == 0) {
                $s .= " " . $aSubQuery["Alias"] . ".* ";
            }
            $s .= " FROM ";
            $s .= "(" . $aSubQuery["Query"]->getSQL() . ") AS " . $aSubQuery["Alias"];
        }

        // Joins
        foreach ($this->_aJoins as $k => $j) {
            $s .= " " . $j["Type"] . " " . $j["TableName"];
            if ($j["OnClause"] != "") {
                $s .= " ON " . $j["OnClause"] . " ";
            }
        }

        // WHERE
        $where = "";
        $bFirst = true;
        foreach ($this->_aWheres as $k => $w) {

            if (array_key_exists("StartGroup", $w) && $w["StartGroup"]) {

                if (! $bFirst) {
                    $where .= " " . $w["LogicalOperator"] . " ";
                }

                $where .= " ( ";
                $bFirst = true;
            } elseif (array_key_exists("EndGroup", $w) && $w["EndGroup"]) {
                $where .= " ) ";
                $bFirst = false;
            } else {

                if (! $bFirst) {
                    $where .= " " . $w["LogicalOperator"] . " ";
                }

                $where .= $w["Criteria"];
                $bFirst = false;
            }


        }

        $bFirst = true;
        if ($where != "") {
            $s .= " WHERE (" . $where . ") ";
            $bFirst = false;
        }

        $whereAtEnd = "";

        foreach ($this->_aWheresAtEnd as $k => $w) {

            if (array_key_exists("StartGroup", $w) && $w["StartGroup"]) {

                if (! $bFirst) {
                    $whereAtEnd .= " " . $w["LogicalOperator"] . " ";
                }

                $whereAtEnd .= " ( ";
                $bFirst = true;
            } elseif (array_key_exists("EndGroup", $w) && $w["EndGroup"]) {
                $whereAtEnd .= " ) ";
                $bFirst = false;
            } else {

                if (! $bFirst) {
                    $whereAtEnd .= " " . $w["LogicalOperator"] . " ";
                }

                $whereAtEnd .= $w["Criteria"];
                $bFirst = false;
            }


        }

        if ($where != "" && $whereAtEnd != "") {
            $s .= " " . $whereAtEnd . " ";
        } elseif ($whereAtEnd != "") {
            $s .= " WHERE " . $whereAtEnd . " ";
        }



        /**
         * BUILD HAVING PORTION OF QUERY
         */
        $having = "";
        $bFirst = true;
        foreach ($this->_aHaving as $k => $w) {

            if (array_key_exists("StartGroup", $w) && $w["StartGroup"]) {

                if (! $bFirst) {
                    $having .= " " . $w["LogicalOperator"] . " ";
                }

                $having .= " ( ";
                $bFirst = true;
            } elseif (array_key_exists("EndGroup", $w) && $w["EndGroup"]) {
                $having .= " ) ";
                $bFirst = false;
            } else {

                if (! $bFirst) {
                    $having .= " " . $w["LogicalOperator"] . " ";
                }

                $having .= $w["Criteria"];
                $bFirst = false;
            }


        }

        if ($having != "") {
            $s .= " HAVING " . $having;
        }


        // Group by
        $groupBy = "";
        $bFirst = true;
        foreach ($this->_aGroupBy as $k => $w) {

            if (! $bFirst) {
                $groupBy .= " , ";
            }
            $groupBy .= $w["FieldName"];

            $bFirst = false;
        }

        if ($groupBy != "") {
            $s .= " GROUP BY " . $groupBy;
        }

        // Order by
        $orderBy = "";
        $bFirst = true;
        $aOrderItems = [];
        /**
         * Automaticly order by data based on group by
         */
        if ($this->getAutoOrderBy()) {
            foreach ($this->_aGroupBy as $k => $w) {

                if (is_string($w) || is_numeric($w)) {
                    if (!array_key_exists($w, $aOrderItems) && Boolean::toBool($w["OrderBy"])) {

                        if (! $bFirst) {
                            $orderBy .= " , ";
                        }

                        $orderBy .= $w["FieldName"];
                        $aOrderItems[$w] = $w["FieldName"];

                        $bFirst = FALSE;

                    }
                }

            }
        }
        /**
         * Add any additional order by
         */
        foreach ($this->_aOrderBy as $k => $w) {

            if (!array_key_exists($w["Criteria"], $aOrderItems)) {
                if (! $bFirst) {
                    $orderBy .= " , ";
                }
                if (is_array($w["Criteria"])) {
                    if (array_get($w["Criteria"], "mapping") != "") {
                        $orderBy .= array_get($w["Criteria"], "mapping");
                        if (array_get($w["Criteria"], "sortOrder") != "") {
                            $orderBy .= " " . array_get($w["Criteria"], "sortOrder");
                        }
                    }
                } else {
                    $orderBy .= $w["Criteria"];
                }


                $bFirst = FALSE;
            }

        }

        foreach($this->_aUnions as $oQuery) {
            $s .= " UNION ALL ";
            $oQuery->clearOrderBy();
            $oQuery->clearLimit();
            $s .= $oQuery->getSQL();
        }

        if ($orderBy != "") {
            $s .= " ORDER BY " . $orderBy;
        }

        if ($this->_sLimit !== NULL) {
            $s .= " LIMIT " . $this->_sLimit;
        }

        return $s;

    }

    public function escapeSpecialChars($s) {


        $chars = [
            "e" => "[eéèêëEÉÈÊË]",				"é" => "[eéèêëEÉÈÊË]",				"è" => "[eéèêëEÉÈÊË]",				"ê" => "[eéèêëEÉÈÊË]",				"ë" => "[eéèêëEÉÈÊË]",
            "E" => "[eéèêëEÉÈÊË]",				"É" => "[eéèêëEÉÈÊË]",				"È" => "[eéèêëEÉÈÊË]",				"Ê" => "[eéèêëEÉÈÊË]",				"Ë" => "[eéèêëEÉÈÊË]",
            "a" => "[aàâäAÀÂÂ]",                "à" => "[aàâäAÀÂÂ]",                "â" => "[aàâäAÀÂÂ]",                "ä" => "[aàâäAÀÂÂ]",                "A" => "[aàâäAÀÂÂ]",
            "À" => "[aàâäAÀÂÂ]",                "Â" => "[aàâäAÀÂÂ]",                "Ä" => "[aàâäAÀÂÂ]",                "i" => "[iìîïIÌÎÏ]",                "ì" => "[iìîïIÌÎÏ]",
            "î" => "[iìîïIÌÎÏ]",                "ï" => "[iìîïIÌÎÏ]",                "I" => "[iìîïIÌÎÏ]",                "Ì" => "[iìîïIÌÎÏ]",                "Î" => "[iìîïIÌÎÏ]",
            "Ï" => "[iìîïIÌÎÏ]",
            "u" => "[uùûüUÙÛÜ]",                "ù" => "[uùûüUÙÛÜ]",                "û" => "[uùûüUÙÛÜ]",                "ü" => "[uùûüUÙÛÜ]",                "U" => "[uùûüUÙÛÜ]",
            "Ù" => "[uùûüUÙÛÜ]",                "Û" => "[uùûüUÙÛÜ]",                "Ü" => "[uùûüUÙÛÜ]",
            "c" => "[cçCÇ]",                "ç" => "[cçCÇ]",                "C" => "[cçCÇ]",                "Ç" => "[cçCÇ]",
            "o" => "[oòôöOÒÔÖ]",                "ò" => "[oòôöOÒÔÖ]",                "ô" => "[oòôöOÒÔÖ]",                "ö" => "[oòôöOÒÔÖ]",                "O" => "[oòôöOÒÔÖ]",
            "Ò" => "[oòôöOÒÔÖ]",                 "Ô" => "[oòôöOÒÔÖ]",                "Ö" => "[oòôöOÒÔÖ]",
            "-" => "[ -]",
        ];

        $s = strtr($s, $chars);

        if (starts_with($s, "*") && !ends_with($s, "*")) {
            $s = str_replace_mb("*", "", $s);
            $s = $s . "\$";
        } elseif(!starts_with($s, "*") && ends_with($s, "*")) {
            $s = str_replace_mb("*", "", $s);
            $s = "^" . $s;
        } elseif(!starts_with($s, "*") && !ends_with($s, "*")) {
            $s = "^" . $s . "\$";
        }
        $s = str_replace_mb("*", "", $s);


        //

        return $s;

    }


}

?>