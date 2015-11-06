<?php
/**
 * Created by PhpStorm.
 * User: sharif
 * Date: 10/18/2015
 * Time: 11:15 PM
 */
namespace PHPParser\Parser;

use PHPParser\Tools\Tools;
use PHPParser\Tools\String;
use PHPParser\Lexer\Lexer;

class Object{

    public    $type;
    protected $sql;
    protected $functions     = "ABS|ACOS|ADDDATE|ADDTIME|AES_DECRYPT|AES_ENCRYPT|ANY_VALUE|Area|AsBinary|AsWKB|ASCII|ASIN|AsText|AsWKT|ATAN2|ATAN|ATAN|AVG|BENCHMARK|BIN|BINARY|BIT_AND|BIT_COUNT|BIT_LENGTH|BIT_OR|BIT_XOR|Buffer|CASE|CAST|CEIL|CEILING|Centroid|CHAR_LENGTH|CHAR|CHARACTER_LENGTH|CHARSET|COALESCE|COERCIBILITY|COLLATION|COMPRESS|CONCAT_WS|CONCAT|CONNECTION_ID|Contains|CONV|CONVERT_TZ|CONVERT|ConvexHull|COS|COT|COUNT|CRC32|Crosses|CURDATE|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURTIME|DATABASE|DATE_ADD|DATE_FORMAT|DATE_SUB|DATE|DATEDIFF|DAY|DAYNAME|DAYOFMONTH|DAYOFWEEK|DAYOFYEAR|DECODE|DEFAULT|DEGREES|DES_DECRYPT|DES_ENCRYPT|Dimension|Disjoint|Distance|DIV|ELT|ENCODE|ENCRYPT|EndPoint|Envelope|Equals|EXP|EXPORT_SET|ExteriorRing|EXTRACT|ExtractValue|FIELD|FIND_IN_SET|FLOOR|FORMAT|FOUND_ROWS|FROM_BASE64|FROM_DAYS|FROM_UNIXTIME|GeomCollFromText|GeometryCollectionFromText|GeomCollFromWKB|GeometryCollectionFromWKB|GeometryCollection|GeometryN|GeometryType|GeomFromText|GeometryFromText|GeomFromWKB|GET_FORMAT|GET_LOCK|GLength|GREATEST|GROUP_CONCAT|GTID_SUBSET|GTID_SUBTRACT|HEX|HOUR|IF|IFNULL|IN|INET_ATON|INET_NTOA|INET6_ATON|INET6_NTOA|INSERT|INSTR|InteriorRingN|Intersects|INTERVAL|IS_FREE_LOCK|IS_IPV4_COMPAT|IS_IPV4_MAPPED|IS_IPV4|IS_IPV6|IS NOT NULL|IS NOT|IS NULL|IS_USED_LOCK|IS|IsClosed|IsEmpty|ISNULL|IsSimple|LAST_DAY|LAST_INSERT_ID|LCASE|LEAST|LEFT|LENGTH|LIKE|LineFromText|LineFromWKB|LineStringFromWKB|LineString|LN|LOAD_FILE|LOCALTIME|LOCALTIMESTAMP|LOCATE|LOG10|LOG2|LOG|LOWER|LPAD|LTRIM|MAKE_SET|MAKEDATE|MAKETIME|MASTER_POS_WAIT|MATCH|MAX|MBRContains|MBRCoveredBy|MBRCovers|MBRDisjoint|MBREqual|MBREquals|MBRIntersects|MBROverlaps|MBRTouches|MBRWithin|MD5|MICROSECOND|MID|MIN|MINUTE|MLineFromText|MLineFromWKB|MOD|MONTH|MONTHNAME|MPointFromText|MultiPointFromText|MPointFromWKB|MultiPointFromWKB|MPolyFromText|MultiPolygonFromText|MPolyFromWKB|MultiPolygonFromWKB|MultiLineString|MultiPoint|MultiPolygon|NAME_CONST|NOW|NULLIF|NumGeometries|NumInteriorRings|NumPoints|OCT|OCTET_LENGTH|OLD_PASSWORD|ORD|Overlaps|PASSWORD|PERIOD_ADD|PERIOD_DIFF|PI|Point|PointFromText|PointFromWKB|PointN|PolyFromText|PolygonFromText|PolyFromWKB|PolygonFromWKB|Polygon|POSITION|POW|POWER|PROCEDURE|QUARTER|QUOTE|RADIANS|RAND|RANDOM_BYTES|REGEXP|RELEASE_ALL_LOCKS|RELEASE_LOCK|REPEAT|REPLACE|REVERSE|RIGHT|RLIKE|ROUND|ROW_COUNT|RPAD|RTRIM|SCHEMA|SEC_TO_TIME|SECOND|SESSION_USER|SHA1|SHA|SHA2|SIGN|SIN|SLEEP|SOUNDEX|SOUNDS LIKE|SPACE|SQRT|SRID|ST_Area|ST_AsGeoJSON|ST_Centroid|ST_Contains|ST_ConvexHull|ST_Crosses|ST_Difference|ST_Disjoint|ST_Distance_Sphere|ST_Distance|ST_Envelope|ST_Equals|ST_GeoHash|ST_GeomFromGeoJSON|ST_Intersection|ST_Intersects|ST_IsValid|ST_LatFromGeoHash|ST_Length|ST_LongFromGeoHash|ST_MakeEnvelope|ST_Overlaps|ST_PointFromGeoHash|ST_Simplify|ST_SymDifference|ST_Touches|ST_Union|ST_Validate|ST_Within|StartPoint|STD|STDDEV_POP|STDDEV_SAMP|STDDEV|STR_TO_DATE|STRCMP|SUBDATE|SUBSTR|SUBSTRING_INDEX|SUBSTRING|SUBTIME|SUM|SYSDATE|SYSTEM_USER|TAN|TIME_FORMAT|TIME_TO_SEC|TIME|TIMEDIFF|TIMESTAMP|TIMESTAMPADD|TIMESTAMPDIFF|TO_BASE64|TO_DAYS|TO_SECONDS|Touches|TRIM|TRUNCATE|UCASE|UNCOMPRESS|UNCOMPRESSED_LENGTH|UNHEX|UNIX_TIMESTAMP|UpdateXML|UPPER|USER|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|UUID_SHORT|UUID|VALIDATE_PASSWORD_STRENGTH|VALUES|VAR_POP|VAR_SAMP|VARIANCE|VERSION|WAIT_FOR_EXECUTED_GTID_SET|WAIT_UNTIL_SQL_THREAD_AFTER_GTIDS|WEEK|WEEKDAY|WEEKOFYEAR|WEIGHT_STRING|Within|X|XOR|Y|YEAR|YEARWEEK";
    protected $reservedExtra = "ENGINE|CHARSET|AUTO_INCREMENT|DUPLICATE";
    protected $reserved      = "ACCESSIBLE|ADD|ALL|ALTER|ANALYZE|AND|AS|ASC|ASENSITIVE|BEFORE|BETWEEN|BIGINT|BINARY|BLOB|BOTH|BY|CALL|CASCADE|CASE|CHANGE|CHAR|CHARACTER|CHECK|COLLATE|COLUMN|CONDITION|CONSTRAINT|CONTINUE|CONVERT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURSOR|DATABASE|DATABASES|DAY_HOUR|DAY_MICROSECOND|DAY_MINUTE|DAY_SECOND|DEC|DECIMAL|DECLARE|DEFAULT|DELAYED|DELETE|DESC|DESCRIBE|DETERMINISTIC|DISTINCT|DISTINCTROW|DIV|DOUBLE|DROP|DUAL|EACH|ELSE|ELSEIF|ENCLOSED|ESCAPED|EXISTS|EXIT|EXPLAIN|FALSE|FETCH|FLOAT|FLOAT4|FLOAT8|FOR|FORCE|FOREIGN|FROM|FULLTEXT|GET|GRANT|GROUP|HAVING|HIGH_PRIORITY|HOUR_MICROSECOND|HOUR_MINUTE|HOUR_SECOND|IF|IGNORE|IN|INDEX|INFILE|INNER|INOUT|INSENSITIVE|INSERT|INT|INT1|INT2|INT3|INT4|INT8|INTEGER|INTERVAL|INTO|IO_AFTER_GTIDS|IO_BEFORE_GTIDS|IS|ITERATE|JOIN|KEY|KEYS|KILL|LEADING|LEAVE|LEFT|LIKE|LIMIT|LINEAR|LINES|LOAD|LOCALTIME|LOCALTIMESTAMP|LOCK|LONG|LONGBLOB|LONGTEXT|LOOP|LOW_PRIORITY|MASTER_BIND|MASTER_SSL_VERIFY_SERVER_CERT|MATCH|MAXVALUE|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MIDDLEINT|MINUTE_MICROSECOND|MINUTE_SECOND|MOD|MODIFIES|NATURAL|NOT|NO_WRITE_TO_BINLOG|NULL|NUMERIC|ON|OPTIMIZE|OPTIMIZER_COSTS|OPTION|OPTIONALLY|OR|ORDER|OUT|OUTER|OUTFILE|PARTITION|PRECISION|PRIMARY|PROCEDURE|PURGE|RANGE|READ|READS|READ_WRITE|REAL|REFERENCES|REGEXP|RELEASE|RENAME|REPEAT|REPLACE|REQUIRE|RESIGNAL|RESTRICT|RETURN|REVOKE|RIGHT|RLIKE|SCHEMA|SCHEMAS|SECOND_MICROSECOND|SELECT|SENSITIVE|SEPARATOR|SET|SHOW|SIGNAL|SMALLINT|SPATIAL|SPECIFIC|SQL|SQLEXCEPTION|SQLSTATE|SQLWARNING|SQL_BIG_RESULT|SQL_CALC_FOUND_ROWS|SQL_SMALL_RESULT|SSL|STARTING|STRAIGHT_JOIN|TABLE|TERMINATED|THEN|TINYBLOB|TINYINT|TINYTEXT|TO|TRAILING|TRIGGER|TRUE|UNDO|UNION|UNIQUE|UNLOCK|UNSIGNED|UPDATE|USAGE|USE|USING|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|VALUES|VARBINARY|VARCHAR|VARCHARACTER|VARYING|WHEN|WHERE|WHILE|WITH|WRITE|XOR|YEAR_MONTH|ZEROFILL";

