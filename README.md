PipeSys
=======

PipeSys (*said Pipes*) is a very basic system of pipes implemented in PHP.

## Requirements
 * PHP >= 5.5

## How it works

The way PipeSys works is by having a chain of commands which are joined together
by the Scheduler to give the effect of pipes by chaining the output of one to
the input of another.
A Scheduler can have many commands added to it and all must follow the
`OutputInterface`.

Commands are special classes which have a `doCommand` method which should return
a Generator, i.e. it should have `yield` statements in it.
When a command wants to read it should use a coroutine call like:
```php
$input = (yield new ReadIntent);
```
By `yield`ing a `ReadIntent` object the command tells the Scheduler that it
wants to read to continue. When data is available for the command it it sent
using the `send()` generator method. `$input` will pick up this sent value and
the generator will continue.

When a command wants to write it should yield the line:
```php
yield $line;
```
The line should be a single line of text, with a platform dependant newline on
the end. (`PHP_EOL`)

Commands at the start or end have access to the `stdin` or `stdout` respectively
of the Scheduler which may correspond to the `stdin` and `stdout` of the
system. (Provided by the `StdIn` and `StdOut` classes.)

The Scheduler uses coöperative scheduling, because PHP is single process
and single threaded there is no way to implement preëmptive scheduling.

When a stdin method is exhausted it should return an `EOF` object.The `EOF`
signals that the end of the stream (EOF = End Of File) has been reached.
Commands that read will most likely want to return once receiving this as they
can't continue any further without anything to read.

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
use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$s = new PS\Scheduler( new PS\IO\StdIn, new PS\IO\StdOut );
$s->addCommand( new Command\ChattyYes );
$s->addCommand( new Command\Head );
$s->run();

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
use ElvenSpellmaker\PipeSys as PS;
use ElvenSpellmaker\PipeSys\Command as Command;

$s = new PS\Scheduler( new PS\IO\StdIn, new PS\IO\StdOut );
$s->addCommand( new Command\ChattyYes );
$s->addCommand( new Command\Grep( 'Chatty' ) );
$s->addCommand( new Command\Head );
$s->run();

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
