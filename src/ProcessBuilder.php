<?php

/*
 * This file was originally part of the Symfony package.
 *
 * Copyright (c) 2004-2020 Fabien Potencier
 * Copyright (c) 2020 Ben Ramsey
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Ramsey\Pygments;

use ReflectionClass;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessUtils;

class ProcessBuilder
{
    private $arguments;
    private $input = null;
    private $prefix = [];

    /**
     * @param string[] $arguments An array of arguments
     */
    public function __construct(array $arguments = [])
    {
        $this->arguments = $arguments;
    }

    /**
     * Creates a process builder instance.
     *
     * @param string[] $arguments An array of arguments
     *
     * @return static
     */
    public static function create(array $arguments = [])
    {
        return new static($arguments);
    }

    /**
     * Adds an unescaped argument to the command string.
     *
     * @param string $argument A command argument
     *
     * @return $this
     */
    public function add($argument)
    {
        $this->arguments[] = $argument;

        return $this;
    }

    /**
     * Adds a prefix to the command string.
     *
     * The prefix is preserved when resetting arguments.
     *
     * @param string|array $prefix A command prefix or an array of command prefixes
     *
     * @return $this
     */
    public function setPrefix($prefix)
    {
        $this->prefix = \is_array($prefix) ? $prefix : [$prefix];

        return $this;
    }

    /**
     * Sets the arguments of the process.
     *
     * Arguments must not be escaped.
     * Previous arguments are removed.
     *
     * @param string[] $arguments
     *
     * @return $this
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * Sets the input of the process.
     *
     * @param resource|string|int|float|bool|\Traversable|null $input The input content
     *
     * @return $this
     *
     * @throws InvalidArgumentException In case the argument is invalid
     */
    public function setInput($input)
    {
        $this->input = ProcessUtils::validateInput(__METHOD__, $input);

        return $this;
    }

    /**
     * Creates a Process instance and returns it.
     *
     * @return Process
     *
     * @throws LogicException In case no arguments have been provided
     */
    public function getProcess()
    {
        if (0 === \count($this->prefix) && 0 === \count($this->arguments)) {
            throw new LogicException('You must add() command arguments before calling getProcess().');
        }

        $command = array_merge($this->prefix, $this->arguments);

        $reflectedProcess = new ReflectionClass(Process::class);
        $reflectedConstructor = $reflectedProcess->getConstructor();
        $param = $reflectedConstructor->getParameters()[0] ?? null;

        if ($param === null) {
            throw new \RuntimeException('Unable to determine type for first parameter of ' . Process::class);
        }

        if ($param->getType() && $param->getType()->getName() === 'array') {
            return new Process($command, null, null, $this->input);
        }

        $commandLine = array_shift($command) . ' ';
        $commandLine .= implode(
            ' ',
            array_map(
                function ($v) {
                    return escapeshellarg($v);
                },
                $command
            )
        );

        return new Process($commandLine, null, null, $this->input);
    }
}
