<?php
/**
 * QuickMySQLLexer is a MySQL SQL lexer.
 * It can tokenize MySQL queries, determine the type of each token and its position,
 * And generate clean query as well as markings and structure
 * Currently in beta stage. Needs testing.
 * @author AbiusX
 * @version 1.0
 */
namespace PHPParser\Lexer;
class Lexer
{
	const TYPE_COMMENT="comment";
	const TYPE_SYMBOL="symbol";
	const TYPE_MULTILINE_COMMENT="multiline-comment";
	const TYPE_IDENTIFIER="identifier";
	const TYPE_STRING="string";
	const TYPE_NUMBER="number";
	const TYPE_KEYWORD="keyword";
	/**
	 * The original query
	 * @var [type]
	 */
	protected $query; 
	/**
	 * Returns the query processed by the object
	 * @return [type] [description]
	 */
	function query()
	{
		return $this->query;
	}
	/**
	 * An array of tokens lexed
	 * @var [type]
	 */
	protected $tokens;
	/**
	 * Return tokens lexed
	 * @return array 2D, each element has keys 'original','position','length','clean','type'
	 */
	function tokens()
	{
		return $this->tokens();
	}
	/**
	 * MySQL reserved words
	 * @var string
	 */
	protected $reserved="ACCESSIBLE|ADD|ALL|ALTER|ANALYZE|AND|AS|ASC|ASENSITIVE|BEFORE|BETWEEN|BIGINT|BINARY|BLOB|BOTH|BY|CALL|CASCADE|CASE|CHANGE|CHAR|CHARACTER|CHECK|COLLATE|COLUMN|CONDITION|CONSTRAINT|CONTINUE|CONVERT|CREATE|CROSS|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURSOR|DATABASE|DATABASES|DAY_HOUR|DAY_MICROSECOND|DAY_MINUTE|DAY_SECOND|DEC|DECIMAL|DECLARE|DEFAULT|DELAYED|DELETE|DESC|DESCRIBE|DETERMINISTIC|DISTINCT|DISTINCTROW|DIV|DOUBLE|DROP|DUAL|EACH|ELSE|ELSEIF|ENCLOSED|ESCAPED|EXISTS|EXIT|EXPLAIN|FALSE|FETCH|FLOAT|FLOAT4|FLOAT8|FOR|FORCE|FOREIGN|FROM|FULLTEXT|GET|GRANT|GROUP|HAVING|HIGH_PRIORITY|HOUR_MICROSECOND|HOUR_MINUTE|HOUR_SECOND|IF|IGNORE|IN|INDEX|INFILE|INNER|INOUT|INSENSITIVE|INSERT|INT|INT1|INT2|INT3|INT4|INT8|INTEGER|INTERVAL|INTO|IO_AFTER_GTIDS|IO_BEFORE_GTIDS|IS|ITERATE|JOIN|KEY|KEYS|KILL|LEADING|LEAVE|LEFT|LIKE|LIMIT|LINEAR|LINES|LOAD|LOCALTIME|LOCALTIMESTAMP|LOCK|LONG|LONGBLOB|LONGTEXT|LOOP|LOW_PRIORITY|MASTER_BIND|MASTER_SSL_VERIFY_SERVER_CERT|MATCH|MAXVALUE|MEDIUMBLOB|MEDIUMINT|MEDIUMTEXT|MIDDLEINT|MINUTE_MICROSECOND|MINUTE_SECOND|MOD|MODIFIES|NATURAL|NOT|NO_WRITE_TO_BINLOG|NULL|NUMERIC|ON|OPTIMIZE|OPTIMIZER_COSTS|OPTION|OPTIONALLY|OR|ORDER|OUT|OUTER|OUTFILE|PARTITION|PRECISION|PRIMARY|PROCEDURE|PURGE|RANGE|READ|READS|READ_WRITE|REAL|REFERENCES|REGEXP|RELEASE|RENAME|REPEAT|REPLACE|REQUIRE|RESIGNAL|RESTRICT|RETURN|REVOKE|RIGHT|RLIKE|SCHEMA|SCHEMAS|SECOND_MICROSECOND|SELECT|SENSITIVE|SEPARATOR|SET|SHOW|SIGNAL|SMALLINT|SPATIAL|SPECIFIC|SQL|SQLEXCEPTION|SQLSTATE|SQLWARNING|SQL_BIG_RESULT|SQL_CALC_FOUND_ROWS|SQL_SMALL_RESULT|SSL|STARTING|STRAIGHT_JOIN|TABLE|TERMINATED|THEN|TINYBLOB|TINYINT|TINYTEXT|TO|TRAILING|TRIGGER|TRUE|UNDO|UNION|UNIQUE|UNLOCK|UNSIGNED|UPDATE|USAGE|USE|USING|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|VALUES|VARBINARY|VARCHAR|VARCHARACTER|VARYING|WHEN|WHERE|WHILE|WITH|WRITE|XOR|YEAR_MONTH|ZEROFILL";
	protected $reservedExtra="ENGINE|CHARSET|AUTO_INCREMENT|DUPLICATE";
	protected $functions="ABS|ACOS|ADDDATE|ADDTIME|AES_DECRYPT|AES_ENCRYPT|ANY_VALUE|Area|AsBinary|AsWKB|ASCII|ASIN|AsText|AsWKT|ATAN2|ATAN|ATAN|AVG|BENCHMARK|BIN|BINARY|BIT_AND|BIT_COUNT|BIT_LENGTH|BIT_OR|BIT_XOR|Buffer|CASE|CAST|CEIL|CEILING|Centroid|CHAR_LENGTH|CHAR|CHARACTER_LENGTH|CHARSET|COALESCE|COERCIBILITY|COLLATION|COMPRESS|CONCAT_WS|CONCAT|CONNECTION_ID|Contains|CONV|CONVERT_TZ|CONVERT|ConvexHull|COS|COT|COUNT|CRC32|Crosses|CURDATE|CURRENT_DATE|CURRENT_TIME|CURRENT_TIMESTAMP|CURRENT_USER|CURTIME|DATABASE|DATE_ADD|DATE_FORMAT|DATE_SUB|DATE|DATEDIFF|DAY|DAYNAME|DAYOFMONTH|DAYOFWEEK|DAYOFYEAR|DECODE|DEFAULT|DEGREES|DES_DECRYPT|DES_ENCRYPT|Dimension|Disjoint|Distance|DIV|ELT|ENCODE|ENCRYPT|EndPoint|Envelope|Equals|EXP|EXPORT_SET|ExteriorRing|EXTRACT|ExtractValue|FIELD|FIND_IN_SET|FLOOR|FORMAT|FOUND_ROWS|FROM_BASE64|FROM_DAYS|FROM_UNIXTIME|GeomCollFromText|GeometryCollectionFromText|GeomCollFromWKB|GeometryCollectionFromWKB|GeometryCollection|GeometryN|GeometryType|GeomFromText|GeometryFromText|GeomFromWKB|GET_FORMAT|GET_LOCK|GLength|GREATEST|GROUP_CONCAT|GTID_SUBSET|GTID_SUBTRACT|HEX|HOUR|IF|IFNULL|IN|INET_ATON|INET_NTOA|INET6_ATON|INET6_NTOA|INSERT|INSTR|InteriorRingN|Intersects|INTERVAL|IS_FREE_LOCK|IS_IPV4_COMPAT|IS_IPV4_MAPPED|IS_IPV4|IS_IPV6|IS NOT NULL|IS NOT|IS NULL|IS_USED_LOCK|IS|IsClosed|IsEmpty|ISNULL|IsSimple|LAST_DAY|LAST_INSERT_ID|LCASE|LEAST|LEFT|LENGTH|LIKE|LineFromText|LineFromWKB|LineStringFromWKB|LineString|LN|LOAD_FILE|LOCALTIME|LOCALTIMESTAMP|LOCATE|LOG10|LOG2|LOG|LOWER|LPAD|LTRIM|MAKE_SET|MAKEDATE|MAKETIME|MASTER_POS_WAIT|MATCH|MAX|MBRContains|MBRCoveredBy|MBRCovers|MBRDisjoint|MBREqual|MBREquals|MBRIntersects|MBROverlaps|MBRTouches|MBRWithin|MD5|MICROSECOND|MID|MIN|MINUTE|MLineFromText|MLineFromWKB|MOD|MONTH|MONTHNAME|MPointFromText|MultiPointFromText|MPointFromWKB|MultiPointFromWKB|MPolyFromText|MultiPolygonFromText|MPolyFromWKB|MultiPolygonFromWKB|MultiLineString|MultiPoint|MultiPolygon|NAME_CONST|NOW|NULLIF|NumGeometries|NumInteriorRings|NumPoints|OCT|OCTET_LENGTH|OLD_PASSWORD|ORD|Overlaps|PASSWORD|PERIOD_ADD|PERIOD_DIFF|PI|Point|PointFromText|PointFromWKB|PointN|PolyFromText|PolygonFromText|PolyFromWKB|PolygonFromWKB|Polygon|POSITION|POW|POWER|PROCEDURE|QUARTER|QUOTE|RADIANS|RAND|RANDOM_BYTES|REGEXP|RELEASE_ALL_LOCKS|RELEASE_LOCK|REPEAT|REPLACE|REVERSE|RIGHT|RLIKE|ROUND|ROW_COUNT|RPAD|RTRIM|SCHEMA|SEC_TO_TIME|SECOND|SESSION_USER|SHA1|SHA|SHA2|SIGN|SIN|SLEEP|SOUNDEX|SOUNDS LIKE|SPACE|SQRT|SRID|ST_Area|ST_AsGeoJSON|ST_Centroid|ST_Contains|ST_ConvexHull|ST_Crosses|ST_Difference|ST_Disjoint|ST_Distance_Sphere|ST_Distance|ST_Envelope|ST_Equals|ST_GeoHash|ST_GeomFromGeoJSON|ST_Intersection|ST_Intersects|ST_IsValid|ST_LatFromGeoHash|ST_Length|ST_LongFromGeoHash|ST_MakeEnvelope|ST_Overlaps|ST_PointFromGeoHash|ST_Simplify|ST_SymDifference|ST_Touches|ST_Union|ST_Validate|ST_Within|StartPoint|STD|STDDEV_POP|STDDEV_SAMP|STDDEV|STR_TO_DATE|STRCMP|SUBDATE|SUBSTR|SUBSTRING_INDEX|SUBSTRING|SUBTIME|SUM|SYSDATE|SYSTEM_USER|TAN|TIME_FORMAT|TIME_TO_SEC|TIME|TIMEDIFF|TIMESTAMP|TIMESTAMPADD|TIMESTAMPDIFF|TO_BASE64|TO_DAYS|TO_SECONDS|Touches|TRIM|TRUNCATE|UCASE|UNCOMPRESS|UNCOMPRESSED_LENGTH|UNHEX|UNIX_TIMESTAMP|UpdateXML|UPPER|USER|UTC_DATE|UTC_TIME|UTC_TIMESTAMP|UUID_SHORT|UUID|VALIDATE_PASSWORD_STRENGTH|VALUES|VAR_POP|VAR_SAMP|VARIANCE|VERSION|WAIT_FOR_EXECUTED_GTID_SET|WAIT_UNTIL_SQL_THREAD_AFTER_GTIDS|WEEK|WEEKDAY|WEEKOFYEAR|WEIGHT_STRING|Within|X|XOR|Y|YEAR|YEARWEEK";
	function __construct()
	{
		$this->reserved=array_flip(explode("|",$this->reserved));
		$this->reserved=array_merge($this->reserved,array_flip(explode("|",$this->reservedExtra)));
		$this->functions=array_flip(explode("|",strtoupper($this->functions)));
		$this->symbols=array_flip($this->symbols);
		$this->whitespace=array_flip($this->whitespace);
	}
	/**
	 * Converts a token string to a token array
	 * @param  string  $token [description]
	 * @param  integer $pos   position in the origianl query (end's position)
	 * @return array         [description]
	 */
	private function token($token,$pos=0)
	{
		static $count=0;
		$len=strlen($token);
		$last=$len-1;
		$res=array("original"=>$token,"type"=>"","position"=>$pos-$len+1,"length"=>$len,"clean"=>$token);
		if (isset($this->reserved[strtoupper($token)]))
		{
			$res['type']="keyword";
			$res['clean']=strtoupper($token);
		}
		elseif (isset($this->functions[strtoupper($token)]))
		{
			$res['type']="function";
			$res['clean']=strtoupper($token);
		}
		elseif (isset($this->symbols[$token]))
			$res['type']=self::TYPE_SYMBOL;
		elseif ($len>2 and $token[0]=='`' and $token[$last]=='`')
			$res['type']=self::TYPE_IDENTIFIER;
		elseif ($len>2 and $token[0]=='/' and $token[1]=='*')
			$res['type']=self::TYPE_MULTILINE_COMMENT;
		elseif ($len>=2 and $token[0]=='/' and $token[1]=='/')
			$res['type']=self::TYPE_COMMENT;
		elseif ($len>=2 and $token[0]=='-' and $token[1]=='-')
			$res['type']=self::TYPE_COMMENT;
		elseif ($len>=1 and $token[0]=='#')
			$res['type']=self::TYPE_COMMENT;
		elseif ($len>2 and $token[0]=='"' and $token[$last]=='"')
			$res['type']=self::TYPE_STRING;
		elseif ($len>2 and $token[0]=="'" and $token[$last]=="'")
			$res['type']=self::TYPE_STRING;
		elseif (is_numeric($token))
			$res['type']=self::TYPE_NUMBER;
		else
			$res['type']=self::TYPE_IDENTIFIER;
		// print_r($res);
		$this->tokens[]=$res;
		return $res;
	}
	/**
	 * Whether or not a string is made of whitespace
	 * @param  [type]  $string [description]
	 * @return boolean         [description]
	 */
	private function isWhitespace($string)
	{
		if ($string==="") return true;
		for ($i=0;$i<strlen($string);++$i)
			if (!isset($this->whitespace[$string[$i]])) return false;

		return true;
	}
	/**
	 * MySQL symbols
	 * * is excluded as it needs special handling
	 * @var array
	 */
	protected $symbols=array("(",")",",","=","/","+",">","<","<=",">=","!=","&&","||","&","|","^","<<",">>","-","%","<>","!"); 
	/**
	 * Whitespace characters
	 * @var array
	 */
	protected $whitespace=array("\n","\r"," ","\t");
	/**
	 * Two word keywords.
	 * Not used atm.
	 * @var array
	 */
	protected $twoWorders=array("insert"=>"into","order"=>"by","group"=>"by","inner"=>"join","outer"=>"join","left"=>"join","right"=>"join","union"=>"all");
	/**
	 * Lex a MySQL query. The results will be in tokens property,
	 * and can be accessed with tokens(), clean(), markings(), cleanMarkings() functions
	 * @param  [type] $query [description]
	 * @return boolean       query validity  (limited to lexer)
	 */
	function lex($query)
	{
		$this->query=$query;
		$this->tokens=array();
		$len=strlen($query);
		$buf="";
		$oldToken="";
		$prev="";
		$inString=false;
		$inComment=false;
		for ($i=0;$i<$len;++$i)
		{
			$next=$i<$len-1?$query[$i+1]:"";
			$prev=$i>0?$query[$i-1]:"";
			$c=$query[$i];
			//comment handling
			if ($inComment)
			{
				if ($c==$inComment[0] and (strlen($inComment)==1 or $next==$inComment[1]))
				{
					$pos=$i;
					if ($inComment=="\n") 
					{
						$inComment="";
						$pos--;
					}
					if (strlen($inComment)>1) 
					{
						$pos++;
						$i++;
					}
					$this->token($buf.$inComment,$pos);
					$inComment=false;
					$buf="";
					continue;
				}
				else
				{
					$buf.=$c;
					continue;
				}
			}
			if (!$inComment and !$inString)
			{
				if ($c=="/" and $next=="*")
					$inComment="*/";
				if ($c=="/" and $next=="/")
					$inComment="\n";
				if ($c=="-" and $next=="-")
					$inComment="\n";
				if ($c=="#")
					$inComment="\n";
			}
			//string handling
			if ($c=="'" or $c=='"')
			{
				if (!$inString)
					$inString=$c;
				else
				{
					if ($c==$inString and $prev!="\\")
					{
						$inString=false;
						$this->token($buf.$c,$i);
						$buf="";
						continue;
					}
				}

			}
			if ($inString)
			{
				$buf.=$c;
				continue;
			}
			//* is only an operator if its not all selector, e.g SELECT t.* FROM vs. select 1*3
			if (isset($this->whitespace[$c]) or isset($this->symbols[$c]) or ($c=="*" and $prev!=".") or $inComment) //end of token
			{
				// if (isset($this->twoWorders[strtolower($buf)])) #two word tokens
					// $oldToken=$buf;
				// else #one word tokens
				if (!$this->isWhitespace($buf)) //only if not whitespace
					// if ($oldToken and $this->twoWorders[strtolower($oldToken)]==strtolower($buf)) #if second word of a two word
					// {
						// $this->token($oldToken." ".$buf,$i-1);
						// $oldToken="";
					// }
					// else #single word, not whitespace
						$this->token($buf,$i-1);
				if (!$inComment and (isset($this->symbols[$c]) or ($c=="*" and $prev!="."))) //if this char is a token
					if ($next and isset($this->symbols[$c.$next])) //two char symbol
					{
						$i++;
						$this->token($c.$next,$i);
					}
					else //single char symbol
						$this->token($c,$i);

				$buf="";
			}
			else
				$buf.=$c;

			if ($inComment) //for first character of a comment block
				$buf.=$c;

		}
		if (!$this->isWhitespace($buf))			
			$this->token($buf,$i-1); //i has increased after the loop, reduce by one

		if ($inComment or $inString) //invalid
			return false;
		return true;
	}

