<?php
/**
 * Created by PhpStorm.
 * User: kyle
 * Date: 2018/06/02
 * Time: 10:21 AM
 */
// insert into db
$sql = "INSERT INTO tableName (rowname, rowname, rowname) VALUES('?,?,?') WHERE rowname = value";
if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param('sss', $rowName_param, $rowName2_param, $rowName3_param );
    $rowName_param = $rowName;
    $rowName2_param = $rowName;
    $rowName3_param = $rowName3;
    if($stmt->execute()){
        // sql was successfull

    }else{
        // failure with the execute of stmt
        echo "error with the sql";
    }
}else{
    // failure with preparing the statment
    echo 'error with preparing the statement';
}
// Close statement
$stmt->close();

// Close connection
$mysqli->close();

// update the db (all that changes is the sql
$sql = "UPDATE tablename SET rowname1 = ?, rowname2=?, rowname3 =? WHERE rowname = value";
// Delete from the db (all that changes s the sql
$sql = "DELETE FROM movies WHERE rowName = ?";
