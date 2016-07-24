<?php $aCacheData = API\Engine\Database\DbRow::__set_state(array(
   '_oTable' => 
  API\Application\DbTables\User::__set_state(array(
     '_sTableName' => 'api_user',
     '_mPrimaryKey' => 'user_id',
     '_aValidateRules' => 
    array (
      'required' => 
      array (
        0 => 
        array (
          0 => 'username',
          1 => 'required',
        ),
        1 => 
        array (
          0 => 'email',
          1 => 'email',
        ),
      ),
      'integer' => 'level',
    ),
     '_oQuery' => NULL,
     '_aData' => NULL,
     'app' => NULL,
  )),
   '_oValidator' => NULL,
   '_aErrors' => NULL,
   '_aData' => 
  array (
    'user_id' => 9,
    'username' => 'nice',
    'password' => NULL,
    'level' => 1,
    'email' => 'email@email.c11o1m2',
  ),
   'app' => NULL,
)); $TTL = 1467714880;
?>