	/**
	 * Return the clean query
	 * Every token is delimited with one space
	 * Single line comments are delimited by new line
	 * @return string
	 */
	public function clean()
	{
		$clean="";
		for ($i=0;$i<count($this->tokens);++$i)
		{
			$clean.=$this->tokens[$i]['clean']." ";
			if ($this->tokens[$i]['type']=='comment')
				$clean=substr($clean,0,-1)."\n ";
		}
		return $clean;
	}
	/**
	 * Return markings for the clean version of the query
	 * Markings define what every area of the query is
	 * @return string
	 */
	public function cleanMarkings()
	{
		$cleanMarkings="";
		for ($i=0;$i<count($this->tokens);++$i)
		{
			$c=$this->tokens[$i]['type'][0];
			if ($this->tokens[$i]['type']=='symbol')
				$c="@";
			$cleanMarkings.=str_repeat($c, strlen($this->tokens[$i]['clean']))." ";
			if ($c=='c') //single line comment
				$cleanMarkings=substr($cleanMarkings,0,-1)."\n ";
		}
		return $cleanMarkings;
	}
	/**
	 * Return markings for the original query
	 * Markings define what every area of the query is
	 * @return string
	 */
	public function markings()
	{
		$markings=$this->query;
		for ($i=0;$i<count($this->tokens);++$i)
		{
			$c=$this->tokens[$i]['type'][0];
			if ($this->tokens[$i]['type']=='symbol')
				$c="@";
			for ($j=0;$j<$this->tokens[$i]['length'];++$j)
				$markings[$j+$this->tokens[$i]['position']]=$c;
		}
		return $markings;
	}

	/**
	 * Returns the query structure
	 * The structure is the clean query where numebrs are replaced with 1 and strings with 'str'
	 * It is useful for caching purposes, in which structure of all semantically equivalent queries are the same
	 * 	allowing for good caching.
	 * Comments are not modified, as they are not supposed to be changed in a query
	 * @return string
	 */
	public function structure()
	{
		$structure="";
		for ($i=0;$i<count($this->tokens);++$i)
		{
			$c=$this->tokens[$i]['type'][0];
			if ($this->tokens[$i]['type']=='symbol')
				$c="@";
			if ($this->tokens[$i]['type']==self::TYPE_STRING)
				$structure.="'str'";
			elseif ($this->tokens[$i]['type']==self::TYPE_NUMBER)
				$structure.="1";
			else
				$structure.=$this->tokens[$i]['clean'];
			$structure.=" ";
			if ($c=='c') //single line comment
				$structure=substr($structure,0,-1)."\n ";
		}
		return $structure;
	}
}