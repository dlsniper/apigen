<?php

/**
 * ApiGen 2.4.1 - API documentation generator for PHP 5.3+
 *
 * Copyright (c) 2010 David Grudl (http://davidgrudl.com)
 * Copyright (c) 2011 Jaroslav Hanslík (https://github.com/kukulich)
 * Copyright (c) 2011 Ondřej Nešpor (https://github.com/Andrewsville)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

namespace ApiGen;

/**
 * Extension reflection envelope.
 *
 * Alters TokenReflection\IReflectionExtension functionality for ApiGen.
 *
 * @author Jaroslav Hanslík
 * @author Ondřej Nešpor
 */
class ReflectionExtension extends ReflectionBase
{
	/**
	 * Returns a class reflection.
	 *
	 * @param string $name Class name
	 * @return \ApiGen\ReflectionClass|null
	 */
	public function getClass($name)
	{
		$class = $this->reflection->getClass($name);
		if (null === $class) {
			return null;
		}
		if (isset(self::$parsedClasses[$name])) {
			return self::$parsedClasses[$name];
		}
		return new ReflectionClass($class, self::$generator);
	}

	/**
	 * Returns classes defined by this extension.
	 *
	 * @return array
	 */
	public function getClasses()
	{
		$generator = self::$generator;
		$classes = self::$parsedClasses;
		return array_map(function(TokenReflection\IReflectionClass $class) use ($generator, $classes) {
			return isset($classes[$class->getName()]) ? $classes[$class->getName()] : new ReflectionClass($class, $generator);
		}, $this->reflection->getClasses());
	}

	/**
	 * Returns a constant reflection.
	 *
	 * @param string $name Constant name
	 * @return \ApiGen\ReflectionConstant|null
	 */
	public function getConstant($name)
	{
		return $this->getConstantReflection($name);
	}

	/**
	 * Returns a constant reflection.
	 *
	 * @param string $name Constant name
	 * @return \ApiGen\ReflectionConstant|null
	 */
	public function getConstantReflection($name)
	{
		$constant = $this->reflection->getConstantReflection($name);
		return null === $constant ? null : new ReflectionConstant($constant, self::$generator);
	}

	/**
	 * Returns reflections of defined constants.
	 *
	 * @return array
	 */
	public function getConstants()
	{
		return $this->getConstantReflections();
	}

	/**
	 * Returns reflections of defined constants.
	 *
	 * @return array
	 */
	public function getConstantReflections()
	{
		$generator = self::$generator;
		return array_map(function(TokenReflection\IReflectionConstant $constant) use ($generator) {
			return new ReflectionConstant($constant, $generator);
		}, $this->reflection->getConstantReflections());
	}

	/**
	 * Returns a function reflection.
	 *
	 * @param string $name Function name
	 * @return \ApiGen\ReflectionFunction
	 */
	public function getFunction($name)
	{
		$function = $this->reflection->getFunction($name);
		return null === $function ? null : new ReflectionFunction($function, self::$generator);
	}

	/**
	 * Returns functions defined by this extension.
	 *
	 * @return array
	 */
	public function getFunctions()
	{
		$generator = self::$generator;
		return array_map(function(TokenReflection\IReflectionFunction $function) use ($generator) {
			return new ReflectionFunction($function, $generator);
		}, $this->reflection->getFunctions());
	}
}