    protected $lexer;

    public function __construct($sql,$type = false)
    {

        $this->lexer = new Lexer();
        if ($type)
        {
            $this->type = strtolower ($type);
        }
        $this->sql = $sql;
    }

    /**
     * get functions that used in sql if $return_func is false then return
     *  clean sql
     *
     * @param bool|true $return_func
     * @param null $sql string sql query
     *
     * @return mixed
     */
    public function functions($return_func = true,$sql = NULL)
    {

        if (!$sql)
        {
            $sql = $this->sql;
        }
        $this->lexer->lex ($sql);
        $sql = $this->lexer->clean ();
        $functions = explode ('|',$this->functions);
        // we calc position of each function that exist in sql
        $exists_functions = array();
        foreach ($functions as $func)
        {
            if (($pos = strpos ($sql,$func.' ('))!==false || ($pos = strpos ($sql,$func.'('))!==false)
            {
                $exists_functions[] = array('func' => $func,'position' => $pos);
            }
        }
        if ($return_func)
        {
            return $exists_functions;
        }
        elseif (count ($exists_functions))
        {
            // we have to remove function from last position to remove ) and inside code in theme.
            $exists_functions = Tools::sort ($exists_functions,'position',SORT_DESC);
            foreach ($exists_functions as $func)
            {
                $sql = String::removeSubStr ($sql,$func['func'],')',true);
            }

            return $sql;
        }
        else
        {
            return $sql;
        }
    }

