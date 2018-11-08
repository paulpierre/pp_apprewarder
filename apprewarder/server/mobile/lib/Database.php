<?php
class Database {
	public function db_update($table_name,$db_columns,$db_conditions,$isOr=false)
	{
        $utilityInstance = new UtilityManager();

        $values = ''; $column_count = 0;$i = 0; $value = null; $db_column = '';

        $column_count = count($db_columns);
        $db_conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME,DATABASE_PORT);
        //lets iterate through all the columns and values we want to update
        foreach($db_columns as $columnKey=>$columnVal)
        {

            $db_value = (is_numeric($columnVal))? $columnVal : '\''.$db_conn->real_escape_string($columnVal).'\'';
            $db_column = $columnKey;
            $db_separator = ($i<=$column_count && $i > 0)?',':'';
            $values .= $db_separator . $db_column . '=' . $db_value;
            $i++;
        }

        $db_condition =''; $db_separator='';
        /*
        foreach($db_conditions as $conditionKey=>$conditionVal)
        {
            if(count($db_conditions)>1){ $db_separator = ($isOr ? ' OR ' : ' AND ');}         //by default we assume these will be an inclusive chain so we set our separator to AND
            else { $db_separator = '';}
            $conditionValStr = (is_numeric($conditionVal))?$conditionVal:'\'' . $conditionVal . '\'';
            $db_condition .= $db_separator . $conditionKey . '=' . $conditionValStr; //add each column
        }
        */
        $i=0;
        $db_condition = $db_conditions;                             //overwrite our final output in case its a flat string
        if(is_array($db_condition))                                  //if its not a flat string, lets iterate and build a string
        {
            $db_condition = '';                                     //lets reset the final output
            $condition_count = count($db_conditions);
            foreach($db_conditions as $conditionKey=>$conditionVal)
            {
                if($condition_count>1 && $i>0){ $db_separator = ($isOr ? ' OR ' : ' AND ');}         //by default we assume these will be an inclusive chain so we set our separator to AND
                else { $db_separator = '';}
                $conditionValStr = (is_numeric($conditionVal))?$conditionVal:'\'' . $conditionVal . '\'';
                $db_condition .= $db_separator . $conditionKey . '=' . $conditionValStr; //add each column
                $i++;
            }
            $i=0;
        }



        $q = ('UPDATE ' . $table_name .' SET '. $values . ' WHERE ' . $db_condition);


        if (mysqli_connect_errno()) {       ////throw an error if there is a connection problem
            $utilityInstance->log('Error: ' . $q . '\n' . mysqli_connect_error());
            return false; //mysqli_connect_error());
        } else {   //transaction successful
            $result = $db_conn->query($q); //or $this->db_error($result);
            $utilityInstance->log('Query: ' . $q . '\nResult:' . print_r($result,true));
            $db_conn->close();
            return $result;
        }
	}


    public function db_create($table_name,$db_columns)
    {
        //print_r($db_columns);
        $utilityInstance = new UtilityManager();
        $values = ''; $column_count = count($db_columns);$i=0; $value = null; $db_column = '';$columns='';

        //lets iterate through all the columns and values we want to update
        $db_conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME,DATABASE_PORT);
        //print_r($db_columns);exit();
        foreach($db_columns as $columnKey=>$columnVal)
        {
            $db_value = $db_conn->real_escape_string($columnVal);
            $db_column = $columnKey;
            $separator = ($i<=$column_count && $i > 0)?',':'';
            $columns .= $separator . $db_column;
            $values .= $separator . (is_numeric($db_value) ? $db_value: '\''.$db_value.'\'');
            $i++;

        }
        $q = ('INSERT INTO ' . $table_name . '('  . $columns . ')' .
            ' VALUES( '.$values .')');
        $db_conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME,DATABASE_PORT);
        if (mysqli_connect_errno()) {       ////throw an error if there is a connection problem
            $utilityInstance->log('Error: ' . $q . '\n' . mysqli_connect_error());
            return false; //mysqli_connect_error());
        } else {   //transaction successful
            //print $q;
            $result = $db_conn->query($q); //or $this->db_error($result);
            $utilityInstance->log('Query: ' . $q . '\n Result:' . print_r($result,true));
            $id = $db_conn->insert_id;
            $utilityInstance->log('INSERT row id: ' . $id);
            $db_conn->close();
            return $id;//$result;
        }
    }


    public function db_retrieve($table_name,$db_columns,$db_conditions='',$db_extra=null,$isOr=false)
    {

        $utilityInstance = new UtilityManager();
        $i = 0; $db_column = '';$db_condition = '';$condition_count = 0;
        $db_condition = $db_conditions;                             //overwrite our final output in case its a flat string
        if(is_array($db_condition))                                  //if its not a flat string, lets iterate and build a string
        {
            $db_condition = '';                                     //lets reset the final output
            $condition_count = count($db_conditions);
            foreach($db_conditions as $conditionKey=>$conditionVal)
            {
                if($condition_count>1 && $i>0){ $db_separator = ($isOr ? ' OR ' : ' AND ');}         //by default we assume these will be an inclusive chain so we set our separator to AND
                else { $db_separator = '';}
                $conditionValStr = (is_numeric($conditionVal))?$conditionVal:'\'' . $conditionVal . '\'';
                $db_condition .= $db_separator . $conditionKey . '=' . $conditionValStr; //add each column
                $i++;
            }
            $i=0;
        }

        $q = ('SELECT ' . implode(', ',$db_columns) . ' FROM ' . $table_name .
            (empty($db_condition)?'':' WHERE '.$db_condition)) .
            (empty($db_extra)?'':$db_extra);


        $db_conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME,DATABASE_PORT);
        if (mysqli_connect_errno()) {
            $utilityInstance->log('Error: ' . $q . '\n' . mysqli_connect_error());
            return false; //mysqli_connect_error());
        } else {   //transaction successful
            //print $q;
            $result = $db_conn->query($q); //or $this->db_error($result);
            $db_conn->close();
            $result_set = array();
            while ($row = mysqli_fetch_assoc($result)) {
               array_push($result_set,$row);
            }
            $utilityInstance->log('Query: ' . $q . '\nResult:' . print_r($result_set,true));
            return $result_set;
        }
    }

    public function db_query($q)
    {
        $utilityInstance = new UtilityManager();
        $db_conn = new mysqli(DATABASE_HOST, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_NAME,DATABASE_PORT);
        if (mysqli_connect_errno()) {
            $utilityInstance->log('Error: ' . $q . '\n' . mysqli_connect_error());
            return false;
        } else {   //transaction successful
            $result = $db_conn->query($q); ;
            $db_conn->close();
            $result_set = array();
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($result_set,$row);
            }
            $utilityInstance->log('Query: ' . $q . '\nResult:' . print_r($result_set,true));
            return $result_set;
        }
    }

}



?>