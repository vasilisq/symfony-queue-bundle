# Queue Bundle

This bundle provides simple interface for creating and dispatching background commands.

**Tested on Symfony 2.8.**

## Usage

* Register **QueueBundle** in your kernel.
* Create a job class implementing **JobInterface**.
* Register **CRON**-schedule to run **ServeCommand** ("queue:serve") with suitable interval. 
For example, every minute.
* Use **QueueService::enqueue** to put your job into queue.

## Further improvements

* Test on fresher Symfony versions.
* Implement **RedisTaskRepository**.
* Refactor.
* Prepare bundle to register on **packagist**.