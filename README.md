# Laravel workflow [![Build Status](https://cdn.learnku.com/uploads/images/202110/28/27889/qBmRZutSjj.svg)](https://github.com/becage/laravel-workflow)

Use the Symfony Workflow component in Laravel8,PHP7,PHP8
This repository based on @brexis,his project since 2019-09 No longer maintained.
So i modify the code and publish it.

### Installation

    composer require cage/laravel-workflow
### Configuration

Publish the config file

```
    php artisan vendor:publish --provider="Cage\LaravelWorkflow\WorkflowServiceProvider"
```

Configure your workflow in `config/workflow.php`

Use the `WorkflowTrait` inside supported classes

```php
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Cage\LaravelWorkflow\Traits\WorkflowTrait;

class BlogPost extends Model
{
  use WorkflowTrait;
}
```
### Usage

```php
<?php

use App\Models\BlogPost;
use Workflow;

$post        = BlogPost::find(1);
$workflow    = $post->workflow_get();// or $workflow = Workflow::get($post);
$workflow->can($post, 'publish'); //  False
$workflow->can($post, 'to_review'); // True
$transitions = $workflow->getEnabledTransitions($post);// Get the transitions
$definition  = $workflow->getDefinition();// Get the definition
$places      = $workflow->getMarking($post)->getPlaces();// // Get the current places
$metadata    = $workflow->getMetadataStore();// Get the metadata

// Apply a transition
$workflow->apply($post, 'to_review');
$post->save(); // Don't forget to persist the state
```

### Use the events
Register at `app/Providers/EventServiceProvider.php`
```php
protected $subscribe = [
        BlogPostWorkflowSubscriber::class,
    ];
```
Then you can subscribe to an event
Create Listener at `app/Listeners/BlogPostWorkflowSubscriber.php`
```php
<?php

namespace App\Listeners;

use Cage\LaravelWorkflow\Events\GuardEvent;

class BlogPostWorkflowSubscriber
{
    public function onGuard(GuardEvent $event){}

    public function onLeave($event)
    {
        // The event can also proxy to the original event
        $subject = $event->getOriginalEvent()->getSubject();
    }

    public function onTransition($event) {}

    public function onEnter($event) {}

    public function onEntered($event) {}

    public function subscribe($events)
    {
        $events->listen(
            'Cage\LaravelWorkflow\Events\GuardEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onGuard'
        );

        $events->listen(
            'Cage\LaravelWorkflow\Events\LeaveEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onLeave'
        );

        $events->listen(
            'Cage\LaravelWorkflow\Events\TransitionEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onTransition'
        );

        $events->listen(
            'Cage\LaravelWorkflow\Events\EnterEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onEnter'
        );

        $events->listen(
            'Cage\LaravelWorkflow\Events\EnteredEvent',
            'App\Listeners\BlogPostWorkflowSubscriber@onEntered'
        );
    }

}
```

### Dump Workflows
Symfony workflow uses GraphvizDumper to create the workflow image. You may need to install the `dot` command of [Graphviz](http://www.graphviz.org/)

```php
php artisan workflow:dump straight --class App\\Models\\BlogPost --path workflows  --format=svg
```

### For More Information
`https://symfony.com/doc/current/workflow.html`
`https://github.com/brexis/laravel-workflow`
