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

**Note:** The current Scheduler will return upon reaching any generator that is
exhausted, that is to say that the generator's `valid()` method returns false.
This is unlike *nix pipes and is subject to change.

An example of the different behaviour is:
```sh
echo "foo" | head -n 1 | yes
```

In Unix this will satisfy head and it will terminate. Because `yes` never reads
it continues to spam the terminal with `y` without a care.

Using `PipeSys`, the yes will be terminated because 'echo' is satisfied and
returns after it has yielded once. Yes will only print to the terminal thrice
before it is terminated (due to how PipeSys works, it takes one cycle for `Head`
to request to read, one to satisfy `Head`, one cycle to find out `Puts` is
invalid, and one until it breaks and the whole chain terminates.)

Cycle 1:
 * `Puts` outputs 'foo'.
 * `Head` signals its intent to read.
 * `Yes` prints `y` to the terminal.

Cycle 2:
 * `Puts` is 'blocked' as it has output to be read and so isn't run.
 * `Head` receives the output from `Puts` and continues, `yield`ing `Put`'s value.
 * `Yes` prints another `y` to the terminal.

Cycle 3:
 * `Puts` returns.
 * `Head` is 'blocked' as it has output for `Yes` and so is skipped.
 * `Yes` prints yet another `y` to the terminal.

Cycle 4:
 * The `Scheduler` detects Puts is exhausted and breaks out and returns.

The equivalent `PipeSys` code to the above scenario is below:
```php
use ElvenSpellmaker\PipeSystem as PS;
use ElvenSpellmaker\PipeSystem\Command as Command;

$s = new PS\Scheduler(new PS\IO\StdIn, new PS\IO\StdOut);
$s->addCommand(new Command\Puts('foo');
$s->addCommand(new Command\Head(1);
$s->addCommand(new Command\Yes);
$s->run();
```

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
use ElvenSpellmaker\PipeSystem as PS;
use ElvenSpellmaker\PipeSystem\Command as Command;

$s = new PS\Scheduler(new PS\IO\StdIn, new PS\IO\StdOut);
$s->addCommand(new Command\ChattyYes);
$s->addCommand(new Command\Head);
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
use ElvenSpellmaker\PipeSystem as PS;
use ElvenSpellmaker\PipeSystem\Command as Command;

$s = new PS\Scheduler(new PS\IO\StdIn, new PS\IO\StdOut);
$s->addCommand(new Command\ChattyYes);
$s->addCommand(new Command\Grep('Chatty');
$s->addCommand(new Command\Head);
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
