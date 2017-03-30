<?php

class NoRejectFunctionDeclaredExceptionn extends \Exception {}

interface PromiseInterface
{
  public function then($tf1, $tf2 = null);
}

class Promise implements PromiseInterface
{
	private $f = null;
	private $thenFunctionResolved = null;
	private $thenFunctionRejected = null;

	public function __construct( $f )
	{
		$this->f = $f;
		$this->resolve = function($val)
		{
			call_user_func($this->thenFunctionResolved, $val);
		};
		$this->reject = function($val)
		{
			if (!$this->thenFunctionRejected)
			{
				throw new \NoRejectFunctionDeclaredException('No function for reject in \'then\' defined!');
			}
			call_user_func($this->thenFunctionRejected, $val);
		};
	}

	public function then($tf1, $tf2 = null)
	{
		$this->thenFunctionResolved = $tf1;
		$this->thenFunctionRejected = $tf2;

		try
		{
		  call_user_func($this->f, $this->resolve, $this->reject);
		}
		catch(\Exception $e)
		{
		  if (!$e instanceof \NoRejectFunctionDeclaredException)
		  {
            call_user_func($this->f, $this->resolve);
		  }
		  else
		  {
			  throw $e;
		  }
		}

		return $this;
	}
}
?>