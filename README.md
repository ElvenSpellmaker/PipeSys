PipeSys [![Build Status](https://travis-ci.org/ElvenSpellmaker/PipeSys.svg?branch=master)](https://travis-ci.org/ElvenSpellmaker/PipeSys)
=======

PipeSys (*said Pipes*) is a very basic system of pipes implemented in PHP.

## Requirements
 * PHP >= 5.6

## How it works

The way PipeSys works is by having a chain of commands which are joined together
by the Scheduler to give the effect of pipes by chaining the output of one to
the input of another.
A Scheduler can have many commands added to it and all must be an instance of
`AbstractCommand`.

Commands are special classes which have a `getCommand` method which should
return a Generator, i.e. it should have `yield` statements in it.
When a command wants to read it should use a coroutine call like:
```php
$input = (yield new ReadIntent);
```
By `yield`ing a `ReadIntent` object the command knows it wants to read in order
to continue. When data is available for the command it it sent using the
`send()` generator method. `$input` will pick up this sent value and the
generator will resume.

When a command wants to write it should yield an `OutputIntent` signalling that
it has the intention to output something:
```php
yield new OutputIntent('Hello World!');
```
The output data can be anything you like and may even be sent to another
channel, such as `StdErr`:
```php
yield new OutputIntent('Hello World!', IOConstants::IO_STDERR);
```

Commands at the start or end have access to the `stdin` or `stdout` respectively
of the Scheduler which may correspond to the `stdin` and `stdout` of the
system. (Provided by the `StdIn` and `StdOut` classes.)

The commands are connected by a `ConnectorInterface` implementing class, which
allows you to customise and fine-tune the connection behaviour. A default
conntector called `StandardConnector` is provided which will connect the first
command's input to the StdIn of the system, the last command's output to the
StdOut of the system, all other commands link their outputs and inputs in a
chain (like UNIX pipes) and all commands get connected to the StdErr channel
too.

The Scheduler uses coöperative scheduling, because PHP is single process
and single threaded there is no way to implement preëmptive scheduling without
an extension.

When a stdin method is exhausted it should return an `EOF` object.The `EOF`
signals that the end of the stream (EOF = End Of File) has been reached.
Commands that read will most likely want to return once receiving this as they
usually can't continue any further without anything to read.

Terminating commands should `yield null` to tell the system they can no longer
function any more.

## Example Code

Examples of simple command chains are shown below.

`ChattyYes` is similar to the Unix `Yes` command except instead of `y` being
output it outputs:
```
Yes
I
Am
Chatty
```
over and over.

```sh
ChattyYes | head
```
The above chain shows that ChattyYes is connected to stdIn and its output is
connected to head which will print the first 10 lines it receives.

The PHP code for this is below:
```php
<?php

include 'vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$connector = new Command\StandardConnector;

$c = new PS\Scheduler($connector);
$c->addCommand(new Command\ChattyYes);
$c->addCommand(new Command\Head));
$c->run();

Output:
Yes
I
Am
Chatty
Yes
I
Am
Chatty
Yes
I
```


```sh
ChattyYes | grep "Chatty" | head
```
The above chain shows that ChattyYes is connected to stdIn and it outputs to
grep which is looking for the phrase "Chatty" and its output is connected to
head which will print the first 10 lines it receives.

The PHP code for this is below:
```php
<?php

include 'vendor/autoload.php';

use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$connector = new Command\StandardConnector;

$c = new PS\Scheduler($connector);
$c->addCommand(new Command\ChattyYes);
$c->addCommand(new Command\Grep('Chatty'));
$c->addCommand(new Command\Head));
$c->run();

Output:
Chatty
Chatty
Chatty
Chatty
Chatty
Chatty
Chatty
Chatty
Chatty
Chatty
```
