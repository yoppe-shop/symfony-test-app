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
		/*
		Oder mit Klammern über die Klassenmethoden:
		($this->f)($this->resolve, $this->reject);

		Oder Umweg über "normale" Variable:
		$f = $this->f;
		$f($this->resolve, $this->reject);
        */

		return $this;
	}
}

$p1 = new Promise( function( $resolve )
{
	if (1 === 1)
	{
		$resolve(1);
	}
	else
	{
		// $reject(2);
	}
} );

// FALL 1 MIT REJECTION:

$p1->then(function($v){
	echo "RESOLVED: " . $v . "<br>";
},
function($v){
	echo "REJECTED: " . $v . "<br>";
});

// FALL 2 MIT REJECTION:

$p2 = new Promise( function( $resolve, $reject )
{
	if (1 !== 1)
	{
		$resolve(1);
	}
	else
	{
		$reject(2);
	}
});

$p2->then(function($v){
	echo "RESOLVED: " . $v . "<br>";
},
function($v){
	echo "REJECTED: " . $v . "<br>";
});
?>