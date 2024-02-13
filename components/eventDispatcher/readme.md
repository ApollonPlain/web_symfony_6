# Component EventDispatcher

[Symfony Documentation EventDispatcher component](https://symfony.com/doc/6.4/components/event_dispatcher.html)


The EventDispatcher component provides tools that allow your application components to communicate with each other by dispatching events and listening to them.


Consider the real-world example where you want to provide a plugin system for your project. A plugin should be able to add methods, or do something before or after a method is executed, without interfering with other plugins. This is not an easy problem to solve with single inheritance, and even if multiple inheritance was possible with PHP, it comes with its own drawbacks.


The Symfony EventDispatcher component implements the Mediator and Observer design patterns to make all these things possible and to make your projects truly extensible.


Read the [Events and Event Listeners article](https://symfony.com/doc/6.4/event_dispatcher.html) to learn about how to use it in Symfony applications.




``` class Event implements StoppableEventInterface ```


https://github.com/symfony/symfony/blob/6.4/src/Symfony/Contracts/EventDispatcher/Event.php



The dispatcher is the central object of the event dispatcher system. In general, a single dispatcher is created, which maintains a registry of listeners. 

When an event is dispatched via the dispatcher, it notifies all listeners registered with that event


```PHP
interface EventDispatcherInterface extends ContractsEventDispatcherInterface {

    public function addListener(string $eventName, callable $listener, int $priority = 0);
    public function addSubscriber(EventSubscriberInterface $subscriber);
    public function removeListener(string $eventName, callable $listener);
    public function removeSubscriber(EventSubscriberInterface $subscriber);
    /** @return array<callable[]|callable>   */
    public function getListeners(?string $eventName = null): array;
    public function getListenerPriority(string $eventName, callable $listener): ?int;
    public function hasListeners(?string $eventName = null): bool;

}

```
