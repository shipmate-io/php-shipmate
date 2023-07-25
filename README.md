# Interact with Shipmate from your PHP code

## Installation

You can install the package via composer:

```bash
composer require shipmate-io/php-shipmate
```

## Usage

### Job queue

You can interact with your Shipmate job queues as follows:

```php
use Shipmate\Shipmate\JobQueue\JobQueue;
use Shipmate\Shipmate\JobQueue\Job;

$jobQueue = new JobQueue;

// publish a job

$job = new Job(
    payload: [
        'action' => 'send_welcome_email',
        'data' => [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ],
    ],
)

$jobQueue->publishJob(
    queueName: getenv('SHIPMATE_JOB_QUEUE_NAME'),
    queueWorkerUrl: getenv('SHIPMATE_JOB_QUEUE_WORKER_URL'),
    job: $job
);

// handle a job

$requestContents = $httpRequest->getContents();

$job = $jobQueue->parseJob($requestContents);

$job->payload['action'];
$job->payload['data']['first_name'];
$job->payload['data']['last_name'];
```

### Message queue

You can interact with your Shipmate message queues as follows:

```php
use Shipmate\Shipmate\MessageQueue\MessageQueue;
use Shipmate\Shipmate\MessageQueue\Message;

$messageQueue = new MessageQueue;

// publish a message

$message = new Message(
    type: 'user.created',
    payload: [
        'first_name' => 'John',
        'last_name' => 'Doe',
    ],
)

$messageQueue->publishMessage(
    queueName: getenv('SHIPMATE_MESSAGE_QUEUE_NAME'),
    message: $message,
);

// handle a message

$requestContents = $httpRequest->getContents();

$message = $messageQueue->parseMessage($requestContents);

$message->type;
$message->payload['first_name'];
$message->payload['last_name'];
```

### Storage bucket

You can interact with your Shipmate storage buckets as follows:

```php
use Shipmate\Shipmate\StorageBucket\StorageBucket;

$storageBucket = new StorageBucket(
    bucketName: getenv('SHIPMATE_STORAGE_BUCKET_NAME'),
);

// write a file
$storageBucket->write('avatars/1.jpg', $fileContents);

// read a file
$fileContents = $storageBucket->read('avatars/1.jpg');

// copy a file
$storageBucket->copy('avatars/1.jpg', 'avatars/2.jpg');
```

The storage bucket integration is built on top of [Flysystem](https://flysystem.thephpleague.com/), a popular file
storage library for PHP. For a full list of the available methods, see the 
[Flysystem documentation](https://flysystem.thephpleague.com/).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