    /**
     * get joins tables and condition of them
     */
    public function joins()
    {

        $string = 'JOIN';
        $positions = String::findPositions ($this->sql,$string);
        if (count ($positions))
        {
            // check on join or more
            if (count ($positions)==1)
            {
                // we have usually USING or ON in joins mysql so we get tables and alias of theme
                $table = String::getBetween ($this->sql,'JOIN','ON');
                if (!$table)
                {
                    $table = String::getBetween ($this->sql,'JOIN','USING');
                }
                if (!$table)
                {
                    return array();
                }
                $aliases = $this->getAliases ($table);

                return array(
                    array('table' => trim (trim($aliases[0]),'`'),'alias' => isset($aliases[1]) ? trim (trim($aliases[1]),'`') : '')
                );
            }
            //several joins founded
            else
            {
                $tables = array();
                $index = 0;
                foreach ($positions as $key=>$position)
                {
                    $index++;
                    $next_position = String::findPositions ($this->sql,'ON',$position,false);
                    if (!count ($next_position))
                    {
                        $next_position = String::findPositions ($this->sql,'USING',$position,false);
                    }
                    if (!count ($next_position))
                    {
                        continue;
                    }
                    else
                    {

                        $next_position = $next_position[0];
                        if(isset($positions[$index]) && $next_position>$positions[$index])
                            continue;
                        $length = $next_position - $position;
                        $table = substr ($this->sql,$position + strlen ('JOIN'),$length-strlen('JOIN'));
                        $aliases = $this->getAliases ($table);
                        $tables[] =  array('table' => trim (trim($aliases[0]),'`'),'alias' => isset($aliases[1]) ? trim (trim($aliases[1]),'`') : '');
                    }
                }
                return $tables;
            }
        }
        else
        {
            return array();
        }
    }

    // get aliases
    protected function getAliases($table)
    {

        $aliases = explode ('AS',trim ($table));
        if (count ($aliases)==1)
            // check with space
        {
            $aliases = explode (' ',trim ($table));
        }

        return $aliases;
    }
}