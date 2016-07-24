<?php
namespace API\Library\Authenticate;
interface Authenticate
{
	public function validate($auth);
}